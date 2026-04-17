<?php
$pageTitle = isset($title) ? $title : 'RestoMS';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth overflow-x-hidden">
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
    
    <!-- Custom Styles (Ensuring public/ prefix for subdirectory routing) -->
    <link rel="stylesheet" href="<?php echo url('/public/css/style.css'); ?>">
</head>
<body class="selection:bg-primary-100 selection:text-primary-700 overflow-x-hidden">
    
    <!-- Navbar -->
    <header class="sticky top-0 z-50 glass h-16 border-b border-neutral-100 transition-all duration-200">
        <nav class="max-w-7xl mx-auto px-4 sm:px-8 h-full flex items-center justify-between">
            <!-- Logo -->
            <a href="<?php echo url('/'); ?>" class="flex items-center gap-3 font-display font-black text-2xl tracking-tight text-neutral-900 group transition-all duration-300">
                <div class="relative">
                    <div class="bg-primary-500 p-2 rounded-xl text-white shadow-lg shadow-primary-500/30 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                        <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                    </div>
                </div>
                <span class="flex items-center">
                    <span class="text-neutral-900 group-hover:text-primary-600 transition-colors">Resto</span>
                    <span class="text-primary-500">MS</span>
                </span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center gap-1">
                <a href="<?php echo url('/'); ?>" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-white transition-all">Trang chủ</a>
                <a href="<?php echo url('/menu'); ?>" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-white transition-all">Thực đơn</a>
                <button onclick="openReservationModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-neutral-600 hover:text-primary-500 hover:bg-white transition-all cursor-pointer">Đặt bàn</button>
                
                <div class="h-4 w-px bg-neutral-200 mx-2"></div>

                <!-- Desktop Cart -->
                <a href="<?php echo url('/cart'); ?>" class="relative group p-2 mx-1 text-neutral-600 hover:text-primary-500 transition-all" aria-label="Giỏ hàng">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span id="cart-badge-desktop" class="cart-badge hidden">0</span>
                </a>

                <?php if ($isLoggedIn): ?>
                    <a href="<?php echo url('/admin/dashboard'); ?>" class="ml-2 flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-neutral-700 hover:bg-neutral-100 transition-all">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-primary-500"></i> Dashboard
                    </a>
                    <a href="<?php echo url('/logout'); ?>" class="ml-2 px-4 py-1.5 rounded-lg border border-neutral-200 text-xs font-bold text-neutral-500 hover:bg-neutral-50 transition-all">
                        Đăng xuất
                    </a>
                <?php else: ?>
                    <button onclick="openReservationModal()" class="ml-3 px-6 py-2.5 rounded-xl bg-primary-500 text-sm font-bold text-white shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-primary-500/40 hover:-translate-y-0.5 transition-all active:translate-y-0 cursor-pointer">
                        Bắt đầu
                    </button>
                <?php endif; ?>
            </div>

            <!-- Right Actions (Mobile Toggle) -->
            <div class="flex items-center gap-3 md:hidden">
                <a href="<?php echo url('/cart'); ?>" class="relative p-2 text-neutral-700 hover:text-primary-500 transition-all">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                    <span id="cart-badge-mobile" class="cart-badge hidden">0</span>
                </a>
                <button onclick="toggleMobileMenu()" class="p-2 -mr-2 text-neutral-700 cursor-pointer" aria-label="Mở menu">
                    <i id="mobile-menu-icon" data-lucide="menu" class="w-7 h-7"></i>
                </button>
            </div>
        </nav>
        
    </header>

    <!-- Mobile Sidebar Drawer (Defensive Tailwind classes for fallback hiding) -->
    <div id="drawer-overlay" class="drawer-overlay fixed inset-0 z-[55] bg-slate-900/40 backdrop-blur-sm opacity-0 invisible transition-all duration-300" onclick="toggleMobileMenu()"></div>
    <div id="mobile-drawer" class="mobile-drawer fixed top-0 right-0 z-[60] w-[280px] h-full bg-white shadow-2xl translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex flex-col h-full">
            <!-- Drawer Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-neutral-100">
                <span class="font-display font-bold text-lg text-neutral-900">Điều hướng</span>
                <button onclick="toggleMobileMenu()" class="p-2 text-neutral-400 hover:text-neutral-900 transition-colors">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <!-- Drawer Links -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
                <a href="<?php echo url('/'); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-neutral-600 hover:text-primary-50 hover:bg-primary-50 rounded-xl transition-all">
                    <i data-lucide="home" class="w-5 h-5"></i> Trang chủ
                </a>
                <a href="<?php echo url('/menu'); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-neutral-600 hover:text-primary-50 hover:bg-primary-50 rounded-xl transition-all">
                    <i data-lucide="book-open" class="w-5 h-5"></i> Thực đơn
                </a>
                <button onclick="openReservationModal(); toggleMobileMenu();" class="w-full flex items-center gap-3 px-4 py-3 text-base font-medium text-neutral-600 hover:text-primary-50 hover:bg-primary-50 rounded-xl transition-all cursor-pointer">
                    <i data-lucide="calendar" class="w-5 h-5"></i> Đặt bàn
                </button>
                
                <div class="pt-4 mt-4 border-t border-neutral-100">
                    <?php if ($isLoggedIn): ?>
                        <div class="px-4 py-2 text-xs font-semibold text-neutral-400 uppercase tracking-wider">Tài khoản</div>
                        <a href="<?php echo url('/admin/dashboard'); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-neutral-700 hover:bg-neutral-50 rounded-xl">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Dashboard
                        </a>
                        <a href="<?php echo url('/logout'); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-red-600 hover:bg-red-50 rounded-xl">
                            <i data-lucide="log-out" class="w-5 h-5"></i> Đăng xuất
                        </a>
                    <?php else: ?>
                        <a href="<?php echo url('/login'); ?>" class="flex items-center gap-3 px-4 py-3 text-base font-medium text-neutral-700 hover:bg-neutral-50 rounded-xl">
                            <i data-lucide="log-in" class="w-5 h-5"></i> Đăng nhập
                        </a>
                        <button onclick="openReservationModal(); toggleMobileMenu();" class="mt-4 w-full py-4 rounded-xl bg-primary-500 text-white font-bold shadow-lg shadow-primary-500/20 active:scale-[0.98] transition-all">
                            Bắt đầu ngay
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Drawer Footer -->
            <div class="p-6 border-t border-neutral-100 bg-neutral-50/50">
                <div class="flex items-center gap-3 mb-4">
                    <div class="bg-primary-100 p-2 rounded-lg text-primary-600">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                    </div>
                    <div class="text-sm">
                        <div class="text-neutral-400">Hỗ trợ 24/7</div>
                        <div class="font-bold text-neutral-900">0123-456-789</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
            if (window.scrollY > 8) {
                header.classList.add('shadow-sm', 'border-neutral-200');
                header.classList.remove('border-neutral-100');
            } else {
                header.classList.remove('shadow-sm', 'border-neutral-200');
                header.classList.add('border-neutral-100');
            }
        });

        // Mobile Menu Logic (Drawer)
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('drawer-overlay');
            const body = document.body;
            
            const isOpen = drawer.classList.contains('open');
            
            if (!isOpen) {
                drawer.classList.add('open');
                overlay.classList.add('open');
                body.style.overflow = 'hidden';
            } else {
                drawer.classList.remove('open');
                overlay.classList.remove('open');
                body.style.overflow = '';
            }
        }

        // Cart Badge Logic
        async function updateCartBadge() {
            try {
                const response = await fetch('<?php echo url('/cart/status'); ?>', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                
                const badges = [
                    document.getElementById('cart-badge-desktop'),
                    document.getElementById('cart-badge-mobile')
                ];
                
                badges.forEach(badge => {
                    if (badge) {
                        if (data.cartCount > 0) {
                            badge.textContent = data.cartCount > 99 ? '99+' : data.cartCount;
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                });
            } catch (err) {
                console.error('Failed to update cart badge:', err);
            }
        }

        // Initialize things on load
        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadge();
            // Lucide icons are already initialized via the script in <head> usually,
            // but let's ensure it runs for dynamically changed content if needed.
            if (window.lucide) {
                lucide.createIcons();
            }
        });

        // Reservation Modal Logic
        const resModal = document.getElementById('reservation-modal');
        const resContent = document.getElementById('reservation-modal-content');
        const resForm = document.getElementById('reservation-form');
        const resSuccessState = document.getElementById('reservation-success-state');
        const dateInput = document.getElementById('res_date_display');
        const hiddenDateInput = document.getElementById('res_reservation_date');
        const timeSelect = document.getElementById('res_reservation_time');
        const timeToggle = document.getElementById('res_reservation_time_toggle');
        const timeDropdown = document.getElementById('res_time_dropdown');
        const timeLabel = document.getElementById('res_selected_time_label');
        const timeChevron = document.getElementById('res_time_chevron');
        const timeSlotsContainer = document.getElementById('res_time_slots_container');
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

            // Reset custom dropdown
            timeLabel.textContent = 'Chọn giờ đặt bàn';
            timeLabel.classList.remove('text-neutral-900', 'font-semibold');
            timeLabel.classList.add('text-neutral-400');
            timeSelect.value = '';
            closeTimeDropdown();
            
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

        function toggleTimeDropdown() {
            const isHidden = timeDropdown.classList.contains('hidden');
            if (isHidden) {
                openTimeDropdown();
            } else {
                closeTimeDropdown();
            }
        }

        function openTimeDropdown() {
            timeDropdown.classList.remove('hidden');
            timeChevron.classList.add('rotate-180');
            timeToggle.classList.add('ring-2', 'ring-orange-500', 'border-orange-500');
        }

        function closeTimeDropdown() {
            timeDropdown.classList.add('hidden');
            timeChevron.classList.remove('rotate-180');
            timeToggle.classList.remove('ring-2', 'ring-orange-500', 'border-orange-500');
        }

        function selectTimeSlot(time) {
            timeSelect.value = time;
            timeLabel.textContent = time;
            timeLabel.classList.add('text-neutral-900', 'font-semibold');
            timeLabel.classList.remove('text-neutral-400');
            closeTimeDropdown();
            clearResErrors();
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

            timeSlotsContainer.innerHTML = '';
            
            if (startMinutes > endMinutes) {
                timeSlotsContainer.innerHTML = '<div class="col-span-3 py-4 text-center text-xs text-neutral-400 italic font-medium">Hết khung giờ khả dụng cho hôm nay</div>';
                return;
            }

            for (let min = startMinutes; min <= endMinutes; min += STEP_MINUTES) {
                const h = Math.floor(min / 60);
                const m = min % 60;
                const timeStr = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'flex items-center justify-center rounded-md border border-neutral-100 py-2.5 text-sm font-medium text-neutral-600 transition-all hover:border-orange-200 hover:bg-orange-50 hover:text-orange-600 cursor-pointer';
                btn.textContent = timeStr;
                btn.onclick = () => selectTimeSlot(timeStr);
                
                timeSlotsContainer.appendChild(btn);
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
            if (e.key === 'Escape') {
                if (!resModal.classList.contains('hidden')) {
                    closeReservationModal();
                }
                if (!timeDropdown.classList.contains('hidden')) {
                    closeTimeDropdown();
                }
            }
        });

        // Click outside to close dropdown
        window.addEventListener('mousedown', (e) => {
            if (!timeDropdown.classList.contains('hidden') && 
                !timeDropdown.contains(e.target) && 
                !timeToggle.contains(e.target)) {
                closeTimeDropdown();
            }
        });
    </script>
</body>
</html>
