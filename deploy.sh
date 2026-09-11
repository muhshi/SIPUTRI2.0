#!/usr/bin/env bash

# ==============================================================================
# SIPUTRI 2.0 - Smart Deployment Script
# Mendukung eksekusi langsung (native) maupun via Docker (FrankenPHP)
# Hanya melakukan build & install dependensi saat benar-benar dibutuhkan.
# ==============================================================================

set -e

# Warna output terminal
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Flag opsi
FORCE_BUILD=false
SKIP_BUILD=false
BRANCH=""

# Parsing argumen
while [[ "$#" -gt 0 ]]; do
    case $1 in
        -f|--force|--force-build) FORCE_BUILD=true ;;
        --skip-build) SKIP_BUILD=true ;;
        -b|--branch) BRANCH="$2"; shift ;;
        -h|--help)
            echo "Penggunaan: ./deploy.sh [OPSI]"
            echo "Opsi:"
            echo "  -f, --force, --force-build  Paksa jalankan build dan install semua dependensi"
            echo "  --skip-build                Lewati proses build frontend"
            echo "  -b, --branch <nama_branch>  Tentukan branch git (default: branch aktif saat ini)"
            echo "  -h, --help                  Tampilkan panduan ini"
            exit 0
            ;;
        *) echo -e "${RED}Opsi tidak dikenal: $1${NC}"; exit 1 ;;
    esac
    shift
done

echo -e "${CYAN}======================================================${NC}"
echo -e "${CYAN}        SIPUTRI 2.0 - SMART DEPLOYMENT START          ${NC}"
echo -e "${CYAN}======================================================${NC}"

# Pastikan berada di root direktori project
cd "$(dirname "$0")"

# ------------------------------------------------------------------------------
# Helper: Deteksi Environment PHP & Composer (Host vs Docker)
# ------------------------------------------------------------------------------
CONTAINER_NAME="siputri-franken"

is_container_running() {
    command -v docker &> /dev/null && docker ps --format '{{.Names}}' 2>/dev/null | grep -qE "^${CONTAINER_NAME}$"
}

run_artisan() {
    if command -v php &> /dev/null; then
        php artisan "$@"
    elif is_container_running; then
        docker exec -i "$CONTAINER_NAME" php artisan "$@"
    elif command -v docker &> /dev/null && [ -f "docker-compose.yml" ]; then
        docker compose exec -T "$CONTAINER_NAME" php artisan "$@" 2>/dev/null || docker-compose exec -T "$CONTAINER_NAME" php artisan "$@"
    else
        echo -e "${RED}❌ PHP tidak ditemukan di host ataupun di container Docker ($CONTAINER_NAME).${NC}" >&2
        return 1
    fi
}

run_composer() {
    if command -v composer &> /dev/null; then
        composer "$@"
    elif is_container_running; then
        docker exec -i "$CONTAINER_NAME" composer "$@"
    elif command -v docker &> /dev/null && [ -f "docker-compose.yml" ]; then
        docker compose exec -T "$CONTAINER_NAME" composer "$@" 2>/dev/null || docker-compose exec -T "$CONTAINER_NAME" composer "$@"
    else
        echo -e "${RED}❌ Composer tidak ditemukan di host ataupun di container Docker.${NC}" >&2
        return 1
    fi
}

# ------------------------------------------------------------------------------
# Git Pull
# ------------------------------------------------------------------------------
if [ -z "$BRANCH" ]; then
    BRANCH=$(git rev-parse --abbrev-ref HEAD)
fi

echo -e "${BLUE}📌 Target Branch: ${YELLOW}${BRANCH}${NC}"

# Amankan jika ada perubahan file lokal di server (misal package-lock.json dari npm)
if ! git diff --quiet 2>/dev/null || ! git diff --cached --quiet 2>/dev/null; then
    echo -e "${YELLOW}⚠️  Terdeteksi perubahan file lokal di server. Menyimpan sementara (git stash)...${NC}"
    git stash || true
fi

OLD_COMMIT=$(git rev-parse HEAD 2>/dev/null || echo "initial")

echo -e "${BLUE}⬇️  Menarik perubahan dari remote (git pull origin ${BRANCH})...${NC}"
git pull origin "$BRANCH"

NEW_COMMIT=$(git rev-parse HEAD)

echo -e "${GREEN}✓ Git commit lama: ${OLD_COMMIT:0:7}${NC}"
echo -e "${GREEN}✓ Git commit baru: ${NEW_COMMIT:0:7}${NC}"

if [ "$OLD_COMMIT" = "$NEW_COMMIT" ] && [ "$FORCE_BUILD" = false ]; then
    echo -e "${YELLOW}ℹ️  Tidak ada commit baru pada branch ini.${NC}"
    CHANGED_FILES=""
else
    if [ "$OLD_COMMIT" = "initial" ]; then
        CHANGED_FILES=$(git ls-files)
    else
        CHANGED_FILES=$(git diff --name-only "$OLD_COMMIT" "$NEW_COMMIT")
    fi
fi

# Mode Maintenance
echo -e "${YELLOW}🚧 Mengaktifkan mode maintenance (artisan down)...${NC}"
run_artisan down || true

# ------------------------------------------------------------------------------
# PHP / Composer Dependencies
# ------------------------------------------------------------------------------
NEED_COMPOSER=false

