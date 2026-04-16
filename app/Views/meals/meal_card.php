<article class="group bg-white border border-neutral-100 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-card-hover hover:-translate-y-2 transition-all duration-500 flex flex-col h-full relative">
    <div class="relative overflow-hidden aspect-[4/3] bg-neutral-100">
        <?php if (!empty($item['image_url'])): ?>
            <img 
                src="<?php echo htmlspecialchars((string) $item['image_url'], ENT_QUOTES, 'UTF-8'); ?>" 
                alt="<?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
            >
        <?php else: ?>
            <div class="w-full h-full flex flex-col items-center justify-center text-neutral-400">
                <i data-lucide="image" class="w-10 h-10 mb-2 opacity-20"></i>
                <span class="text-[10px] uppercase font-bold tracking-widest opacity-40">No Image</span>
            </div>
        <?php endif; ?>
        
        <div class="absolute top-4 left-4">
            <span class="px-3 py-1.5 rounded-full bg-white/90 backdrop-blur-sm shadow-sm text-[11px] font-bold text-primary-600 border border-primary-50/50">
                <?php echo htmlspecialchars((string) $item['category_name'], ENT_QUOTES, 'UTF-8'); ?>
            </span>
        </div>
    </div>

    <div class="p-8 flex flex-col flex-1">
        <div class="flex items-start justify-between gap-4 mb-4">
            <h3 class="font-display font-bold text-xl text-neutral-900 leading-tight group-hover:text-primary-600 transition-colors">
                <?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>
            </h3>
            <?php if ((int) $item['is_available'] === 1): ?>
                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-emerald-500 mt-2 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
            <?php endif; ?>
        </div>

        <p class="text-sm text-neutral-500 line-clamp-2 leading-relaxed flex-1 mb-6">
            <?php echo htmlspecialchars(trim((string) ($item['description'] ?? 'Khám phá hương vị tuyệt vời tại RestoMS.')), ENT_QUOTES, 'UTF-8'); ?>
        </p>

        <div class="flex items-center justify-between pt-6 border-t border-neutral-50">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest mb-0.5">Giá chỉ từ</span>
                <span class="font-display font-black text-primary-600 text-2xl">
                    <?php echo number_format((float) $item['price'], 0, ',', '.'); ?> <span class="text-xs">đ</span>
                </span>
            </div>

            <button 
                type="button"
                onclick="openMealModal(this)"
                data-item="<?php echo htmlspecialchars(json_encode([
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'price' => (float)$item['price'],
                    'description' => $item['description'] ?? 'Khám phá hương vị tuyệt vời tại RestoMS.',
                    'image_url' => $item['image_url'] ?? '',
                    'category_name' => $item['category_name'] ?? ''
                ]), ENT_QUOTES, 'UTF-8'); ?>"
                class="flex items-center justify-center w-12 h-12 rounded-2xl bg-neutral-900 text-white hover:bg-primary-500 transition-all shadow-sm hover:scale-110 active:scale-95"
                title="Thêm vào giỏ hàng"
            >
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            </button>
        </div>
    </div>
</article>
