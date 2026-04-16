<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Admin Reservations Dashboard</h1>
        <p class="text-slate-600 mt-1">Quản lý đặt bàn và cập nhật trạng thái đơn cho nhân viên.</p>
    </div>
    <div class="text-sm text-slate-500">
        Đăng nhập bởi: <span class="font-medium text-slate-700"><?php echo htmlspecialchars((string) ($_SESSION['user']['username'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
</div>

<?php if (!empty($flashSuccess)): ?>
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-emerald-700">
        <?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">
        <?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
        <p class="text-sm text-slate-500 font-medium">Tổng số lượt đặt</p>
        <p class="text-3xl font-bold text-slate-900 mt-2"><?php echo number_format($stats['total']); ?></p>
    </article>

    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
        <p class="text-sm text-slate-500 font-medium">Đang chờ hôm nay</p>
        <p class="text-3xl font-bold text-amber-600 mt-2"><?php echo number_format($stats['pending_today']); ?></p>
    </article>

    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:shadow-md">
        <p class="text-sm text-slate-500 font-medium">Đã hoàn thành</p>
        <p class="text-3xl font-bold text-emerald-600 mt-2"><?php echo number_format($stats['completed']); ?></p>
    </article>
</div>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
    <div class="px-4 py-4 border-b border-slate-200 bg-slate-50">
        <h2 class="font-semibold text-slate-900">Danh sách đặt bàn</h2>
    </div>

    <?php if (empty($reservations)): ?>
        <div class="p-8 text-center text-slate-600">
            Chưa có reservation nào trong hệ thống.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-slate-700 uppercase tracking-tighter">
                    <tr>
                        <th class="text-left px-4 py-3">ID</th>
                        <th class="text-left px-4 py-3">Khách hàng</th>
                        <th class="text-left px-4 py-3">SĐT</th>
                        <th class="text-left px-4 py-3">Ngày/Giờ</th>
                        <th class="text-left px-4 py-3">Bàn</th>
                        <th class="text-center px-4 py-3">Số người</th>
                        <th class="text-left px-4 py-3">Đơn hàng</th>
                        <th class="text-left px-4 py-3">Thanh toán</th>
                        <th class="text-left px-4 py-3">Trạng thái</th>
                        <th class="text-center px-4 py-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php foreach ($reservations as $row): ?>
                        <?php
                        $reservationId = (string) $row['id'];
                        $status = strtolower(trim((string) $row['status']));
                        
                        $statusConfig = array(
                            'confirmed' => array('label' => 'Confirmed', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200'),
                            'completed' => array('label' => 'Completed', 'class' => 'bg-blue-100 text-blue-700 border-blue-200'),
                            'cancelled' => array('label' => 'Cancelled', 'class' => 'bg-red-100 text-red-700 border-red-200'),
                            'pending'   => array('label' => 'Pending',   'class' => 'bg-amber-100 text-amber-700 border-amber-200'),
                        );
                        
                        $conf = $statusConfig[$status] ?? $statusConfig['pending'];
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 font-mono text-xs text-slate-400">
                                <?php echo htmlspecialchars(substr($reservationId, 0, 8), ENT_QUOTES, 'UTF-8'); ?>...
                            </td>
                            <td class="px-4 py-4 font-medium text-slate-800">
                                <?php echo htmlspecialchars((string) $row['guest_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                <?php echo htmlspecialchars((string) $row['guest_phone'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 whitespace-nowrap">
                                <?php echo date('d/m/Y H:i', strtotime((string) $row['reservation_time'])); ?>
                            </td>
                            <td class="px-4 py-4 text-slate-600 font-medium">
                                <?php echo !empty($row['table_number']) ? htmlspecialchars((string) $row['table_number'], ENT_QUOTES, 'UTF-8') : '-'; ?>
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-slate-700">
                                <?php echo (int) $row['guest_count']; ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php if (!empty($row['order_id'])): ?>
                                    <div class="text-xs font-semibold text-slate-800">#<?php echo htmlspecialchars(substr((string) $row['order_id'], 0, 8), ENT_QUOTES, 'UTF-8'); ?></div>
                                    <div class="text-xs text-slate-500"><?php echo number_format((float) $row['order_total'], 0, ',', '.'); ?> VND</div>
                                <?php else: ?>
                                    <span class="text-slate-400">Không có đơn</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-xs font-medium">
                                <?php if (!empty($row['payment_status'])): ?>
                                    <span class="<?php echo $row['payment_status'] === 'paid' ? 'text-emerald-600' : 'text-amber-600'; ?>">
                                        <?php echo ucfirst((string) $row['payment_status']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full border px-2 py-1 text-xs font-bold <?php echo $conf['class']; ?>">
                                    <?php echo $conf['label']; ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if ($status === 'pending'): ?>
                                        <form method="POST" action="/admin/orders/update-status">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Confirm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($status !== 'completed' && $status !== 'cancelled'): ?>
                                        <form method="POST" action="/admin/orders/update-status">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Complete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($status !== 'cancelled' && $status !== 'completed'): ?>
                                        <form method="POST" action="/admin/orders/update-status">
                                            <input type="hidden" name="reservation_id" value="<?php echo $reservationId; ?>">
                                            <input type="hidden" name="status" value="cancelled">
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Cancel">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
