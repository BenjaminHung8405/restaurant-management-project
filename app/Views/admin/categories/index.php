<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight"><?php echo $title; ?></h1>
            <p class="text-slate-500 text-sm mt-1">Quản lý các danh mục thực đơn của nhà hàng.</p>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if ($flashSuccess): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-500"></i>
            <span class="text-sm font-medium"><?php echo $flashSuccess; ?></span>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl flex items-center gap-3 animate-fade-in">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500"></i>
            <span class="text-sm font-medium"><?php echo $flashError; ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Category Form (Left Sidebar) -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h2 id="form-title" class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-primary-500"></i>
                        <span>Thêm Danh mục mới</span>
                    </h2>
                </div>
                
                <form id="category-form" action="<?php echo url('/admin/categories/store'); ?>" method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="id" id="cat-id">
                    
                    <div>
                        <label for="cat-name" class="block text-sm font-semibold text-slate-700 mb-1.5">Tên danh mục <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="cat-name" required
                               placeholder="VD: Hải sản, Đồ uống..."
                               class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-sm">
                    </div>

                    <div>
                        <label for="cat-description" class="block text-sm font-semibold text-slate-700 mb-1.5">Mô tả</label>
                        <textarea name="description" id="cat-description" rows="3"
                                  placeholder="Mô tả ngắn về danh mục này..."
                                  class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-sm resize-none"></textarea>
                    </div>

                    <div>
                        <label for="cat-image" class="block text-sm font-semibold text-slate-700 mb-1.5">URL Hình ảnh</label>
                        <input type="url" name="image_url" id="cat-image"
                               placeholder="https://example.com/image.jpg"
                               class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all outline-none text-sm">
                    </div>

                    <div class="pt-2 flex flex-col gap-2">
                        <button type="submit" id="submit-btn" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-sm shadow-primary-200 flex items-center justify-center gap-2 text-sm">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            <span>Lưu danh mục</span>
                        </button>
                        <button type="button" id="reset-btn" onclick="resetForm()" class="hidden w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 rounded-xl transition-all flex items-center justify-center gap-2 text-sm">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            <span>Hủy bỏ</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help/Info Card -->
            <div class="bg-indigo-50 rounded-2xl p-5 border border-indigo-100">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-indigo-100 rounded-lg">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-indigo-900 text-sm">Mẹo quản lý</h3>
                        <p class="text-indigo-700/80 text-xs mt-1 leading-relaxed">
                            Mỗi danh mục nên có một hình ảnh minh họa để giúp khách hàng dễ dàng nhận biết khi xem Menu.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Table (Right) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-20">Ảnh</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Thông tin danh mục</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-500 italic">
                                        Chưa có danh mục nào được tạo.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categories as $cat): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex-shrink-0">
                                                <?php if ($cat['image_url']): ?>
                                                    <img src="<?php echo htmlspecialchars($cat['image_url']); ?>" alt="" class="w-full h-full object-cover">
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                        <i data-lucide="image" class="w-6 h-6"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900"><?php echo htmlspecialchars($cat['name']); ?></div>
                                            <div class="text-xs text-slate-500 mt-0.5 line-clamp-1"><?php echo htmlspecialchars($cat['description'] ?: 'Không có mô tả'); ?></div>
                                            <div class="text-[10px] text-slate-300 font-mono mt-1 uppercase tracking-tighter"><?php echo $cat['id']; ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button onclick='editCategory(<?php echo json_encode($cat, JSON_HEX_APOS | JSON_HEX_QUOT); ?>)'
                                                        class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-all" title="Sửa">
                                                    <i data-lucide="edit-3" class="w-4.5 h-4.5"></i>
                                                </button>
                                                <form action="<?php echo url('/admin/categories/delete'); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="inline">
                                                    <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Xóa">
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
        </div>
    </div>
</div>

<script>
    const form = document.getElementById('category-form');
    const formTitle = document.getElementById('form-title');
    const submitBtn = document.getElementById('submit-btn');
    const resetBtn = document.getElementById('reset-btn');
    
    const inputId = document.getElementById('cat-id');
    const inputName = document.getElementById('cat-name');
    const inputDesc = document.getElementById('cat-description');
    const inputImage = document.getElementById('cat-image');

    function editCategory(data) {
        // Toggle to Edit Mode
        form.action = '<?php echo url('/admin/categories/update'); ?>';
        formTitle.innerHTML = '<i data-lucide="edit-3" class="w-5 h-5 text-primary-500"></i><span>Cập nhật Danh mục</span>';
        submitBtn.querySelector('span').innerText = 'Cập nhật';
        resetBtn.classList.remove('hidden');
        
        // Populate Data
        inputId.value = data.id;
        inputName.value = data.name;
        inputDesc.value = data.description || '';
        inputImage.value = data.image_url || '';
        
        // Re-init icons
        lucide.createIcons();
        
        // Scroll to form on mobile
        window.scrollTo({ top: 0, behavior: 'smooth' });
        inputName.focus();
    }

    function resetForm() {
        // Reset to Add Mode
        form.action = '<?php echo url('/admin/categories/store'); ?>';
        formTitle.innerHTML = '<i data-lucide="plus-circle" class="w-5 h-5 text-primary-500"></i><span>Thêm Danh mục mới</span>';
        submitBtn.querySelector('span').innerText = 'Lưu danh mục';
        resetBtn.classList.add('hidden');
        
        form.reset();
        inputId.value = '';
        
        // Re-init icons
        lucide.createIcons();
    }
</script>
