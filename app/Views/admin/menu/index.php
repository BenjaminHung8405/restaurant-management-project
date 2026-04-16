<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900">Quản lý thực đơn</h1>
        <p class="text-slate-600 mt-1">Thêm, sửa hoặc xóa các món ăn trong thực đơn của nhà hàng.</p>
    </div>
    <a href="/admin/menu/create" class="inline-flex items-center gap-2 rounded-xl bg-teal-600 text-white px-5 py-2.5 font-bold hover:bg-teal-700 transition-colors shadow-lg shadow-teal-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Thêm món mới
    </a>
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

<section class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <?php if (empty($items)): ?>
        <div class="p-12 text-center text-slate-500">
            Không có món ăn nào trong thực đơn. Hãy thêm món mới để bắt đầu.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase tracking-tighter">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Hình ảnh</th>
                        <th class="px-6 py-4 font-semibold">Tên món</th>
                        <th class="px-6 py-4 font-semibold">Danh mục</th>
                        <th class="px-6 py-4 font-semibold text-right">Giá</th>
                        <th class="px-6 py-4 font-semibold text-center">Trạng thái</th>
                        <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img src="/<?php echo htmlspecialchars((string) $item['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="" class="w-16 h-12 rounded-lg object-cover border border-slate-200">
                                <?php else: ?>
                                    <div class="w-16 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-[10px] text-slate-400">No Image</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800"><?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                <p class="text-xs text-slate-500 truncate max-w-[200px]"><?php echo htmlspecialchars((string) ($item['description'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <?php echo htmlspecialchars((string) ($item['category_name'] ?? 'Chưa rỏ'), ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-teal-700">
                                <?php echo number_format((float) $item['price'], 0, ',', '.'); ?> VND
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ((int)$item['is_available'] === 1): ?>
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 px-2.5 py-0.5 text-xs font-bold">
                                        Hiển thị
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center rounded-full bg-slate-100 text-slate-500 px-2.5 py-0.5 text-xs font-bold">
                                        Ẩn
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="/admin/menu/edit?id=<?php echo $item['id']; ?>" class="p-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form method="POST" action="/admin/menu/delete" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
