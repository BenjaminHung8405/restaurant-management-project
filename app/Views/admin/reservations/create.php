<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex text-sm text-slate-500 mb-1" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="<?php echo url('/admin'); ?>" class="hover:text-primary-600 transition-colors">Admin</a></li>
                    <li class="flex items-center space-x-2">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        <span class="text-slate-900 font-medium">Tạo Đặt bàn mới</span>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Tạo Đặt bàn mới</h1>
            <p class="text-slate-500 mt-1">Dành cho khách hàng vãng lai hoặc đặt qua điện thoại.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="<?php echo url('/admin/orders'); ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Hủy & Quay lại
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm animate-shake">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Đã xảy ra lỗi:</h3>
                    <ul class="mt-1 text-sm text-red-700 list-disc list-inside space-y-0.5">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <form action="<?php echo url('/admin/reservations/store'); ?>" method="POST" class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                
                <!-- Left Column: Customer Details -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <i data-lucide="user" class="w-5 h-5 text-primary-500"></i>
                        <h2 class="font-bold text-slate-900">Thông tin khách hàng</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="group">
                            <label for="guest_name" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Tên khách hàng *</label>
                            <input type="text" id="guest_name" name="guest_name" required
                                   value="<?php echo htmlspecialchars($formData['guest_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder:text-slate-400"
                                   placeholder="Nhập họ và tên khách hàng">
                        </div>

                        <div class="group">
                            <label for="guest_phone" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Số điện thoại *</label>
                            <input type="tel" id="guest_phone" name="guest_phone" required
                                   value="<?php echo htmlspecialchars($formData['guest_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder:text-slate-400"
                                   placeholder="Ví dụ: 0912345678">
                        </div>
                    </div>
                </div>

                <!-- Right Column: Reservation Details -->
                <div class="space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                        <i data-lucide="calendar" class="w-5 h-5 text-primary-500"></i>
                        <h2 class="font-bold text-slate-900">Chi tiết đặt bàn</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="group">
                            <label for="reservation_date" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Ngày *</label>
                            <input type="date" id="reservation_date" name="reservation_date" required
                                   min="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo htmlspecialchars($formData['reservation_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="group">
                            <label for="reservation_time" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Giờ *</label>
                            <input type="time" id="reservation_time" name="reservation_time" required
                                   value="<?php echo htmlspecialchars($formData['reservation_time'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="group">
                            <label for="guest_count" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Số khách *</label>
                            <input type="number" id="guest_count" name="guest_count" required min="1"
                                   value="<?php echo htmlspecialchars($formData['guest_count'] ?? '2', ENT_QUOTES, 'UTF-8'); ?>"
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none">
                        </div>
                        <div class="group">
                            <label for="table_id" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Gán Bàn *</label>
                            <select id="table_id" name="table_id" required
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none appearance-none cursor-pointer">
                                <option value="">-- Chọn bàn --</option>
                                <?php foreach ($tables as $table): ?>
                                    <option value="<?php echo $table['id']; ?>" 
                                            data-capacity="<?php echo $table['capacity']; ?>"
                                            <?php echo ($formData['table_id'] ?? '') === $table['id'] ? 'selected' : ''; ?>>
                                        Bàn <?php echo htmlspecialchars($table['table_number'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo $table['capacity']; ?> người)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Full Width: Notes -->
                <div class="md:col-span-2 space-y-4 pt-4">
                    <div class="group">
                        <label for="notes" class="block text-sm font-semibold text-slate-700 mb-1.5 group-focus-within:text-primary-600 transition-colors">Ghi chú (Yêu cầu đặc biệt)</label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all outline-none placeholder:text-slate-400"
                                  placeholder="Vị trí VIP, trang trí sinh nhật, dị ứng thức ăn..."><?php echo htmlspecialchars($formData['notes'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-10 flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="<?php echo url('/admin/orders'); ?>" class="px-6 py-3 text-sm font-bold text-slate-600 hover:text-slate-900 transition-all">Hủy bỏ</a>
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-primary-200">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    Lưu Đặt bàn & Xác nhận
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Initialize icons if not already done
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Dynamic Table Filtering by Capacity
    document.addEventListener('DOMContentLoaded', function() {
        const guestCountInput = document.getElementById('guest_count');
        const tableSelect = document.getElementById('table_id');
        const tableOptions = Array.from(tableSelect.querySelectorAll('option[data-capacity]'));

        function filterTables() {
            const count = parseInt(guestCountInput.value) || 0;
            let availableCount = 0;
            
            tableOptions.forEach(option => {
                const capacity = parseInt(option.dataset.capacity);
                if (capacity < count) {
                    option.disabled = true;
                    option.style.display = 'none';
                    if (option.selected) {
                        tableSelect.value = '';
                    }
                } else {
                    option.disabled = false;
                    option.style.display = '';
                    availableCount++;
                }
            });

            // Handle current selection if it was reset
            if (tableSelect.value === '' && count > 0) {
                // Optionally auto-select the first available table
                // tableSelect.selectedIndex = 0; 
            }
        }

        // Run on load and on change
        guestCountInput.addEventListener('input', filterTables);
        filterTables();
    });
</script>
