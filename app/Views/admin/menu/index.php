<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight font-roboto">Quản lý Thực đơn</h1>
        <p class="text-slate-500 text-sm mt-1">Cập nhật danh sách món ăn, danh mục và quản lý tình trạng kinh doanh.</p>
    </div>
    <a href="<?php echo url('/admin/menu/create'); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold shadow-xl shadow-primary-500/25 transition-all hover:-translate-y-1 active:translate-y-0 group">
        <i data-lucide="plus-circle" class="w-5 h-5 transition-transform group-hover:rotate-90"></i>
        <span>Thêm Món Mới</span>
    </a>
</div>

<!-- Flash Messages -->
<?php if (!empty($flashSuccess)): ?>
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/50 backdrop-blur-sm px-5 py-4 text-emerald-700 animate-fade-in shadow-sm">
        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
            <i data-lucide="check" class="w-5 h-5"></i>
        </div>
        <p class="text-sm font-semibold"><?php echo htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($flashError)): ?>
    <div class="mb-6 flex items-center gap-3 rounded-2xl border border-red-100 bg-red-50/50 backdrop-blur-sm px-5 py-4 text-red-700 animate-fade-in shadow-sm">
        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </div>
        <p class="text-sm font-semibold"><?php echo htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
<?php endif; ?>

<!-- Filter & Search Bar -->
<div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm mb-8">
    <form method="GET" action="<?php echo url('/admin/menu'); ?>" class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <!-- Search Input -->
        <div class="md:col-span-4 relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary-500 transition-colors">
                <i data-lucide="search" class="w-4.5 h-4.5"></i>
            </div>
            <input type="text" name="search" value="<?php echo htmlspecialchars($filters['search'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Tìm kiếm tên món..." class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all placeholder:text-slate-400">
        </div>

        <!-- Category Dropdown -->
        <div class="md:col-span-3">
            <select name="category_id" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all appearance-none cursor-pointer">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($filters['category_id'] ?? '') === $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars((string)$cat['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Status Dropdown -->
        <div class="md:col-span-3">
            <select name="status" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-medium focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all appearance-none cursor-pointer">
                <option value="">Trạng thái kinh doanh</option>
                <option value="available" <?php echo ($filters['status'] ?? '') === 'available' ? 'selected' : ''; ?>>Đang bán</option>
                <option value="out_of_stock" <?php echo ($filters['status'] ?? '') === 'out_of_stock' ? 'selected' : ''; ?>>Hết hàng</option>
            </select>
        </div>

        <!-- Filter Action Button -->
        <div class="md:col-span-2">
            <button type="submit" class="w-full h-full flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-bold text-sm transition-all active:scale-[0.98]">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>Lọc</span>
            </button>
        </div>
    </form>
</div>

<!-- Modern Data Table -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/80 border-b border-slate-100 italic">
                    <th class="px-8 py-5 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Hình ảnh</th>
                    <th class="px-6 py-5 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Tên món ăn</th>
                    <th class="px-6 py-5 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Danh mục</th>
                    <th class="px-6 py-5 font-bold text-slate-500 uppercase tracking-widest text-[10px]">Giá bán</th>
                    <th class="px-6 py-5 font-bold text-slate-500 uppercase tracking-widest text-[10px] text-center">Trạng thái</th>
                    <th class="px-8 py-5 font-bold text-slate-500 uppercase tracking-widest text-[10px] text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <i data-lucide="search-x" class="w-10 h-10"></i>
                                </div>
                                <div class="space-y-1">
                                    <h3 class="text-lg font-bold text-slate-900">Không tìm thấy món ăn nào</h3>
                                    <p class="text-slate-500 text-sm">Hãy thử thay đổi từ khóa tìm kiếm hoặc bộ lọc.</p>
                                </div>
                                <a href="<?php echo url('/admin/menu'); ?>" class="text-primary-600 font-bold hover:text-primary-700 transition-colors text-sm">Xóa tất cả bộ lọc</a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-slate-50/50 transition-all group">
                            <!-- Image Thumbnail -->
                            <td class="px-8 py-4">
                                <div class="relative w-12 h-12 rounded-xl border border-slate-200 bg-slate-100 overflow-hidden flex-shrink-0 shadow-sm group-hover:shadow-md transition-shadow">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img src="<?php echo url((string)$item['image_url']); ?>" alt="" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-[10px] text-slate-400 font-bold">NO IMG</div>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Name -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900 text-base group-hover:text-primary-600 transition-colors">
                                        <?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <?php if (!empty($item['description'])): ?>
                                        <span class="text-xs text-slate-400 truncate max-w-[200px] mt-0.5"><?php echo htmlspecialchars((string) $item['description'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                                    <?php echo htmlspecialchars((string) ($item['category_name'] ?? 'Mặc định'), ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>

                            <!-- Price -->
                            <td class="px-6 py-4">
                                <span class="font-extrabold text-slate-950 font-roboto text-base">
                                    <?php echo number_format((float) $item['price'], 0, ',', '.'); ?>
                                    <span class="text-[10px] text-slate-400 font-normal uppercase ml-0.5">đ</span>
                                </span>
                            </td>

                            <!-- Status Badges -->
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap items-center justify-center gap-2">
                                    <?php if ((int)$item['is_available'] === 1): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[11px] font-bold uppercase tracking-tight border border-emerald-100 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Đang bán
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-600 text-[11px] font-bold uppercase tracking-tight border border-rose-100 shadow-sm">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Hết hàng
                                        </span>
                                    <?php endif; ?>

                                    <?php if (isset($item['is_featured']) && (int)$item['is_featured'] === 1): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold uppercase border border-amber-100 shadow-sm" title="Món nổi bật">
                                            <i data-lucide="star" class="w-3 h-3 fill-amber-500 text-amber-500"></i>
                                            HOT
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-8 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                                    <a href="<?php echo url('/admin/menu/edit?id=' . $item['id']); ?>" class="p-2.5 rounded-xl bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition-all shadow-sm" title="Chỉnh sửa">
                                        <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                    </a>
                                    <form method="POST" action="<?php echo url('/admin/menu/delete'); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa món này? Hành động này không thể hoàn tác.');" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="p-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all shadow-sm" title="Xóa">
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
