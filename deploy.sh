#!/usr/bin/env bash

# ==============================================================================
# SIPUTRI 2.0 - Smart Deployment Script
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
            echo "  --skip-build                Lewati proses npm run build"
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

# Deteksi branch saat ini jika tidak dispesifikasikan
if [ -z "$BRANCH" ]; then
    BRANCH=$(git rev-parse --abbrev-ref HEAD)
fi

echo -e "${BLUE}📌 Target Branch: ${YELLOW}${BRANCH}${NC}"

# 1. Catat commit sebelum pull
OLD_COMMIT=$(git rev-parse HEAD 2>/dev/null || echo "initial")

# 2. Ambil perubahan terbaru dari Git
echo -e "${BLUE}⬇️  Menarik perubahan dari remote (git pull origin ${BRANCH})...${NC}"
git pull origin "$BRANCH"

NEW_COMMIT=$(git rev-parse HEAD)

echo -e "${GREEN}✓ Git commit lama: ${OLD_COMMIT:0:7}${NC}"
echo -e "${GREEN}✓ Git commit baru: ${NEW_COMMIT:0:7}${NC}"

# Tentukan apakah ada perubahan file tertentu
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

# 3. Aktifkan Maintenance Mode (jika aplikasi sudah berjalan dan bukan first run)
if [ -f "artisan" ] && [ -f ".env" ]; then
    echo -e "${YELLOW}🚧 Mengaktifkan mode maintenance (php artisan down)...${NC}"
    php artisan down || true
fi

# ------------------------------------------------------------------------------
# 4. CEK DEPENDENSI PHP (Composer)
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
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
else
    echo -e "${GREEN}⏩ Dependensi PHP tidak berubah. Skip composer install.${NC}"
fi

# ------------------------------------------------------------------------------
# 5. CEK DEPENDENSI & BUILD FRONTEND (Node.js / Vite)
# ------------------------------------------------------------------------------
NEED_NPM_INSTALL=false
NEED_NPM_BUILD=false

if [ "$SKIP_BUILD" = true ]; then
    echo -e "${YELLOW}⏩ Menolak build aset karena flag --skip-build diberikan.${NC}"
elif [ "$FORCE_BUILD" = true ]; then
    NEED_NPM_INSTALL=true
    NEED_NPM_BUILD=true
elif [ ! -f "public/build/manifest.json" ]; then
    echo -e "${YELLOW}⚠️  File 'public/build/manifest.json' tidak ditemukan (belum dibuild).${NC}"
    NEED_NPM_BUILD=true
    if [ ! -d "node_modules" ]; then
        NEED_NPM_INSTALL=true
    fi
else
    # Cek perubahan file npm
    if echo "$CHANGED_FILES" | grep -qE '^package(-lock)?\.json$'; then
        echo -e "${YELLOW}📦 Terdeteksi perubahan pada package.json / package-lock.json.${NC}"
        NEED_NPM_INSTALL=true
        NEED_NPM_BUILD=true
    elif echo "$CHANGED_FILES" | grep -qE '^(vite\.config\.js|resources/|public/)'; then
        echo -e "${YELLOW}🎨 Terdeteksi perubahan pada file aset frontend (resources/ / vite.config.js).${NC}"
        NEED_NPM_BUILD=true
    fi
fi

if [ "$NEED_NPM_INSTALL" = true ]; then
    echo -e "${BLUE}⚙️  Menginstall dependensi NPM (npm install)...${NC}"
    npm install
fi

if [ "$NEED_NPM_BUILD" = true ] && [ "$SKIP_BUILD" = false ]; then
    echo -e "${BLUE}🔨 Melakukan kompilasi aset frontend (npm run build)...${NC}"
    npm run build
else
    if [ "$SKIP_BUILD" = false ]; then
        echo -e "${GREEN}⏩ Aset frontend tidak berubah & manifest sudah ada. Skip npm run build.${NC}"
    fi
fi

# ------------------------------------------------------------------------------
# 6. DATABASE MIGRATION
# ------------------------------------------------------------------------------
if [ -f ".env" ]; then
    echo -e "${BLUE}🗄️  Menjalankan migrasi database yang belum berjalan...${NC}"
    # Catatan: Aturan sistem melarang drop/truncate. migrate --force hanya menambahkan tabel/kolom baru secara aman.
    php artisan migrate --force
fi

# ------------------------------------------------------------------------------
# 7. OPTIMASI & CACHING LARAVEL
# ------------------------------------------------------------------------------
echo -e "${BLUE}⚡ Membersihkan dan menyegarkan cache aplikasi...${NC}"
php artisan optimize:clear

echo -e "${BLUE}⚡ Membuat cache konfigurasi, route, dan view...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Cache Filament jika didukung
if php artisan list | grep -q "filament:cache-components"; then
    php artisan filament:cache-components || true
fi

# Pastikan storage link tersedia
if [ ! -L "public/storage" ] && [ ! -d "public/storage" ]; then
    echo -e "${BLUE}🔗 Menghubungkan storage (php artisan storage:link)...${NC}"
    php artisan storage:link || true
fi

# ------------------------------------------------------------------------------
# 8. CONTAINER / DOCKER RESTART (Jika menggunakan docker-compose)
# ------------------------------------------------------------------------------
if command -v docker &> /dev/null; then
    if [ -f "docker-compose.yml" ]; then
        # Cek apakah container siputri-franken sedang berjalan
        if docker ps --format '{{.Names}}' | grep -qE 'siputri-franken'; then
            echo -e "${BLUE}🔄 Me-reload container FrankenPHP/Caddy...${NC}"
            docker compose restart siputri-franken || docker-compose restart siputri-franken || true
        fi
    fi
fi

# ------------------------------------------------------------------------------
# 9. Nonaktifkan Maintenance Mode
# ------------------------------------------------------------------------------
if [ -f "artisan" ]; then
    echo -e "${GREEN}🚀 Mengaktifkan kembali aplikasi (php artisan up)...${NC}"
    php artisan up || true
fi

echo -e "${CYAN}======================================================${NC}"
echo -e "${GREEN}🎉 DEPLOYMENT BERHASIL SELESAI!${NC}"
echo -e "${CYAN}======================================================${NC}"
