<div class="relative w-full max-w-5xl mx-auto mt-20 mb-8 overflow-hidden pt-12 pb-16">
    <!-- Radial Glow below horizon -->
    <div class="absolute bottom-0 inset-x-0 h-48 glow-horizon pointer-events-none"></div>

    <!-- Glowing Curved Arc Horizon -->
    <div class="relative flex flex-col items-center">
        <!-- SVG Horizon Curve with vibrant glow -->
        <div class="w-full h-32 relative">
            <svg class="w-full h-full" viewBox="0 0 1000 160" preserveAspectRatio="none" fill="none">
                <defs>
                    <linearGradient id="horizonGlowPartial" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#f97316" stop-opacity="0" />
                        <stop offset="30%" stop-color="#ea580c" stop-opacity="0.5" />
                        <stop offset="50%" stop-color="#ffedd5" stop-opacity="1" />
                        <stop offset="70%" stop-color="#ea580c" stop-opacity="0.5" />
                        <stop offset="100%" stop-color="#f97316" stop-opacity="0" />
                    </linearGradient>
                    <filter id="glowBlurPartial" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="6" result="blur" />
                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                </defs>
                <!-- Deep Ambient Curve -->
                <path d="M 0 160 Q 500 10 1000 160" stroke="#f97316" stroke-width="4" stroke-opacity="0.3" filter="url(#glowBlurPartial)" fill="none" />
                <!-- Crisp Luminous Center Curve -->
                <path d="M 0 160 Q 500 10 1000 160" stroke="url(#horizonGlowPartial)" stroke-width="2.5" fill="none" />
            </svg>
        </div>

        <!-- Ambient Glow Flare on apex -->
        <div class="absolute top-10 left-1/2 -translate-x-1/2 w-40 h-10 bg-orange-400 blur-2xl opacity-60 pointer-events-none"></div>
    </div>
</div>
