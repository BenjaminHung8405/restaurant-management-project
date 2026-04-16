<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Quản lý Đặt bàn & Đơn hàng</h1>
        <p class="text-slate-500 text-sm mt-1">Theo dõi các lượt đặt bàn, đơn hàng đi kèm và cập nhật trạng thái phục vụ.</p>
    </div>
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm">
        <i data-lucide="user-check" class="w-4 h-4 text-emerald-500"></i>
        <span>Active: <?php echo htmlspecialchars((string) ($_SESSION['user']['username'] ?? 'Admin'), ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
</div>

<!-- Flash Messages -->
<?php if (!empty($flashSuccess)): ?>
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3.5 text-emerald-700 animate-fade-in shadow-sm">
        <i data-lucide="check-circle-2" class="w-5 h-5 flex-shrink-0"></i>
        <p class="text-sm font-bold"><?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-100 bg-red-50 px-4 py-3.5 text-red-700 animate-fade-in shadow-sm">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
        <p class="text-sm font-bold"><?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="group bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Toàn thời gian</span>
        </div>
        <p class="text-sm font-bold text-slate-500">Tổng số lượt đặt</p>
        <p class="text-3xl font-black text-slate-900 mt-1"><?php echo number_format($stats['total']); ?></p>
    </div>

    <div class="group bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-2xl bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <span class="text-[10px] font-black text-amber-200 uppercase tracking-widest italic animate-pulse">Live</span>
        </div>
        <p class="text-sm font-bold text-slate-500">Đang chờ hôm nay</p>
        <p class="text-3xl font-black text-amber-600 mt-1"><?php echo number_format($stats['pending_today']); ?></p>
    </div>

    <div class="group bg-white p-6 rounded-3xl border border-slate-200 shadow-sm hover:shadow-md transition-all hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 rounded-2xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i data-lucide="check-square" class="w-6 h-6"></i>
            </div>
            <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Hoàn tất</span>
        </div>
        <p class="text-sm font-bold text-slate-500">Thành công</p>
        <p class="text-3xl font-black text-emerald-600 mt-1"><?php echo number_format($stats['completed']); ?></p>
    </div>
</div>

<!-- Reservations List -->
<section class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-12">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <h2 class="font-bold text-slate-800 flex items-center gap-2">
            <i data-lucide="list" class="w-5 h-5 text-primary-500"></i>
            Danh sách đặt bàn
        </h2>
        <div class="flex gap-2">
            <button class="p-2 rounded-xl border border-slate-200 bg-white text-slate-400 hover:text-slate-600 transition-colors">
                <i data-lucide="filter" class="w-4 h-4"></i>
            </button>
            <button class="p-2 rounded-xl border border-slate-200 bg-white text-slate-400 hover:text-slate-600 transition-colors">
                <i data-lucide="download" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <?php if (empty($reservations)): ?>
        <div class="p-16 text-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="calendar-x" class="w-8 h-8 text-slate-300"></i>
            </div>
            <p class="text-slate-500 font-bold italic">Chưa có dữ liệu đặt bàn nào được ghi nhận.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-slate-50/30 text-slate-400 border-b border-slate-100">
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Mã đơn</th>
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Khách hàng</th>
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Thời gian</th>
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Vị trí & Quy mô</th>
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px]">Đơn hàng</th>
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-center">Trạng thái</th>
                        <th class="px-6 py-4 font-black uppercase tracking-widest text-[10px] text-right">Quản lý</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($reservations as $row): ?>
                        <?php
                        $reservationId = (string) $row['id'];
                        $status = strtolower(trim((string) $row['status']));
                        
                        $statusConfig = array(
                            'confirmed' => array('label' => 'Đã xác nhận', 'icon' => 'check-circle',   'class' => 'bg-emerald-50 text-emerald-600 border-emerald-100'),
                            'completed' => array('label' => 'Hoàn thành', 'icon' => 'check-square',   'class' => 'bg-blue-50 text-blue-600 border-blue-100'),
                            'cancelled' => array('label' => 'Đã hủy',     'icon' => 'x-circle',       'class' => 'bg-red-50 text-red-600 border-red-100'),
                            'pending'   => array('label' => 'Đang chờ',   'icon' => 'clock',          'class' => 'bg-amber-50 text-amber-600 border-amber-100'),
                        );
                        
                        $conf = $statusConfig[$status] ?? $statusConfig['pending'];
                        ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-5">
                                <span class="font-mono text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">
                                    #<?php echo htmlspecialchars(substr($reservationId, 0, 8), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900"><?php echo htmlspecialchars((string) $row['guest_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span class="text-[11px] text-slate-400 font-medium flex items-center gap-1 mt-0.5">
                                        <i data-lucide="phone" class="w-3 h-3"></i>
                                        <?php echo htmlspecialchars((string) $row['guest_phone'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col whitespace-nowrap">
                                    <span class="text-xs font-bold text-slate-700"><?php echo date('H:i', strtotime((string) $row['reservation_time'])); ?></span>
                                    <span class="text-[11px] text-slate-400"><?php echo date('d/m/Y', strtotime((string) $row['reservation_time'])); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="px-3 py-1 bg-slate-100 rounded-lg text-slate-700 font-black text-xs">
                                        <?php echo !empty($row['table_number']) ? htmlspecialchars((string) $row['table_number'], ENT_QUOTES, 'UTF-8') : 'TBA'; ?>
                                    </div>
                                    <div class="flex items-center gap-1 text-slate-400 text-xs font-bold">
                                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                        <?php echo (int) $row['guest_count']; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <?php if (!empty($row['order_id'])): ?>
                                    <div class="flex flex-col">
                                        <div class="text-[11px] font-black text-slate-800">GIỎ HÀNG #<?php echo htmlspecialchars(substr((string) $row['order_id'], 0, 6), ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="text-xs font-bold text-primary-600"><?php echo number_format((float) $row['order_total'], 0, ',', '.'); ?> VND</div>
                                        <div class="text-[10px] mt-0.5 <?php echo $row['payment_status'] === 'paid' ? 'text-emerald-500 font-bold uppercase' : 'text-slate-400'; ?>">
                                            <?php echo $row['payment_status'] === 'paid' ? '● Đã thanh toán' : '○ Chờ thanh toán'; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Chỉ đặt chỗ</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-center">
                                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full border <?php echo $conf['class']; ?>">
                                        <i data-lucide="<?php echo $conf['icon']; ?>" class="w-3.5 h-3.5"></i>
                                        <span class="text-[11px] font-black uppercase tracking-tight"><?php echo $conf['label']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <?php if ($status === 'pending'): ?>
                                        <form method="POST" action="<?php echo url('/admin/orders/update-status'); ?>">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Xác nhận">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($status !== 'completed' && $status !== 'cancelled'): ?>
                                        <form method="POST" action="<?php echo url('/admin/orders/update-status'); ?>">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white transition-all shadow-sm" title="Hoàn thành">
                                                <i data-lucide="flag" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($status !== 'cancelled' && $status !== 'completed'): ?>
                                        <form method="POST" action="<?php echo url('/admin/orders/update-status'); ?>">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hủy bỏ">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <button class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-900 hover:text-white transition-all shadow-sm" title="Chi tiết">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
