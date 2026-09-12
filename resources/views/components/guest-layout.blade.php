<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MediPortal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500&family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .ecg-path {
            fill: none; stroke: #E8A33D; stroke-width: 2;
            stroke-linecap: round; stroke-linejoin: round;
            stroke-dasharray: 600; stroke-dashoffset: 600;
            animation: draw 3.2s linear infinite;
        }
        @keyframes draw {
            0%   { stroke-dashoffset: 600; opacity: .4; }
            50%  { stroke-dashoffset: 0;   opacity: 1;  }
            100% { stroke-dashoffset: -600; opacity: .4; }
        }
        @media (prefers-reduced-motion: reduce) {
            .ecg-path { animation: none; stroke-dashoffset: 0; }
        }
    </style>
</head>
<body class="font-sans antialiased" style="font-family:'Inter',sans-serif;">

    <div class="min-h-screen flex items-center justify-center p-6" style="background:#F4FAF8;">
        <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 bg-white rounded-2xl overflow-hidden"
             style="box-shadow:0 30px 60px -20px rgba(15,59,56,0.25);">

            {{-- LEFT: brand + ECG signature (hidden on mobile) --}}
            <div class="hidden md:flex flex-col justify-between p-10 text-white"
                 style="background: radial-gradient(circle at 20% 15%, #175C55 0%, #0F3B38 55%, #082220 100%);">

                <div class="flex items-center gap-2 text-xs tracking-widest uppercase"
                     style="font-family:'IBM Plex Mono',monospace; color:#9FCFC7;">
                    <span class="w-2 h-2 rounded-full" style="background:#E8A33D; box-shadow:0 0 0 4px rgba(232,163,61,0.25);"></span>
                    {{ config('app.name', 'MediPortal') }} · Clinician Access
                </div>

                <div class="mt-10">
                    <h1 class="text-3xl leading-snug max-w-xs" style="font-family:'Fraunces',serif; font-weight:500;">
                        Every patient's story starts with your sign-in.
                    </h1>
                    <p class="mt-4 text-sm max-w-xs leading-relaxed" style="color:#B7D9D3;">
                        Secure access to charts, schedules, and consult notes.
                    </p>

                    <div class="mt-9 rounded-xl px-4 py-3" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.12);">
                        <svg viewBox="0 0 300 60" preserveAspectRatio="none" class="w-full h-14">
                            <path class="ecg-path" d="M0,30 L40,30 L52,30 L60,10 L70,50 L80,30 L95,30 L110,30 L120,18 L128,42 L136,30 L300,30" />
                        </svg>
                        <div class="flex justify-between text-[11px] mt-2" style="font-family:'IBM Plex Mono',monospace; color:#7FB0A8;">
                            <span>SYSTEM STATUS</span><span>SECURE CONNECTION</span>
                        </div>
                    </div>
                </div>

                <div class="text-xs pt-4" style="color:#8FC0B8; border-top:1px solid rgba(255,255,255,0.15);">
                    <strong class="block mb-0.5" style="color:#EAF5F2;">"Uptime matters when someone's on the table."</strong>
                    99.98% portal availability, audited monthly.
                </div>
            </div>

            {{-- RIGHT: slot (your login form goes here, untouched structure) --}}
            <div class="p-8 sm:p-12 flex flex-col justify-center">
                <h2 class="text-2xl" style="font-family:'Fraunces',serif; font-weight:500; color:#0E2624;">
                    Welcome back, Doctor
                </h2>
                <p class="mt-1 text-sm" style="color:#5C7B76;">
                    Sign in with your clinician ID to continue.
                </p>

                <div class="mt-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>