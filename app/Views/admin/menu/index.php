<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Quản lý Thực đơn</h1>
        <p class="text-slate-500 text-sm mt-1">Quản lý các món ăn, danh mục và cập nhật trạng thái hiển thị.</p>
    </div>
    <a href="<?php echo url('/admin/menu/create'); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold shadow-lg shadow-primary-500/20 transition-all hover:-translate-y-0.5 active:translate-y-0">
        <i data-lucide="plus" class="w-4.5 h-4.5"></i>
        <span>Thêm món mới</span>
    </a>
</div>

<!-- Flash Messages -->
<?php if (!empty($flashSuccess)): ?>
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-emerald-700 animate-fade-in">
        <i data-lucide="check-circle-2" class="w-5 h-5 flex-shrink-0"></i>
        <p class="text-sm font-medium"><?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="mb-6 flex items-center gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-red-700 animate-fade-in">
        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
        <p class="text-sm font-medium"><?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<!-- Menu Table -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-200">
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-tighter text-xs">Món ăn</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-tighter text-xs">Danh mục</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-tighter text-xs text-right">Giá niêm yết</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-tighter text-xs text-center">Trạng thái</th>
                    <th class="px-6 py-4 font-bold text-slate-700 uppercase tracking-tighter text-xs text-right">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <i data-lucide="utensils" class="w-6 h-6"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Chưa có món ăn nào trong thực đơn.</p>
                                <a href="<?php echo url('/admin/menu/create'); ?>" class="text-primary-600 font-bold hover:underline text-xs mt-1">Thêm món ngay</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="relative w-14 h-14 rounded-xl border border-slate-200 bg-slate-100 overflow-hidden flex-shrink-0">
                                        <?php if (!empty($item['image_url'])): ?>
                                            <img src="<?php echo url($item['image_url']); ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-400 font-bold">NO IMG</div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-900 truncate"><?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p class="text-xs text-slate-500 truncate max-w-[200px]"><?php echo htmlspecialchars((string) ($item['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-orange-50 text-orange-600 text-[11px] font-bold uppercase tracking-wider">
                                    <?php echo htmlspecialchars((string) ($item['category_name'] ?? 'Mặc định'), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">
                                <?php echo number_format((float) $item['price'], 0, ',', '.'); ?> <span class="text-[10px] text-slate-400 font-medium">VND</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <?php if ((int)$item['is_available'] === 1): ?>
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span class="text-[11px] font-bold uppercase tracking-tight">Sẵn sàng</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 border border-slate-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            <span class="text-[11px] font-bold uppercase tracking-tight">Ngừng bán</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo url('/admin/menu/edit?id=' . $item['id']); ?>" class="p-2 rounded-xl text-slate-400 hover:text-primary-600 hover:bg-primary-50 transition-all" title="Chỉnh sửa">
                                        <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                    </a>
                                    <form method="POST" action="<?php echo url('/admin/menu/delete'); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa món này không? Hành động này không thể hoàn tác.')" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all" title="Xóa">
                                            <i data-lucide="trash-2" class="w-4.5 h-4.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
