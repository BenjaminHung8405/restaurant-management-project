<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang | Premium Bistro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Outfit:wght@800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1 { font-family: 'Outfit', sans-serif; }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 selection:bg-primary-100 selection:text-primary-700">
    <div class="max-w-xl w-full text-center">
        <div class="relative mb-12 animate-float">
            <div class="text-[180px] font-black text-slate-200/50 leading-none select-none">404</div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-32 h-32 bg-primary-600 rounded-3xl rotate-12 flex items-center justify-center text-white shadow-2xl shadow-primary-200">
                    <i data-lucide="utensils-crosses" class="w-16 h-16"></i>
                </div>
            </div>
        </div>

        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Trang này đã "hết món"!</h1>
        <p class="text-slate-500 text-lg mb-10 leading-relaxed">
            Rất tiếc, đường dẫn bạn đang tìm kiếm không tồn tại hoặc đã được di chuyển. Hãy quay lại trang chủ để khám phá món khác nhé.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/" class="flex items-center gap-2 px-8 py-4 bg-primary-600 text-white font-bold rounded-2xl hover:bg-primary-700 hover:scale-105 active:scale-95 transition-all shadow-xl shadow-primary-200">
                <i data-lucide="home" class="w-5 h-5"></i>
                Quay về trang chủ
            </a>
            <a href="/menu" class="flex items-center gap-2 px-8 py-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all">
                <i data-lucide="menu" class="w-5 h-5"></i>
                Xem thực đơn
            </a>
        </div>

        <div class="mt-20 pt-8 border-t border-slate-200/60">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Premium Bistro Management System</p>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
