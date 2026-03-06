<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <!-- SEO Meta Tags -->
    <title>Shopee Affiliate Converter - Chuyển đổi Link Shopee Nhanh Chóng</title>
    <meta name="description" content="Công cụ chuyển đổi Shopee Affiliate Link chuyên nghiệp, hỗ trợ tối đa 5 SubID, chuẩn MMO. Đăng nhập Google để nhận 3 lượt convert miễn phí.">
    <meta name="keywords" content="shopee affiliate, shopee converter, chuyển đổi link shopee, affiliate links, mmo tool">
    <meta name="author" content="Trường">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Shopee Affiliate Converter - Tool MMO Chuyên Nghiệp">
    <meta property="og:description" content="Tối ưu hóa chiến dịch Shopee Affiliate của bạn với công cụ convert link siêu tốc, hỗ trợ SubID và GraphQL API.">
    <meta property="og:image" content="{{ asset('images/shopee-hero.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="Shopee Affiliate Converter - Tool MMO Chuyên Nghiệp">
    <meta property="twitter:description" content="Tối ưu hóa chiến dịch Shopee Affiliate của bạn với công cụ convert link siêu tốc, hỗ trợ SubID và GraphQL API.">
    <meta property="twitter:image" content="{{ asset('images/shopee-hero.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(90deg, #ee4d2d, #ff7337);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .shopee-shadow {
            box-shadow: 0 20px 50px rgba(238, 77, 45, 0.15);
        }
    </style>
</head>

<body class="bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 antialiased">
    <!-- Header -->
    <nav class="sticky top-0 z-50 bg-white/80 dark:bg-black/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-[#ee4d2d] to-[#ff7337] p-2 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20,4h-4V2c0-0.55-0.45-1-1-1h-2c-0.55,0-1,0.45-1,1v2h-4V2c0-0.55-0.45-1-1-1h-2C4.45,1,4,1.45,4,2v2H0v18h24V4H20z M6,3h2v1H6V3z M14,3h2v1h-2V3z M22,20H2V6h20V20z M4,8h11v10H4V8z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight gradient-text">ShopeeDev</span>
            </div>

            <div class="flex items-center gap-6">
                @auth
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Đang đăng nhập</p>
                        <p class="text-sm font-bold">{{ Auth::user()->name }}</p>
                    </div>
                    <img src="{{ Auth::user()->avatar }}" class="w-10 h-10 rounded-full border-2 border-[#ee4d2d]/20 p-0.5 shadow-sm">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 013 3H6a3 3 0 013-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
                @else
                <a href="{{ route('login.google') }}" class="flex items-center gap-3 bg-white border border-gray-200 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm hover:shadow-md transition-all active:scale-95">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                    </svg>
                    Login Google
                </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section / Content -->
    <main class="min-h-[80vh] flex flex-col items-center py-12 px-4">
        @auth
        <div class="w-full max-w-4xl space-y-12">
            <header class="text-center space-y-4">
                <h1 class="text-4xl font-extrabold tracking-tight">Công Cụ <span class="gradient-text">Chuyển Đổi</span></h1>
                <p class="text-gray-500 font-medium">Đã đăng nhập: {{ Auth::user()->email }}</p>
            </header>

            <livewire:shopee-converter />

            <!-- Contact Card -->
            <div class="max-w-xl mx-auto bg-gradient-to-r from-gray-900 to-black p-8 rounded-3xl text-white shadow-2xl relative overflow-hidden group">
                <div class="absolute -right-12 -top-12 w-40 h-40 bg-[#ee4d2d] rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">
                    <div class="bg-white/10 p-4 rounded-2xl backdrop-blur-md">
                        <svg class="w-10 h-10 text-[#ee4d2d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <div class="text-center sm:text-left">
                        <h3 class="text-xl font-bold">Nâng cấp bản PRO không giới hạn?</h3>
                        <p class="text-gray-400 text-sm mt-1">Liên hệ chính chủ để mua bản đầy đủ chức năng.</p>
                        <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-4">
                            <a href="tel:0327182537" class="bg-[#ee4d2d] px-6 py-2 rounded-full font-bold text-sm shadow-lg hover:shadow-orange-500/20 transition-all">
                                0327.182.537 (Trường)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Landing for Guest -->
        <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="space-y-10 order-2 lg:order-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-50 dark:bg-orange-950/30 rounded-full border border-orange-100 dark:border-orange-900">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#ee4d2d] opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-[#ee4d2d]"></span>
                    </span>
                    <span class="text-[10px] font-bold text-[#ee4d2d] uppercase tracking-wider">Internal API V3.0 Enabled</span>
                </div>

                <h1 class="text-6xl sm:text-8xl font-black leading-tight tracking-tighter">
                    Shopee <br>
                    <span class="gradient-text">Affiliate</span> <br>
                    Converter.
                </h1>

                <p class="text-xl text-gray-500 dark:text-gray-400 font-medium leading-relaxed max-w-lg">
                    Tối ưu hóa thu nhập thụ động với công cụ rút gọn link Shopee chuyên nghiệp.
                    Tự động xử lý Cookie, SubID và API nội bộ.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login.google') }}" class="flex justify-center items-center gap-3 bg-[#ee4d2d] text-white px-10 py-5 rounded-2xl font-extrabold text-xl shadow-2xl hover:bg-[#d73211] transform hover:-translate-y-1 transition-all shopee-shadow">
                        START NOW FREE
                        <svg class="w-6 h-6 border-2 border-white/20 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <div class="pt-8 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Mua tool / Support 24/7</p>
                    <div class="flex items-center gap-2 text-xl font-extrabold text-gray-900 dark:text-white">
                        <span class="text-[#ee4d2d]">Hotline:</span> 0327.182.537 (Trường)
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2 relative">
                <div class="absolute -inset-4 bg-gradient-to-br from-[#ee4d2d] to-[#ff7337] rounded-[3rem] blur-2xl opacity-10 animate-pulse"></div>
                <img src="{{ asset('images/shopee-hero.png') }}" alt="Shopee Affiliate Illustration" class="relative rounded-[2.5rem] shadow-2xl border border-white/10 w-full animate-fade-in transition-all hover:scale-[1.02] duration-500">
            </div>
        </div>
        @endauth
    </main>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-50 dark:border-gray-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-bold text-gray-300 dark:text-gray-700 uppercase tracking-widest italic">
                &copy; 2026 Developed by Trường - MMO Power Tools
            </p>
        </div>
    </footer>
</body>

</html>