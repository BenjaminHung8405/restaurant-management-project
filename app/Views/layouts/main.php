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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700;800;900&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    
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
                        roboto: ['Roboto', 'sans-serif'],
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
                <button onclick="openReservationModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 transition-all cursor-pointer">Đặt bàn</button>
                
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
                    <button onclick="openReservationModal()" class="ml-2 px-6 py-2 rounded-xl bg-primary-500 text-sm font-bold text-white shadow-lg shadow-primary-500/20 hover:bg-primary-600 hover:-translate-y-0.5 transition-all active:translate-y-0 text-center min-w-[130px] cursor-pointer">
                        Bắt đầu
                    </button>
                <?php endif; ?>
            </div>

            <!-- Right Actions (Mobile) -->
            <div class="flex items-center gap-2 md:hidden">
                <a href="<?php echo url('/cart'); ?>" class="p-2 text-neutral-600 hover:text-primary-500 transition-colors">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </a>
                <button onclick="toggleMobileMenu()" class="p-2 text-neutral-600 cursor-pointer">
                    <i id="mobile-menu-icon" data-lucide="menu" class="w-6 h-6 transition-transform duration-200"></i>
                </button>
            </div>
        </nav>
        
        <!-- Mobile Navigation Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-neutral-100 bg-white/95 backdrop-blur-md animate-slide-down">
            <div class="px-4 py-6 space-y-4">
                <a href="<?php echo url('/'); ?>" class="block px-4 py-2 text-base font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 rounded-lg">Trang chủ</a>
                <a href="<?php echo url('/menu'); ?>" class="block px-4 py-2 text-base font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 rounded-lg">Thực đơn</a>
                <button onclick="openReservationModal(); toggleMobileMenu();" class="w-full text-left px-4 py-2 text-base font-medium text-neutral-600 hover:text-primary-500 hover:bg-primary-50 rounded-lg">Đặt bàn</button>
                
                <div class="pt-4 border-t border-neutral-100">
                    <?php if ($isLoggedIn): ?>
                        <a href="<?php echo url('/admin/dashboard'); ?>" class="block px-4 py-2 text-base font-medium text-neutral-700">Dashboard</a>
                        <a href="<?php echo url('/logout'); ?>" class="block px-4 py-2 text-base font-medium text-red-600">Đăng xuất</a>
                    <?php else: ?>
                        <a href="<?php echo url('/login'); ?>" class="block px-4 py-2 text-base font-medium text-neutral-700">Đăng nhập</a>
                        <button onclick="openReservationModal(); toggleMobileMenu();" class="mt-2 w-full px-6 py-3 rounded-xl bg-primary-500 text-white font-bold shadow-lg shadow-primary-500/20">Bắt đầu ngay</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
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
                        <li><button onclick="openReservationModal()" class="text-sm hover:text-primary-400 transition-colors inline-flex items-center gap-2 cursor-pointer"><span>›</span> Đặt bàn</button></li>
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

    <?php include_once VIEW_PATH . '/partials/reservation_modal.php'; ?>

    <script>
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

        // Mobile Menu Logic
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('mobile-menu-icon');
            const isHidden = menu.classList.contains('hidden');
            
            if (isHidden) {
                menu.classList.remove('hidden');
                lucide.createIcons({
                    name: 'x',
                    attrs: { class: 'w-6 h-6' },
                    element: icon
                });
            } else {
                menu.classList.add('hidden');
                lucide.createIcons({
                    name: 'menu',
                    attrs: { class: 'w-6 h-6' },
                    element: icon
                });
            }
        }

        // Reservation Modal Logic
        const resModal = document.getElementById('reservation-modal');
        const resContent = document.getElementById('reservation-modal-content');
        const resForm = document.getElementById('reservation-form');
        const resSuccessState = document.getElementById('reservation-success-state');
        const dateInput = document.getElementById('res_date_display');
        const hiddenDateInput = document.getElementById('res_reservation_date');
        const timeSelect = document.getElementById('res_reservation_time');
        const notesTextArea = document.getElementById('res_notes');
        const notesCount = document.getElementById('res_notes_count');

        const OPENING_HOUR = 10;
        const CLOSING_HOUR = 22;
        const STEP_MINUTES = 30;

        function openReservationModal() {
            resModal.classList.remove('hidden');
            resModal.classList.add('flex');
            
            // Reset state
            resForm.classList.remove('hidden');
            resSuccessState.classList.add('hidden');
            resForm.reset();
            clearResErrors();
            
            // Set default date to today
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            
            dateInput.value = `${dd}/${mm}/${yyyy}`;
            hiddenDateInput.value = `${yyyy}-${mm}-${dd}`;
            
            updateTimeSlots();

            setTimeout(() => {
                resContent.classList.remove('translate-y-4', 'opacity-0');
                document.body.style.overflow = 'hidden';
            }, 10);
        }

        function closeReservationModal() {
            resContent.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => {
                resModal.classList.add('hidden');
                resModal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        function clearResErrors() {
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.textContent = '';
                el.classList.add('hidden');
            });
            document.getElementById('res_global_error').classList.add('hidden');
        }

        function updateTimeSlots() {
            const selectedDateStr = hiddenDateInput.value;
            const now = new Date();
            const todayStr = now.toISOString().split('T')[0];
            
            let startMinutes = OPENING_HOUR * 60;
            const endMinutes = CLOSING_HOUR * 60;

            if (selectedDateStr === todayStr) {
                const currentMinutes = now.getHours() * 60 + now.getMinutes();
                const roundedNow = Math.ceil(currentMinutes / STEP_MINUTES) * STEP_MINUTES;
                startMinutes = Math.max(startMinutes, roundedNow + STEP_MINUTES);
            }

            timeSelect.innerHTML = '<option value="">Chọn giờ đặt bàn</option>';
            
            if (startMinutes > endMinutes) {
                const opt = document.createElement('option');
                opt.disabled = true;
                opt.textContent = 'Hết khung giờ khả dụng cho hôm nay';
                timeSelect.appendChild(opt);
                return;
            }

            for (let min = startMinutes; min <= endMinutes; min += STEP_MINUTES) {
                const h = Math.floor(min / 60);
                const m = min % 60;
                const timeStr = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                const opt = document.createElement('option');
                opt.value = timeStr;
                opt.textContent = timeStr;
                timeSelect.appendChild(opt);
            }
        }

        // Date input formatting logic
        dateInput.onblur = function() {
            const val = this.value.trim();
            const match = val.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
            if (match) {
                const d = match[1], m = match[2], y = match[3];
                const isoDate = `${y}-${m}-${d}`;
                const testDate = new Date(isoDate);
                if (!isNaN(testDate.getTime())) {
                    hiddenDateInput.value = isoDate;
                    updateTimeSlots();
                    return;
                }
            }
            // If invalid, revert to today
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            this.value = `${dd}/${mm}/${yyyy}`;
            hiddenDateInput.value = `${yyyy}-${mm}-${dd}`;
            updateTimeSlots();
        };

        notesTextArea.oninput = function() {
            notesCount.textContent = `${this.value.length}/300`;
        };

        async function handleReservationSubmit(e) {
            e.preventDefault();
            clearResErrors();

            const btn = document.getElementById('res_submit_btn');
            const btnText = document.getElementById('res_btn_text');
            const btnSpinner = document.getElementById('res_btn_spinner');

            // Simple validation
            let hasError = false;
            const name = document.getElementById('res_guest_name').value.trim();
            const phone = document.getElementById('res_guest_phone').value.trim();
            const time = timeSelect.value;

            if (name.length < 2) {
                showError('res_guest_name', 'Tên quá ngắn.');
                hasError = true;
            }
            if (!/^(0\d{9,10}|84\d{9,10})$/.test(phone)) {
                showError('res_guest_phone', 'Số điện thoại không hợp lệ.');
                hasError = true;
            }
            if (!time) {
                showError('res_reservation_time', 'Vui lòng chọn giờ.');
                hasError = true;
            }

            if (hasError) return;

            // Loading state
            btn.disabled = true;
            btnText.textContent = 'Đang gửi...';
            btnSpinner.classList.remove('hidden');

            try {
                const formData = new FormData(resForm);
                const data = Object.fromEntries(formData.entries());

                const response = await fetch('<?php echo url('/api/reservation'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    resForm.classList.add('hidden');
                    resSuccessState.classList.remove('hidden');
                    // Refresh cart status if needed or just show success
                } else {
                    document.getElementById('res_global_error').textContent = result.error || 'Có lỗi xảy ra.';
                    document.getElementById('res_global_error').classList.remove('hidden');
                }
            } catch (err) {
                document.getElementById('res_global_error').textContent = 'Lỗi kết nối server.';
                document.getElementById('res_global_error').classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btnText.textContent = 'Xác nhận đặt bàn';
                btnSpinner.classList.add('hidden');
            }
        }

        function showError(fieldId, msg) {
            const errEl = document.getElementById(fieldId + '_error');
            if (errEl) {
                errEl.textContent = msg;
                errEl.classList.remove('hidden');
            }
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !resModal.classList.contains('hidden')) {
                closeReservationModal();
            }
        });
    </script>
</body>
</html>
