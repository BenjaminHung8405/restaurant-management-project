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

                <!-- Reservation Time -->
                <div class="space-y-2">
                    <label for="reservation_time" class="block text-sm font-bold text-slate-700">Thời gian đặt bàn <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400"></i>
                        <input type="datetime-local" id="reservation_time" name="reservation_time" required
                               value="<?php echo htmlspecialchars($formData['reservation_time'] ?? ''); ?>"
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all outline-none cursor-pointer">
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

    function filterTables() {
        const selectedSize = parseInt(partySizeInput.value) || 0;
        let selectedFound = false;

        tableOptions.forEach(option => {
            const capacity = parseInt(option.getAttribute('data-capacity'));
            if (capacity >= selectedSize) {
                option.style.display = 'block';
                option.disabled = false;
                if (option.value === tableSelect.value) selectedFound = true;
            } else {
                option.style.display = 'none';
                option.disabled = true;
                if (option.value === tableSelect.value) {
                    tableSelect.value = ""; // Reset if current selection is no longer valid
                }
            }
        });
    }

    partySizeInput.addEventListener('input', filterTables);
    
    // Initial filter in case of validation back-fill
    filterTables();
});
</script>
