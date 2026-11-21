<x-filament-panels::page.simple>
    <style>
        /* Scoped Styles for SIPETRA SSO Login Page */
        .fi-simple-main {
            background: #ffffff !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07), 0 0 1px 1px rgba(0, 0, 0, 0.05) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            padding: 2.25rem 2rem !important;
        }

        .fi-simple-header {
            margin-bottom: 1.5rem !important;
            text-align: center !important;
        }

        .fi-simple-header-heading {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            letter-spacing: -0.02em !important;
            margin-top: 0.5rem !important;
        }

        .fi-simple-header-subheading {
            font-size: 0.875rem !important;
            color: #64748b !important;
            margin-top: 0.25rem !important;
            line-height: 1.4 !important;
        }

        .sso-page-wrapper {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* Flash Notifications */
        .sso-alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            line-height: 1.4;
            margin-bottom: 1.25rem;
            box-sizing: border-box;
        }
        .sso-alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }
        .sso-alert-info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }
        .sso-alert-warning {
            background-color: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
        }
        .sso-alert-icon {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
            margin-top: 0.125rem;
        }
        .sso-alert-title {
            font-weight: 600;
            margin-bottom: 0.125rem;
        }
        .sso-alert-desc {
            font-size: 0.8125rem;
            opacity: 0.95;
        }

        /* Hero SSO Button Container */
        .sso-hero-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .sso-btn-primary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1.125rem;
            background-color: #005596;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.01em;
            box-shadow: 0 4px 14px rgba(0, 85, 150, 0.28);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            box-sizing: border-box;
            user-select: none;
        }
        .sso-btn-primary:hover {
            background-color: #004478;
            box-shadow: 0 6px 20px rgba(0, 85, 150, 0.38);
            transform: translateY(-1px);
            color: #ffffff !important;
        }
        .sso-btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 85, 150, 0.25);
        }

        .sso-btn-content {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            flex-grow: 1;
        }

        /* Badge for BPS Logo */
        .sso-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            min-width: 2.25rem;
            min-height: 2.25rem;
            max-width: 2.25rem;
            max-height: 2.25rem;
            border-radius: 0.5rem;
            background-color: #ffffff;
            padding: 0.3rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
            flex-shrink: 0;
            box-sizing: border-box;
        }
        .sso-badge-logo {
            width: 100% !important;
            height: 100% !important;
            max-width: 100% !important;
            max-height: 100% !important;
            object-fit: contain !important;
            display: block !important;
        }

        .sso-btn-text {
            color: #ffffff !important;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Arrow Icon */
        .sso-btn-arrow {
            width: 1.25rem;
            height: 1.25rem;
            color: rgba(255, 255, 255, 0.85);
            flex-shrink: 0;
            margin-left: 0.5rem;
            transition: transform 0.2s ease;
        }
        .sso-btn-primary:hover .sso-btn-arrow {
            transform: translateX(3px);
            color: #ffffff;
        }

        /* Spinner */
        @keyframes sso-spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .sso-spinner {
            display: none;
            width: 1.5rem;
            height: 1.5rem;
            animation: sso-spin 1s linear infinite;
            color: #ffffff;
            flex-shrink: 0;
        }

        /* Subtitle Text */
        .sso-subtitle {
            font-size: 0.75rem;
            text-align: center;
            color: #64748b;
            line-height: 1.5;
            max-width: 22rem;
            margin: 0.75rem auto 0 auto;
        }

        /* Emergency Fallback Section */
        .sso-fallback-section {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e2e8f0;
            width: 100%;
            text-align: center;
        }
        .sso-fallback-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            background: transparent;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .sso-fallback-toggle:hover {
            color: #1e293b;
            background-color: #f1f5f9;
        }
        .sso-fallback-chevron {
            width: 0.875rem;
            height: 0.875rem;
            transition: transform 0.2s ease;
        }
        .sso-fallback-chevron.is-open {
            transform: rotate(180deg);
        }
        .sso-fallback-content {
            margin-top: 1rem;
            text-align: left;
        }
    </style>

    <div class="sso-page-wrapper">
        {{-- Flash Error Notification --}}
        @if (session('error'))
            <div class="sso-alert sso-alert-danger" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.875rem 1rem; border-radius: 0.75rem; background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; margin-bottom: 1.25rem;">
                <svg class="sso-alert-icon" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0; margin-top: 0.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="sso-alert-title" style="font-weight: 600; margin: 0 0 0.125rem 0;">Gagal Masuk</p>
                    <p class="sso-alert-desc" style="font-size: 0.8125rem; margin: 0; opacity: 0.95;">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if (session('status'))
            <div class="sso-alert sso-alert-info" style="display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.875rem 1rem; border-radius: 0.75rem; background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; margin-bottom: 1.25rem;">
                <svg class="sso-alert-icon" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0; margin-top: 0.125rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="sso-alert-desc" style="font-size: 0.8125rem; margin: 0;">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        {{-- Primary Action: SIPETRA SSO Hero Button --}}
        <div class="sso-hero-card" style="display: flex; flex-direction: column; align-items: center; width: 100%; margin-bottom: 0.5rem;">
            <a href="{{ route('sipetra.login') }}"
               id="sipetra-sso-btn"
               onclick="if(this.getAttribute('data-submitting')) return false; this.setAttribute('data-submitting', '1'); this.style.pointerEvents='none'; this.style.opacity='0.85'; document.getElementById('sso-spinner').style.display='inline-block'; document.getElementById('sso-icon-badge').style.display='none'; document.getElementById('sso-btn-text').innerText='Menghubungkan ke SIPETRA...';"
               class="sso-btn-primary"
               style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 0.75rem 1.125rem; background-color: #005596; color: #ffffff !important; text-decoration: none !important; border-radius: 0.75rem; font-weight: 600; font-size: 0.95rem; box-shadow: 0 4px 14px rgba(0, 85, 150, 0.28); border: 1px solid rgba(255, 255, 255, 0.15); box-sizing: border-box; cursor: pointer;">
                
                <div class="sso-btn-content" style="display: flex; align-items: center; gap: 0.875rem; flex-grow: 1;">
                    {{-- Logo BPS Badge with Padding & Rounded Corners --}}
                    <span id="sso-icon-badge" class="sso-badge" style="display: flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; min-width: 2.25rem; min-height: 2.25rem; max-width: 2.25rem; max-height: 2.25rem; border-radius: 0.5rem; background-color: #ffffff; padding: 0.3rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12); flex-shrink: 0; box-sizing: border-box;">
                        <img id="sso-icon"
                             src="{{ asset('images/logo_bps.png') }}"
                             alt="Logo BPS"
                             class="sso-badge-logo"
                             style="width: 100% !important; height: 100% !important; max-width: 100% !important; max-height: 100% !important; object-fit: contain !important; display: block !important;"
                             loading="lazy">
                    </span>

                    {{-- Spinner --}}
                    <svg id="sso-spinner" class="sso-spinner" style="display: none; width: 1.5rem; height: 1.5rem; color: #ffffff; flex-shrink: 0;" viewBox="0 0 24 24" fill="none">
                        <circle opacity="0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path opacity="0.75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>

                    <span id="sso-btn-text" class="sso-btn-text" style="color: #ffffff !important; font-weight: 600; white-space: nowrap;">Masuk dengan SIPETRA SSO</span>
                </div>

                {{-- Navigation Arrow --}}
                <svg class="sso-btn-arrow" style="width: 1.25rem; height: 1.25rem; color: rgba(255, 255, 255, 0.85); flex-shrink: 0; margin-left: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>

            <p class="sso-subtitle" style="font-size: 0.75rem; text-align: center; color: #64748b; line-height: 1.5; max-width: 22rem; margin: 0.75rem auto 0 auto;">
                Autentikasi terpusat Badan Pusat Statistik Kabupaten Demak. Gunakan akun SIPETRA Anda untuk masuk secara aman.
            </p>
        </div>

        {{-- Emergency Fallback Collapsible Section --}}
        <div class="sso-fallback-section" style="margin-top: 1.5rem; padding-top: 1.25rem; border-top: 1px solid #e2e8f0; width: 100%; text-align: center;" x-data="{ show: @entangle('showFallback') }">
            <div>
                <button type="button"
                        @click="show = !show"
                        class="sso-fallback-toggle"
                        style="display: inline-flex; align-items: center; gap: 0.375rem; background: transparent; border: none; padding: 0.375rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 500; color: #64748b; cursor: pointer;">
                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Opsi Darurat / Pengembang</span>
                    <svg class="sso-fallback-chevron" :class="{ 'is-open': show }" style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>

            <div x-show="show" x-collapse x-cloak class="sso-fallback-content" style="margin-top: 1rem; text-align: left;">
                <div class="sso-alert sso-alert-warning" style="display: flex; align-items: flex-start; gap: 0.5rem; padding: 0.75rem 0.875rem; font-size: 0.75rem; line-height: 1.4; color: #92400e; background-color: #fffbeb; border: 1px solid #fde68a; border-radius: 0.5rem; margin-bottom: 1.25rem;">
                    <svg class="sso-alert-icon" style="width: 1.125rem; height: 1.125rem; flex-shrink: 0; margin-top: 0.125rem; color: #d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Mode darurat hanya digunakan saat server SIPETRA tidak dapat diakses atau untuk pengujian lokal.</span>
                </div>

                {{ $this->content }}
            </div>
        </div>
    </div>
</x-filament-panels::page.simple>
