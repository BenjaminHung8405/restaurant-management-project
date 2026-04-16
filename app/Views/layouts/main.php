<?php
$pageTitle = isset($title) ? $title : 'RestoMS';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | RestoMS — Nhà Hàng Long Xuyên</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                            950: '#431407',
                        },
                        neutral: {
                            50:  "#f8fafc",
                            100: "#f1f5f9",
                            200: "#e2e8f0",
                            300: "#cbd5e1",
                            400: "#94a3b8",
                            500: "#64748b",
                            600: "#475569",
                            700: "#334155",
                            800: "#1e293b",
                            900: "#0f172a",
                            950: "#020617",
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'card': '0 2px 8px 0 rgb(0 0 0 / 0.08)',
                        'card-hover': '0 8px 24px 0 rgb(0 0 0 / 0.12)',
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body { @apply bg-neutral-50 text-neutral-800 antialiased; }
            h1, h2, h3, h4, h5, h6 { @apply font-display text-neutral-900; }
        }
        @layer components {
            .glass { @apply bg-white/80 backdrop-blur-md; }
        }
    </style>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="selection:bg-primary-100 selection:text-primary-700 overflow-x-hidden">
    
    <!-- Navbar -->
    <header class="sticky top-0 z-50 glass h-16 border-b border-neutral-100 transition-all duration-200">
        <nav class="max-w-7xl mx-auto px-4 sm:px-8 h-full flex items-center justify-between">
            <!-- Logo -->
            <a href="<?php echo url('/'); ?>" class="flex items-center gap-2.5 font-display font-black text-2xl tracking-tight text-neutral-900 hover:text-primary-500 transition-colors">
                <div class="bg-primary-500 p-2 rounded-xl text-white shadow-sm">
                    <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                </div>
                <span>Resto<span class="text-primary-500">MS</span></span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1">
                <a href="<?php echo url('/'); ?>" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 transition-all">Trang chủ</a>
                <a href="<?php echo url('/menu'); ?>" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 transition-all">Thực đơn</a>
                <a href="<?php echo url('/reservation'); ?>" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 transition-all">Đặt bàn</a>
                
                <div class="h-4 w-px bg-neutral-200 mx-2"></div>

                <?php if ($isLoggedIn): ?>
                    <a href="<?php echo url('/admin/dashboard'); ?>" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 hover:bg-primary-50 transition-all">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                    </a>
                    <a href="<?php echo url('/logout'); ?>" class="ml-2 px-4 py-2 rounded-lg bg-neutral-100 text-sm font-bold text-neutral-700 hover:bg-neutral-200 transition-all">
                        Đăng xuất
                    </a>
                <?php else: ?>
                    <a href="<?php echo url('/login'); ?>" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-700 hover:text-primary-500 transition-all">Đăng nhập</a>
                    <a href="<?php echo url('/login'); ?>" class="ml-2 px-6 py-2 rounded-xl bg-primary-500 text-sm font-bold text-white shadow-lg shadow-primary-500/20 hover:bg-primary-600 hover:-translate-y-0.5 transition-all active:translate-y-0 text-center min-w-[130px]">
                        Bắt đầu
                    </a>
                <?php endif; ?>
            </div>

            <!-- Right Actions (Mobile) -->
            <div class="flex items-center gap-2 md:hidden">
                <a href="<?php echo url('/cart'); ?>" class="p-2 text-neutral-600 hover:text-primary-500 transition-colors">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </a>
                <button class="p-2 text-neutral-600">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </nav>
    </header>

    <!-- Main Content Area -->
    <main id="main-content" class="min-h-[80vh]">
        <div class="animate-fade-in">
            <?php echo $content; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-neutral-950 text-white/80" aria-label="Site footer">
        <div class="max-w-7xl mx-auto px-4 sm:px-8 pt-16 pb-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12 lg:gap-16">
                <!-- Column 1: Brand -->
                <div class="flex flex-col gap-6">
                    <a href="<?php echo url('/'); ?>" class="inline-flex items-center gap-2.5 font-display font-bold text-xl text-white hover:text-primary-400 transition-colors">
                        <i data-lucide="utensils-crossed" class="w-5 h-5 text-primary-400"></i>
                        <span><span class="text-primary-400">Resto</span>MS</span>
                    </a>
                    <p class="text-sm leading-relaxed text-neutral-400 max-w-xs">
                        Mang đến những bữa ăn ngon và trải nghiệm ẩm thực tuyệt vời ngay tại bàn của bạn. Đặt món trực tuyến, giữ chỗ và thưởng thức từng miếng ăn.
                    </p>
                    <div class="flex items-center gap-3">
                        <a href="#" class="p-2 rounded-lg text-neutral-500 hover:text-primary-400 hover:bg-white/5 transition-all">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="p-2 rounded-lg text-neutral-500 hover:text-primary-400 hover:bg-white/5 transition-all">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="p-2 rounded-lg text-neutral-500 hover:text-primary-400 hover:bg-white/5 transition-all">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="flex flex-col gap-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white/40">Liên kết nhanh</h3>
                    <ul class="flex flex-col gap-3">
                        <li><a href="<?php echo url('/menu'); ?>" class="text-sm hover:text-primary-400 transition-colors inline-flex items-center gap-2"><span>›</span> Thực đơn</a></li>
                        <li><a href="<?php echo url('/reservation'); ?>" class="text-sm hover:text-primary-400 transition-colors inline-flex items-center gap-2"><span>›</span> Đặt bàn</a></li>
                        <li><a href="#" class="text-sm hover:text-primary-400 transition-colors inline-flex items-center gap-2"><span>›</span> Giới thiệu</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact Info -->
                <div class="flex flex-col gap-6">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-white/40">Liên hệ</h3>
                    <ul class="flex flex-col gap-4">
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 mt-1 text-primary-400"></i>
                            <span class="text-sm text-neutral-400">123 Đường Hương Vị, TP. Long Xuyên, An Giang</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-primary-400"></i>
                            <a href="tel:0123456789" class="text-sm text-neutral-400 hover:text-primary-400 transition-colors">0123-456-789</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-primary-400"></i>
                            <a href="mailto:hello@restoms.com" class="text-sm text-neutral-400 hover:text-primary-400 transition-colors">hello@restoms.com</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-6">
                <p class="text-center text-xs text-neutral-500">
                    &copy; <?php echo date('Y'); ?> <span class="text-neutral-300 font-medium">RestoMS</span>. Bảo lưu mọi quyền. Build with ❤️ for Gastronomy.
                </p>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Scroll shadow for header
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 10) {
                header.classList.add('shadow-sm', 'border-neutral-200');
                header.classList.remove('border-neutral-100');
            } else {
                header.classList.remove('shadow-sm', 'border-neutral-200');
                header.classList.add('border-neutral-100');
            }
        });
    </script>
</body>
</html>
