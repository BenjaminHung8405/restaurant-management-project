<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900"><?php echo $title; ?></h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý dánh sách khách hàng đặt bàn trước tại nhà hàng.</p>
        </div>
        <a href="<?php echo url('/admin/reservations/create'); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-all shadow-sm shadow-primary-200">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tạo Đặt bàn
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
            <span class="text-sm font-medium"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
            <span class="text-sm font-medium"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Bàn</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Số khách</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($reservations)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <i data-lucide="calendar-x" class="w-6 h-6 text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500 font-medium">Chưa có lượt đặt bàn nào cho hôm nay và sắp tới.</p>
                                    <a href="<?php echo url('/admin/reservations/create'); ?>" class="mt-2 text-primary-600 font-semibold text-sm hover:underline">Tạo đặt bàn ngay</a>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reservations as $r): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($r['guest_name']); ?></span>
                                        <span class="text-xs text-slate-500"><?php echo htmlspecialchars($r['guest_phone']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                        Bàn <?php echo htmlspecialchars($r['table_number'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-slate-700 font-medium">
                                            <?php echo date('H:i', strtotime($r['reservation_time'])); ?>
                                        </span>
                                        <span class="text-[11px] text-slate-500">
                                            <?php echo date('d/m/Y', strtotime($r['reservation_time'])); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-700"><?php echo $r['guest_count']; ?> khách</span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusClasses = [
                                        'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'confirmed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                                        'cancelled' => 'bg-red-100 text-red-700 border-red-200'
                                    ];
                                    $statusLabels = [
                                        'pending'   => 'Đang chờ',
                                        'confirmed' => 'Đã xác nhận',
                                        'completed' => 'Đã đến',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                    $class = $statusClasses[$r['status']] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                    $label = $statusLabels[$r['status']] ?? $r['status'];
                                    ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border <?php echo $class; ?>">
                                        <?php echo $label; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <?php if ($r['status'] === 'pending'): ?>
                                            <form action="<?php echo url('/admin/reservations/update-status'); ?>" method="POST" class="flex items-center gap-2">
                                                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="status" value="confirmed">
                                                <?php if (empty($r['table_id'])): ?>
                                                    <select name="table_id" required class="text-xs border-slate-200 rounded-lg py-1 px-2 focus:ring-primary-500 focus:border-primary-500 w-28 bg-white" title="Chọn bàn cho khách">
                                                        <option value="">Chọn bàn...</option>
                                                        <?php foreach ($tables as $t): ?>
                                                            <?php
                                                                $isBooked = false;
                                                                foreach ($reservations as $other) {
                                                                    if ($other['id'] !== $r['id'] && 
                                                                        !empty($other['table_id']) && 
                                                                        $other['table_id'] == $t['id'] && 
                                                                        in_array($other['status'], ['pending', 'confirmed']) && 
                                                                        date('Y-m-d H:i', strtotime($other['reservation_time'])) === date('Y-m-d H:i', strtotime($r['reservation_time']))) {
                                                                        $isBooked = true;
                                                                        break;
                                                                    }
                                                                }
                                                            ?>
                                                            <option value="<?php echo $t['id']; ?>" <?php echo $isBooked ? 'disabled style="display:none;"' : ''; ?>>
                                                                Bàn <?php echo htmlspecialchars($t['table_number']); ?> (<?php echo $t['capacity']; ?> ng)
                                                            </option>
                                                            <?php if ($isBooked): ?>
                                                                <option disabled>Bàn <?php echo htmlspecialchars($t['table_number']); ?> (Đã đặt)</option>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php endif; ?>
                                                <button type="submit" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Xác nhận">
                                                    <i data-lucide="check" class="w-4.5 h-4.5"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo url('/admin/reservations/update-status'); ?>" method="POST" class="inline">
                                                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Từ chối/Hủy">
                                                    <i data-lucide="x" class="w-4.5 h-4.5"></i>
                                                </button>
                                            </form>
                                        <?php elseif ($r['status'] === 'confirmed'): ?>
                                            <form action="<?php echo url('/admin/reservations/update-status'); ?>" method="POST" class="inline">
                                                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition-all shadow-sm" title="Khách đã đến">
                                                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                                                    Check-in
                                                </button>
                                            </form>
                                            <form action="<?php echo url('/admin/reservations/update-status'); ?>" method="POST" class="inline">
                                                <input type="hidden" name="id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hủy đặt bàn">
                                                    <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-xs text-slate-400 font-medium italic">Không có thao tác</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
