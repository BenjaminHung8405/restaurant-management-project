<!-- Page Header -->
<div class="mb-8 flex items-center gap-4">
    <a href="<?php echo url('/admin/menu'); ?>" class="p-2.5 rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight"><?php echo $title; ?></h1>
        <p class="text-slate-500 text-sm mt-0.5">Vui lòng cung cấp đầy đủ thông tin để hiển thị món ăn trên thực đơn công khai.</p>
    </div>
</div>

<!-- Error Messages -->
<?php if (!empty($errors)): ?>
    <div class="mb-8 rounded-2xl border border-red-100 bg-red-50 p-5 text-red-700 animate-fade-in">
        <div class="flex items-center gap-3 mb-3">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
            <p class="font-bold">Có lỗi xảy ra trong quá trình xử lý:</p>
        </div>
        <ul class="list-disc pl-8 space-y-1">
            <?php foreach ($errors as $error): ?>
                <li class="text-sm font-medium"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Form Container -->
<div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
    <form method="POST" action="<?php echo $isEdit ? url('/admin/menu/update') : url('/admin/menu/store'); ?>" enctype="multipart/form-data" autocomplete="off" class="grid grid-cols-1 lg:grid-cols-12 gap-0">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)($item->id ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>

        <!-- Left: Image Upload & Status (4 columns) -->
        <div class="lg:col-span-4 p-6 md:p-8 bg-slate-50/50 border-r border-slate-100">
            <div class="space-y-8">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-4 tracking-tight">Hình ảnh món ăn</label>
                    <div class="relative group aspect-square rounded-3xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex flex-col items-center justify-center transition-all hover:border-primary-500 hover:bg-slate-50 cursor-pointer">
                        <?php if (!empty($item->image_url)): ?>
                            <img src="<?php echo url($item->image_url); ?>" id="preview" class="absolute inset-0 w-full h-full object-cover">
                        <?php else: ?>
                            <div id="placeholder" class="text-center p-6">
                                <i data-lucide="image-plus" class="w-10 h-10 text-slate-300 mx-auto mb-3 transition-transform group-hover:scale-110"></i>
                                <p class="text-xs font-bold text-slate-400">TẢI ẢNH LÊN</p>
                                <p class="text-[10px] text-slate-300 mt-1 uppercase tracking-widest">(Click để chọn)</p>
                            </div>
                            <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden shadow-2xl">
                        <?php endif; ?>
                        <input type="file" name="image" id="imageInput" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                    </div>
                </div>
                
                <div class="p-5 bg-white rounded-2xl border border-slate-200 border-dashed">
                    <div class="flex items-center justify-between gap-4">
                        <div class="grow">
                            <p class="text-sm font-bold text-slate-800">Trạng thái sẵn sàng</p>
                            <p class="text-[11px] text-slate-400 font-medium leading-relaxed">Cho phép khách hàng nhìn thấy và đặt món ăn này.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_available" class="sr-only peer" <?php echo (!$isEdit || (isset($item->is_available) && $item->is_available)) ? 'checked' : ''; ?>>
                            <div class="w-12 h-7 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-slate-200 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner overflow-hidden"></div>
                        </label>
                    </div>
                </div>

                <div class="p-5 bg-white rounded-2xl border border-slate-200 border-dashed">
                    <div class="flex items-center justify-between gap-4">
                        <div class="grow">
                            <p class="text-sm font-bold text-slate-800">Món ăn nổi bật</p>
                            <p class="text-[11px] text-slate-400 font-medium leading-relaxed">Hiển thị món ăn ở vị trí ưu tiên hoặc trang chủ.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_featured" class="sr-only peer" <?php echo (isset($item->is_featured) && $item->is_featured) ? 'checked' : ''; ?>>
                            <div class="w-12 h-7 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-5 peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-slate-200 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500 shadow-inner overflow-hidden"></div>
                        </label>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 bg-orange-50/50 rounded-2xl text-orange-700 border border-orange-100">
                    <i data-lucide="info" class="w-4.5 h-4.5 flex-shrink-0 mt-0.5"></i>
                    <p class="text-[11px] leading-relaxed font-medium">
                        Tips: Hình ảnh đẹp, rõ nét sẽ thu hút khách hàng hơn. Khuyên dùng tỉ lệ 1:1, dung lượng < 2MB.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Content Fields (8 columns) -->
        <div class="lg:col-span-8 p-6 md:p-8 space-y-8">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2.5 tracking-tight">Tên món ăn <span class="text-red-500">*</span></label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="<?php echo htmlspecialchars((string)($item->name ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                        class="w-full rounded-2xl border border-slate-200 px-5 py-4 outline-none focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold text-slate-800 placeholder:text-slate-300"
                        placeholder="VD: Phở Bò Tái Lăn Đặc Biệt"
                        autocomplete="off"
                        autocapitalize="off"
                        autocorrect="off"
                        spellcheck="false"
                        required
                    >
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2.5 tracking-tight">Danh mục thực đơn <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select
                            id="category_id"
                            name="category_id"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-4 bg-white outline-none focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold text-slate-800 appearance-none cursor-pointer"
                            required
                        >
                            <option value="">-- Chọn danh mục --</option>
                            <?php if (isset($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option
                                        value="<?php echo $category['id']; ?>"
                                        <?php echo (isset($item) && isset($item->category_id) && $item->category_id == $category['id']) ? 'selected' : ''; ?>
                                    >
                                        <?php echo htmlspecialchars((string)$category['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <i data-lucide="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label for="price" class="block text-sm font-bold text-slate-700 mb-2.5 tracking-tight">Giá niêm yết (VND) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input
                            id="price"
                            name="price"
                            type="number"
                            step="1000"
                            value="<?php echo (float)($item->price ?? 0); ?>"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-4 pr-16 outline-none focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-black text-primary-700 text-lg placeholder:text-slate-200"
                            placeholder="0"
                            autocomplete="off"
                            required
                        >
                        <span class="absolute right-5 top-1/2 -translate-y-1/2 text-[11px] font-black text-slate-400 uppercase tracking-widest border-l border-slate-100 pl-4 py-1">VND</span>
                    </div>
                </div>

                <div>
                    <label for="area" class="block text-sm font-bold text-slate-700 mb-2.5 tracking-tight">Khu vực / Phong cách</label>
                    <div class="relative">
                        <input
                            id="area"
                            name="area"
                            type="text"
                            value="<?php echo htmlspecialchars((string)($item->area ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                            class="w-full rounded-2xl border border-slate-200 px-5 py-4 pl-12 outline-none focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-bold text-slate-800 placeholder:text-slate-300"
                            placeholder="VD: Vietnamese, Thai, French..."
                            autocomplete="off"
                            autocapitalize="off"
                            autocorrect="off"
                            spellcheck="false"
                        >
                        <i data-lucide="map-pin" class="absolute left-5 top-1/2 -translate-y-1/2 w-4.5 h-4.5 text-slate-400 pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 mb-2.5 tracking-tight">Mô tả chi tiết</label>
                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    class="w-full rounded-2xl border border-slate-200 px-5 py-4 outline-none focus:ring-4 focus:ring-primary-100 focus:border-primary-500 transition-all font-medium text-slate-600 placeholder:text-slate-300 leading-relaxed"
                    placeholder="Nguyên liệu chính, hương vị, khẩu phần, lưu ý khi dùng..."
                    autocomplete="off"
                    autocapitalize="off"
                    autocorrect="off"
                    spellcheck="false"
                ><?php echo htmlspecialchars((string)($item->description ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-8 border-t border-slate-100 flex items-center justify-end gap-4">
                <a href="<?php echo url('/admin/menu'); ?>" class="px-8 py-4 rounded-2xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-12 py-4 rounded-2xl bg-primary-600 text-white font-black hover:bg-primary-700 shadow-xl shadow-primary-500/20 transition-all hover:-translate-y-1 active:translate-y-0">
                    <div class="flex items-center gap-2">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        <span><?php echo $isEdit ? 'Cập nhật món' : 'Lưu món mới'; ?></span>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const imageInput = document.getElementById('imageInput');
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
                
                // Add an animation class
                preview.classList.add('animate-in', 'fade-in', 'zoom-in', 'duration-500');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
