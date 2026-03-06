<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <title>Shopee Affiliate Converter - Ứng Dụng</title>
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
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="bg-gradient-to-br from-[#ee4d2d] to-[#ff7337] p-2 rounded-xl shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20,4h-4V2c0-0.55-0.45-1-1-1h-2c-0.55,0-1,0.45-1,1v2h-4V2c0-0.55-0.45-1-1-1h-2C4.45,1,4,1.45,4,2v2H0v18h24V4H20z M6,3h2v1H6V3z M14,3h2v1h-2V3z M22,20H2V6h20V20z M4,8h11v10H4V8z" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight gradient-text">ShopeeDev</span>
            </a>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Đang chạy</p>
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
            </div>
        </div>
    </nav>

    <!-- Converter Form -->
    <main class="min-h-[80vh] flex flex-col items-center py-12 px-4">
        <div class="w-full max-w-4xl space-y-12">
            <header class="text-center space-y-4">
                <h1 class="text-4xl font-extrabold tracking-tight">Công Cụ <span class="gradient-text">Chuyển Đổi</span></h1>
                <p class="text-gray-500 font-medium">Phiên đăng nhập: {{ Auth::user()->email }}</p>
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
    </main>

    <!-- Footer -->
    <footer class="py-12 border-t border-gray-50 dark:border-gray-900 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm font-bold text-gray-300 dark:text-gray-700 uppercase tracking-widest italic">
                &copy; {{ date('Y') }} Developed by Trường - MMO Power Tools
            </p>
        </div>
    </footer>
</body>

</html>