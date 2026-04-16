<div class="mb-6 flex items-center gap-3">
    <a href="/admin/menu" class="p-2 rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-900"><?php echo $title; ?></h1>
        <p class="text-slate-600 text-sm">Cung cấp đầy đủ thông tin về món ăn để hiển thị lên thực đơn.</p>
    </div>
</div>

<?php if (!empty($errors)): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
        <p class="font-bold mb-2">Vui lòng sửa các lỗi sau:</p>
        <ul class="list-disc pl-5">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 md:p-8">
    <form method="POST" action="<?php echo $isEdit ? '/admin/menu/update' : '/admin/menu/store'; ?>" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$item->id, ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>

        <div class="lg:col-span-1 space-y-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Hình ảnh món ăn</label>
                <div class="relative group aspect-square rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 overflow-hidden flex flex-col items-center justify-center transition-all hover:border-teal-500">
                    <?php if (!empty($item->image_url)): ?>
                        <img src="/<?php echo htmlspecialchars((string)$item->image_url, ENT_QUOTES, 'UTF-8'); ?>" id="preview" class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                        <div id="placeholder" class="text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs text-slate-500">Kéo thả hoặc click để tải lên</p>
                        </div>
                        <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                    <?php endif; ?>
                    <input type="file" name="image" id="imageInput" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*">
                </div>
                <p class="text-xs text-slate-500 mt-3 italic">* Định dạng hỗ trợ: JPG, PNG, WEBP (Max 2MB).</p>
            </div>
            
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="grow">
                    <p class="text-sm font-bold text-slate-800">Trạng thái sẵn sàng</p>
                    <p class="text-xs text-slate-500">Món ăn có hiển thị cho khách đặt không.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" class="sr-only peer" <?php echo (!$isEdit || $item->is_available) ? 'checked' : ''; ?>>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                </label>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Tên món ăn <span class="text-red-500">*</span></label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="<?php echo htmlspecialchars((string)($item->name ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:ring-2 focus:ring-teal-500 transition-all font-medium"
                    placeholder="Ví dụ: Phở Bò Tái Lăn"
                    required
                >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                    <select
                        id="category_id"
                        name="category_id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white outline-none focus:ring-2 focus:ring-teal-500 transition-all font-medium"
                        required
                    >
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $category): ?>
                            <option
                                value="<?php echo $category['id']; ?>"
                                <?php echo ($item && $item->category_id == $category['id']) ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars((string)$category['name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-sm font-bold text-slate-700 mb-2">Giá (VND) <span class="text-red-500">*</span></label>
                    <input
                        id="price"
                        name="price"
                        type="number"
                        step="1000"
                        value="<?php echo (float)($item->price ?? 0); ?>"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:ring-2 focus:ring-teal-500 transition-all font-bold text-teal-700"
                        placeholder="0"
                        required
                    >
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Mô tả món ăn</label>
                <textarea
                    id="description"
                    name="description"
                    rows="6"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                    placeholder="Nguyên liệu, cách chế biến, hương vị đặc trưng..."
                ><?php echo htmlspecialchars((string)($item->description ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="/admin/menu" class="px-6 py-3 rounded-xl border border-slate-300 font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-10 py-3 rounded-xl bg-teal-600 text-white font-bold hover:bg-teal-700 transition-colors shadow-lg shadow-teal-100">
                    <?php echo $isEdit ? 'Cập nhật món ăn' : 'Lưu món ăn'; ?>
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
            }
            reader.readAsDataURL(file);
        }
    });
</script>