if [ "$FORCE_BUILD" = true ]; then
    NEED_COMPOSER=true
elif [ ! -d "vendor" ]; then
    echo -e "${YELLOW}⚠️  Folder 'vendor' tidak ditemukan.${NC}"
    NEED_COMPOSER=true
elif echo "$CHANGED_FILES" | grep -qE '^composer\.(json|lock)$'; then
    echo -e "${YELLOW}📦 Terdeteksi perubahan pada composer.json / composer.lock.${NC}"
    NEED_COMPOSER=true
fi

if [ "$NEED_COMPOSER" = true ]; then
    echo -e "${BLUE}⚙️  Menjalankan composer install...${NC}"
    run_composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
else
    echo -e "${GREEN}⏩ Dependensi PHP tidak berubah. Skip composer install.${NC}"
fi

# ------------------------------------------------------------------------------
# Frontend Assets (Node / Vite)
# ------------------------------------------------------------------------------
NEED_NPM_BUILD=false

if [ "$SKIP_BUILD" = true ]; then
    echo -e "${YELLOW}⏩ Menolak build aset karena flag --skip-build diberikan.${NC}"
elif [ "$FORCE_BUILD" = true ]; then
    NEED_NPM_BUILD=true
elif [ ! -f "public/build/manifest.json" ]; then
    echo -e "${YELLOW}⚠️  File 'public/build/manifest.json' tidak ditemukan (belum dibuild).${NC}"
    NEED_NPM_BUILD=true
else
    if echo "$CHANGED_FILES" | grep -qE '^package(-lock)?\.json$'; then
        echo -e "${YELLOW}📦 Terdeteksi perubahan pada package.json / package-lock.json.${NC}"
        NEED_NPM_BUILD=true
    elif echo "$CHANGED_FILES" | grep -qE '^(vite\.config\.js|resources/|public/)'; then
        echo -e "${YELLOW}🎨 Terdeteksi perubahan pada file aset frontend.${NC}"
        NEED_NPM_BUILD=true
    fi
fi

if [ "$NEED_NPM_BUILD" = true ] && [ "$SKIP_BUILD" = false ]; then
    # Cek versi Node di host
    HOST_NODE_VER=0
    if command -v node &> /dev/null; then
        HOST_NODE_VER=$(node -v 2>/dev/null | sed 's/v//' | cut -d. -f1 || echo "0")
    fi

    if [ "$HOST_NODE_VER" -ge 20 ]; then
        echo -e "${BLUE}🔨 Menggunakan Node.js host (v$(node -v)) untuk build aset...${NC}"
        if [ ! -d "node_modules" ] || echo "$CHANGED_FILES" | grep -qE '^package(-lock)?\.json$'; then
            npm install
        fi
        npm run build
    elif command -v docker &> /dev/null; then
        echo -e "${YELLOW}ℹ️  Node.js di host (${HOST_NODE_VER:-tidak ada}) < 20. Menggunakan Docker (node:22-alpine)...${NC}"
        docker run --rm -v "$(pwd):/app" -w /app node:22-alpine sh -c "npm install && npm run build"
    else
        echo -e "${RED}❌ Node.js versi >= 20 dibutuhkan untuk Vite, dan Docker tidak ditemukan.${NC}"
        exit 1
    fi
else
    if [ "$SKIP_BUILD" = false ]; then
        echo -e "${GREEN}⏩ Aset frontend tidak berubah & manifest sudah ada. Skip npm run build.${NC}"
    fi
fi

# ------------------------------------------------------------------------------
# Database Migration
# ------------------------------------------------------------------------------
echo -e "${BLUE}🗄️  Menjalankan migrasi database...${NC}"
run_artisan migrate --force

# ------------------------------------------------------------------------------
# Optimasi Cache Laravel
# ------------------------------------------------------------------------------
echo -e "${BLUE}⚡ Menyegarkan cache konfigurasi, route, dan view...${NC}"
run_artisan optimize:clear
run_artisan config:cache
run_artisan route:cache
run_artisan view:cache

# Filament component cache jika ada
run_artisan filament:cache-components 2>/dev/null || true

# Storage link
if [ ! -L "public/storage" ] && [ ! -d "public/storage" ]; then
    echo -e "${BLUE}🔗 Menghubungkan storage...${NC}"
    run_artisan storage:link || true
fi

# ------------------------------------------------------------------------------
# Restart/Reload Docker Container (FrankenPHP)
# ------------------------------------------------------------------------------
if is_container_running; then
    echo -e "${BLUE}🔄 Me-restart container $CONTAINER_NAME agar perubahan PHP aktif...${NC}"
    docker restart "$CONTAINER_NAME" || true
elif command -v docker &> /dev/null && [ -f "docker-compose.yml" ]; then
    docker compose restart "$CONTAINER_NAME" 2>/dev/null || docker-compose restart "$CONTAINER_NAME" 2>/dev/null || true
fi

# ------------------------------------------------------------------------------
# Nonaktifkan Maintenance Mode
# ------------------------------------------------------------------------------
echo -e "${GREEN}🚀 Mengaktifkan kembali aplikasi (artisan up)...${NC}"
run_artisan up || true

echo -e "${CYAN}======================================================${NC}"
echo -e "${GREEN}🎉 DEPLOYMENT BERHASIL SELESAI!${NC}"
echo -e "${CYAN}======================================================${NC}"
