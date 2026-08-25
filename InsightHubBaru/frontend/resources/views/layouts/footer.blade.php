<style>
    /* ── Global Footer ──────────────────────────────── */
    .global-footer {
        position: relative;
        background: linear-gradient(135deg, #0a1f6e 0%, #0d2580 35%, #0f2fa0 65%, #0a1f6e 100%);
        text-align: center;
        padding: 3.5rem 2rem 2.5rem;
        font-family: 'Inter', sans-serif;
        overflow: hidden;
    }

    /* ── Abstract Wave Decorations (left & right) ─── */
    .global-footer::before,
    .global-footer::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 280px;
        pointer-events: none;
        opacity: 0.18;
        background-repeat: no-repeat;
        background-size: contain;
    }
    /* Left wave – SVG data URI */
    .global-footer::before {
        left: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 280 300' fill='none'%3E%3Cpath d='M-40 280 Q60 200 20 120 Q-20 40 80 -20' stroke='%237eb3ff' stroke-width='1.5' fill='none'/%3E%3Cpath d='M-20 300 Q80 220 40 140 Q0 60 100 0' stroke='%237eb3ff' stroke-width='1' fill='none'/%3E%3Cpath d='M10 310 Q110 230 70 150 Q30 70 130 10' stroke='%237eb3ff' stroke-width='0.8' fill='none'/%3E%3Cpath d='M40 320 Q140 240 100 160 Q60 80 160 20' stroke='%237eb3ff' stroke-width='0.6' fill='none'/%3E%3C/svg%3E");
        background-position: left center;
    }
    /* Right wave */
    .global-footer::after {
        right: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 280 300' fill='none'%3E%3Cpath d='M320 20 Q220 100 260 180 Q300 260 200 320' stroke='%237eb3ff' stroke-width='1.5' fill='none'/%3E%3Cpath d='M300 0 Q200 80 240 160 Q280 240 180 300' stroke='%237eb3ff' stroke-width='1' fill='none'/%3E%3Cpath d='M270 -10 Q170 70 210 150 Q250 230 150 290' stroke='%237eb3ff' stroke-width='0.8' fill='none'/%3E%3Cpath d='M240 -20 Q140 60 180 140 Q220 220 120 280' stroke='%237eb3ff' stroke-width='0.6' fill='none'/%3E%3C/svg%3E");
        background-position: right center;
    }

    /* ── Dot Pattern – bottom-left & top-right ─────── */
    .gf-dots-bl,
    .gf-dots-tr {
        position: absolute;
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 6px;
        pointer-events: none;
        opacity: 0.2;
    }
    .gf-dots-bl {
        bottom: 18px;
        left: 28px;
    }
    .gf-dots-tr {
        top: 18px;
        right: 28px;
    }
    .gf-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #7eb3ff;
    }

    /* ── Inner content wrapper ──────────────────────── */
    .gf-inner {
        position: relative;
        z-index: 2;
        max-width: 700px;
        margin: 0 auto;
    }

    /* ── "MEDIA SOSIAL" label ───────────────────────── */
    .gf-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 1.5rem;
    }

    /* ── Social icons row ───────────────────────────── */
    .gf-socials {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .gf-social-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        text-decoration: none;
        font-size: 1.35rem;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        flex-shrink: 0;
    }
    .gf-social-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.45);
    }
    .gf-yt  { background: #FF0000; color: #fff; }
    .gf-ig  { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fd5949 45%, #d6249f 60%, #285AEB 90%); color: #fff; }
    .gf-fb  { background: #1877F2; color: #fff; }
    .gf-tt  { background: #000000; color: #fff; }

    /* ── Divider ────────────────────────────────────── */
    .gf-divider {
        width: 58%;
        max-width: 420px;
        height: 1px;
        background: rgba(255, 255, 255, 0.18);
        margin: 0 auto 1.75rem;
    }

    /* ── Copyright block ────────────────────────────── */
    .gf-copyright {
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.8;
    }
    .gf-copyright .gf-credit {
        display: block;
        font-size: 0.72rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.4);
        margin-top: 0.35rem;
    }

    /* ── Responsive ─────────────────────────────────── */
    @media (max-width: 600px) {
        .global-footer::before,
        .global-footer::after { width: 160px; opacity: 0.1; }
        .gf-dots-bl, .gf-dots-tr { display: none; }
        .gf-social-link { width: 46px; height: 46px; font-size: 1.2rem; }
        .gf-divider { width: 72%; }
    }
</style>

<footer class="global-footer">

    <!-- Dot patterns -->
    <div class="gf-dots-bl" aria-hidden="true">
        @for($i = 0; $i < 30; $i++) <span class="gf-dot"></span> @endfor
    </div>
    <div class="gf-dots-tr" aria-hidden="true">
        @for($i = 0; $i < 30; $i++) <span class="gf-dot"></span> @endfor
    </div>

    <div class="gf-inner">

        <!-- Label -->
        <span class="gf-label">Media Sosial</span>

        <!-- Social icons -->
        <div class="gf-socials">
            <!-- YouTube -->
            <a href="https://www.youtube.com/@PPEJPKemendag" target="_blank" rel="noopener noreferrer" class="gf-social-link gf-yt" aria-label="YouTube">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.58 7.19c-.23-.86-.91-1.54-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42c-.86.23-1.54.91-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81c.23.86.91 1.54 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42c.86-.23 1.54-.91 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81zM9.75 15.02V8.98L15 12l-5.25 3.02z"/>
                </svg>
            </a>
            <!-- Instagram -->
            <a href="https://www.instagram.com/ppejp.kemendag" target="_blank" rel="noopener noreferrer" class="gf-social-link gf-ig" aria-label="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                </svg>
            </a>
            <!-- Facebook -->
            <a href="https://www.facebook.com/PPEJP.Kemendag" target="_blank" rel="noopener noreferrer" class="gf-social-link gf-fb" aria-label="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/>
                </svg>
            </a>
            <!-- TikTok -->
            <a href="https://www.tiktok.com/@ppejp.kemendag" target="_blank" rel="noopener noreferrer" class="gf-social-link gf-tt" aria-label="TikTok">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/>
                </svg>
            </a>
        </div>

        <!-- Divider -->
        <div class="gf-divider"></div>

        <!-- Copyright -->
        <div class="gf-copyright">
            <span>&copy; 2026 SOVIE. Seluruh hak cipta dilindungi.</span>
            <span class="gf-credit">Created by Lujeng Luthfiyah &ndash; Telkom University Jakarta &ndash; PPEJP</span>
        </div>

    </div>
</footer>
