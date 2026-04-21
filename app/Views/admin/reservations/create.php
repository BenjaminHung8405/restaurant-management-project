<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-8">
        <a href="<?php echo url('/admin/reservations'); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-primary-600 transition-colors mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Quay lại danh sách
        </a>
        <h1 class="text-2xl font-bold text-slate-900"><?php echo $title; ?></h1>
        <p class="text-slate-500 text-sm mt-1">Điền đầy đủ thông tin bên dưới để tạo một phiếu đặt bàn mới.</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                <span class="font-bold">Vui lòng kiểm tra lại:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-2">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo url('/admin/reservations/store'); ?>" method="POST" class="space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Name -->
                <div class="space-y-2">
                    <label for="customer_name" class="block text-sm font-bold text-slate-700">Tên khách hàng <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400"></i>
                        <input type="text" id="customer_name" name="customer_name" required
                               value="<?php echo htmlspecialchars($formData['customer_name'] ?? ''); ?>"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none"
                               placeholder="VD: Nguyễn Văn A">
                    </div>
                </div>

                <!-- Customer Phone -->
                <div class="space-y-2">
                    <label for="customer_phone" class="block text-sm font-bold text-slate-700">Số điện thoại <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="phone" class="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400"></i>
                        <input type="tel" id="customer_phone" name="customer_phone" required
                               value="<?php echo htmlspecialchars($formData['customer_phone'] ?? ''); ?>"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none"
                               placeholder="VD: 0987654321">
                    </div>
                </div>

                <!-- Reservation Date -->
                <div class="space-y-2.5">
                    <label for="reservation_date" class="text-sm font-bold text-slate-700 ml-1">Ngày đặt <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                            <i data-lucide="calendar" class="w-5 h-5"></i>
                        </div>
                        <input
                            id="reservation_date"
                            name="reservation_date"
                            type="date"
                            value="<?php echo htmlspecialchars($formData['reservation_date'] ?? date('Y-m-d')); ?>"
                            class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                            required
                        >
                    </div>
                </div>

                <!-- Reservation Time -->
                <div class="space-y-2.5">
                    <label for="reservation_time" class="text-sm font-bold text-slate-700 ml-1">Giờ đặt <span class="text-red-500">*</span></label>
                    <div class="relative group">
                        <button type="button" id="admin_time_toggle" class="w-full flex items-center justify-between rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium text-left cursor-pointer text-slate-700">
                            <span id="admin_time_label"><?php echo htmlspecialchars($formData['reservation_time'] ?? date('H:i')); ?></span>
                            <i data-lucide="chevron-down" id="admin_time_chevron" class="w-5 h-5 text-neutral-400 transition-transform duration-200"></i>
                        </button>
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                            <i data-lucide="clock" class="w-5 h-5"></i>
                        </div>
                        
                        <!-- Custom Dropdown Menu -->
                        <div id="admin_time_dropdown" class="absolute left-0 right-0 z-[90] mt-2 hidden max-h-64 overflow-y-auto rounded-xl border border-neutral-200 bg-white shadow-xl custom-scrollbar">
                            <div id="admin_time_slots_container" class="grid grid-cols-3 gap-2 p-3">
                                <!-- Time slots will be injected here -->
                            </div>
                        </div>
                        <input type="hidden" id="reservation_time" name="reservation_time" value="<?php echo htmlspecialchars($formData['reservation_time'] ?? date('H:i')); ?>" required>
                    </div>
                </div>

                <!-- Party Size -->
                <div class="space-y-2">
                    <label for="party_size" class="block text-sm font-bold text-slate-700">Số lượng khách <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="users" class="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400"></i>
                        <input type="number" id="party_size" name="party_size" min="1" max="50" required
                               value="<?php echo htmlspecialchars($formData['party_size'] ?? '2'); ?>"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none">
                    </div>
                </div>

                <!-- Table Selection -->
                <div class="space-y-2">
                    <label for="table_id" class="block text-sm font-bold text-slate-700">Gán bàn <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="layout-grid" class="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400"></i>
                        <select id="table_id" name="table_id" required
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none appearance-none">
                            <option value="">-- Chọn bàn --</option>
                            <?php foreach ($tables as $table): ?>
                                <option value="<?php echo $table['id']; ?>" 
                                        data-capacity="<?php echo $table['capacity']; ?>"
                                        <?php echo (isset($formData['table_id']) && $formData['table_id'] == $table['id']) ? 'selected' : ''; ?>>
                                    Bàn <?php echo $table['table_number']; ?> (Sức chứa: <?php echo $table['capacity']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2 space-y-2">
                    <label for="notes" class="block text-sm font-bold text-slate-700">Ghi chú thêm</label>
                    <div class="relative">
                        <i data-lucide="message-square" class="absolute left-3 top-3 w-4.5 h-4.5 text-slate-400"></i>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none resize-none"
                                  placeholder="Yêu cầu đặc biệt: Sinh nhật, ghế trẻ em..."><?php echo htmlspecialchars($formData['notes'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="<?php echo url('/admin/reservations'); ?>" class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                Hủy bỏ
            </a>
            <button type="submit" class="px-8 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm shadow-primary-200">
                Lưu phiếu Đặt bàn
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const partySizeInput = document.getElementById('party_size');
    const tableSelect = document.getElementById('table_id');
    const tableOptions = Array.from(tableSelect.options).filter(opt => opt.value !== "");
    let currentBookings = [];

    // Save original text
    tableOptions.forEach(opt => opt.setAttribute('data-original-text', opt.textContent));

    async function fetchBookings() {
        const date = dateInput.value;
        if (!date) return;
        try {
            const res = await fetch(`<?php echo url('/admin/reservations/api/check-availability'); ?>?date=${date}`);
            const data = await res.json();
            if(data.success) {
                currentBookings = data.bookings;
            } else {
                currentBookings = [];
            }
        } catch(e) {
            currentBookings = [];
        }
        updateUI();
    }

    function updateUI() {
        const selectedTime = timeInput.value;
        const selectedSize = parseInt(partySizeInput.value) || 0;

        tableOptions.forEach(option => {
            const tableId = option.value;
            const capacity = parseInt(option.getAttribute('data-capacity'));
            const isBookedAtSelectedTime = currentBookings.some(b => b.table_id === tableId && b.time === selectedTime);

            if (capacity >= selectedSize && !isBookedAtSelectedTime) {
                option.style.display = 'block';
                option.disabled = false;
                option.textContent = option.getAttribute('data-original-text');
            } else {
                option.style.display = 'none';
                option.disabled = true;
                if (isBookedAtSelectedTime) {
                    option.textContent = option.getAttribute('data-original-text') + " (Đã đặt lúc " + selectedTime + ")";
                    option.style.display = 'block'; // Still show it as disabled
                } else {
                    option.textContent = option.getAttribute('data-original-text');
                }
                
                if (option.value === tableSelect.value) {
                    tableSelect.value = ""; // Reset invalid table
                }
            }
        });
        
        if (!timeDropdown.classList.contains('hidden')) {
            updateAdminTimeSlots();
        }
    }

    partySizeInput.addEventListener('input', updateUI);
    tableSelect.addEventListener('change', updateUI);
    
    // Time picker logic
    const timeToggle = document.getElementById('admin_time_toggle');
    const timeLabel = document.getElementById('admin_time_label');
    const timeDropdown = document.getElementById('admin_time_dropdown');
    const timeSlotsContainer = document.getElementById('admin_time_slots_container');
    const timeInput = document.getElementById('reservation_time');
    const dateInput = document.getElementById('reservation_date');
    const timeChevron = document.getElementById('admin_time_chevron');

    const OPENING_HOUR = 10;
    const CLOSING_HOUR = 22;
    const STEP_MINUTES = 30;

    function toggleAdminTimeDropdown(e) {
        if(e) e.preventDefault();
        timeDropdown.classList.toggle('hidden');
        timeChevron.classList.toggle('rotate-180');
        if (!timeDropdown.classList.contains('hidden')) {
            updateAdminTimeSlots();
        }
    }

    function selectAdminTime(timeStr) {
        timeInput.value = timeStr;
        timeLabel.textContent = timeStr;
        timeDropdown.classList.add('hidden');
        timeChevron.classList.remove('rotate-180');
        updateUI();
    }

    function updateAdminTimeSlots() {
        const selectedDateStr = dateInput.value;
        const now = new Date();
        const dd = String(now.getDate()).padStart(2, '0');
        const mm = String(now.getMonth() + 1).padStart(2, '0');
        const yyyy = now.getFullYear();
        const todayStr = `${yyyy}-${mm}-${dd}`;
        
        let startMinutes = OPENING_HOUR * 60;
        const endMinutes = CLOSING_HOUR * 60;

        if (selectedDateStr === todayStr) {
            const currentMinutes = now.getHours() * 60 + now.getMinutes();
            const roundedNow = Math.ceil(currentMinutes / STEP_MINUTES) * STEP_MINUTES;
            startMinutes = Math.max(startMinutes, roundedNow + STEP_MINUTES);
        }

        timeSlotsContainer.innerHTML = '';
        
        if (startMinutes > endMinutes) {
            timeSlotsContainer.innerHTML = '<div class="col-span-3 py-4 text-center text-xs text-neutral-400 italic font-medium">Hết khung giờ khả dụng cho ngày này</div>';
            return;
        }

        for (let min = OPENING_HOUR * 60; min <= endMinutes; min += STEP_MINUTES) {
            const h = Math.floor(min / 60);
            const m = min % 60;
            const timeStr = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
            
            const btn = document.createElement('button');
            btn.type = 'button';
            
            const isBookedForSelectedTable = tableSelect.value !== "" && currentBookings.some(b => b.table_id === tableSelect.value && b.time === timeStr);

            if ((min < startMinutes && selectedDateStr === todayStr) || isBookedForSelectedTable) {
                // Past time today or Booked
                btn.className = 'flex flex-col items-center justify-center rounded-lg border border-neutral-100 py-2 text-sm font-medium text-neutral-300 bg-neutral-50 cursor-not-allowed';
                btn.disabled = true;
                btn.innerHTML = `<span>${timeStr}</span>` + (isBookedForSelectedTable ? `<span class="text-[10px] text-red-400">Đã đặt</span>` : '');
            } else {
                btn.className = 'flex items-center justify-center rounded-lg border border-neutral-100 py-2.5 text-sm font-medium text-neutral-600 transition-all hover:border-primary-200 hover:bg-primary-50 hover:text-primary-600 cursor-pointer';
                if (timeInput.value === timeStr) {
                    btn.className = 'flex items-center justify-center rounded-lg border border-primary-500 py-2.5 text-sm font-bold text-primary-600 bg-primary-50 transition-all cursor-pointer';
                }
                btn.onclick = () => selectAdminTime(timeStr);
                btn.textContent = timeStr;
            }
            
            timeSlotsContainer.appendChild(btn);
        }
    }

    if (timeToggle) {
        timeToggle.addEventListener('click', toggleAdminTimeDropdown);
    }
    
    if (dateInput) {
        dateInput.addEventListener('change', () => {
            fetchBookings();
        });
    }

    // Close dropdown on click outside
    window.addEventListener('mousedown', (e) => {
        if (timeDropdown && !timeDropdown.classList.contains('hidden') && 
            !timeDropdown.contains(e.target) && 
            !timeToggle.contains(e.target)) {
            timeDropdown.classList.add('hidden');
            timeChevron.classList.remove('rotate-180');
        }
    });

    // Initial fetch
    fetchBookings();
});
</script>
