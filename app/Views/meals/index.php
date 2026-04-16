<?php
$featuredItems = array_filter($menuItems, function($item) {
    return isset($item['is_featured']) && (int)$item['is_featured'] === 1;
});

// Group items by category if we are in "All" view
$groupedItems = [];
if ($categoryId === '') {
    foreach ($categories as $cat) {
        $catItems = array_filter($menuItems, function($item) use ($cat) {
            return (string)$item['category_id'] === (string)$cat['id'];
        });
        if (!empty($catItems)) {
            $groupedItems[] = [
                'category' => $cat,
                'items' => $catItems
            ];
        }
    }
}
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-orange-50 to-white pt-16 pb-12 sm:pt-20 sm:pb-16 overflow-hidden">
    <!-- Decorative element -->
    <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-96 h-96 bg-primary-100/50 rounded-full blur-3xl opacity-50"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="mb-10">
            <div class="flex items-center gap-4 mb-6 animate-fade-in">
                <div class="p-3 bg-primary-500 rounded-2xl text-white shadow-lg shadow-primary-500/20">
                    <i data-lucide="utensils-crossed" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-4xl sm:text-5xl font-display font-black text-neutral-900 tracking-tight leading-tight">
                        Thực Đơn Nhà Hàng
                    </h1>
                    <div class="h-1.5 w-24 bg-primary-500 rounded-full mt-2"></div>
                </div>
            </div>
            <p class="text-lg sm:text-xl text-neutral-600 max-w-2xl leading-relaxed">
                Khám phá bộ sưu tập các món ăn ngon lành được chuẩn bị tươi mới hàng ngày từ bếp của chúng tôi. Mỗi món ăn được chế biến cẩn thận với tình yêu.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8">
            <div class="flex gap-4 p-4 rounded-2xl bg-white/50 border border-white backdrop-blur-sm">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="star" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="font-bold text-neutral-900">Tươi & Chất Lượng</p>
                    <p class="text-sm text-neutral-500">Nguyên liệu tốt nhất</p>
                </div>
            </div>
            <div class="flex gap-4 p-4 rounded-2xl bg-white/50 border border-white backdrop-blur-sm">
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="chef-hat" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="font-bold text-neutral-900">Bếp Trưởng Gợi Ý</p>
                    <p class="text-sm text-neutral-500">Yêu thích nhất</p>
                </div>
            </div>
            <div class="flex gap-4 p-4 rounded-2xl bg-white/50 border border-white backdrop-blur-sm">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="font-bold text-neutral-900">Giá Công Khai</p>
                    <p class="text-sm text-neutral-500">Thông tin rõ ràng</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Bar Section -->
<section class="bg-white border-b border-neutral-100 z-10">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <form action="<?php echo url('/menu'); ?>" method="GET" class="relative max-w-2xl mx-auto">
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400">
                <i data-lucide="search" class="w-5 h-5"></i>
            </div>
            <input 
                type="text" 
                name="search"
                value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Tìm kiếm món ăn bạn yêu thích..." 
                class="w-full pl-13 pr-32 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all text-lg font-medium"
            >
            <?php if ($categoryId): ?>
                <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($categoryId, ENT_QUOTES, 'UTF-8'); ?>">
            <?php endif; ?>
            <button type="submit" class="absolute right-2.5 top-2.5 px-6 py-2 bg-neutral-900 text-white rounded-xl font-bold hover:bg-neutral-800 transition-all">
                Tìm
            </button>
        </form>
    </div>
</section>

<!-- Sticky Category Filter -->
<div class="sticky top-16 z-30 bg-white/95 backdrop-blur-md border-b border-neutral-100 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center gap-3 overflow-x-auto pb-1 scrollbar-hide no-scrollbar">
            <a 
                href="<?php echo url('/menu' . ($search ? '?search='.urlencode($search) : '')); ?>" 
                class="px-6 py-2.5 rounded-full font-bold whitespace-nowrap transition-all <?php echo $categoryId === '' ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20 hover:bg-primary-600' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'; ?>"
            >
                Tất Cả
            </a>
            <?php foreach ($categories as $cat): ?>
                <a 
                    href="<?php echo url('/menu?category_id=' . $cat['id'] . ($search ? '&search='.urlencode($search) : '')); ?>" 
                    class="px-6 py-2.5 rounded-full font-bold whitespace-nowrap transition-all <?php echo (string)$categoryId === (string)$cat['id'] ? 'bg-primary-500 text-white shadow-lg shadow-primary-500/20 hover:bg-primary-600' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'; ?>"
                >
                    <?php echo htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- Featured Carousel (Only if search is empty or matches featured) -->
    <?php if (!empty($featuredItems)): ?>
        <section class="mb-20">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-100 text-orange-600 rounded-xl">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl font-display font-bold text-neutral-900">Bếp Trưởng Gợi Ý</h2>
                </div>
                <div class="flex gap-2">
                    <button id="featured-prev" class="p-3 rounded-full bg-white border border-neutral-200 text-neutral-400 hover:text-primary-500 hover:border-primary-500 transition-all shadow-sm active:scale-90">
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button id="featured-next" class="p-3 rounded-full bg-white border border-neutral-200 text-neutral-400 hover:text-primary-500 hover:border-primary-500 transition-all shadow-sm active:scale-90">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div id="featured-scroll" class="flex gap-6 overflow-x-auto pb-4 scroll-smooth no-scrollbar" style="scroll-snap-type: x mandatory;">
                <?php foreach ($featuredItems as $item): ?>
                    <div class="w-[300px] sm:w-[350px] flex-shrink-0 scroll-snap-align-start group">
                        <div class="bg-white rounded-[2.5rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 flex flex-col h-full relative">
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-4 py-1.5 rounded-full bg-primary-500 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary-500/20">
                                    Nổi bật
                                </span>
                            </div>
                            <div class="relative aspect-[4/3] overflow-hidden bg-neutral-200">
                                <?php if (!empty($item['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($item['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-neutral-400">
                                        <i data-lucide="utensils" class="w-12 h-12 opacity-30"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-8 flex flex-col flex-1">
                                <h3 class="text-2xl font-roboto font-bold text-neutral-900 mb-2 truncate group-hover:text-primary-600 transition-colors"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p class="font-roboto text-sm text-neutral-500 mb-6 line-clamp-2 leading-relaxed flex-1">
                                    <?php echo htmlspecialchars($item['description'] ?: 'Khám phá hương vị tuyệt vời tại RestoMS.', ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                                <div class="flex items-center justify-between pt-6 border-t border-neutral-50">
                                    <span class="text-2xl font-roboto font-black text-primary-600">
                                        <?php echo number_format($item['price'], 0, ',', '.'); ?> <span class="text-sm font-bold opacity-70">đ</span>
                                    </span>
                                    <button 
                                        type="button"
                                        onclick="openMealModal(this)"
                                        data-item="<?php echo htmlspecialchars(json_encode([
                                            'id' => $item['id'],
                                            'name' => $item['name'],
                                            'price' => (float)$item['price'],
                                            'description' => $item['description'] ?? 'Khám phá hương vị tuyệt vời tại RestoMS.',
                                            'image_url' => $item['image_url'] ?? '',
                                            'category_name' => 'Nổi bật'
                                        ]), ENT_QUOTES, 'UTF-8'); ?>"
                                        class="p-3 bg-neutral-900 text-white rounded-2xl hover:bg-primary-500 transition-all shadow-sm hover:scale-110 active:scale-95"
                                    >
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Main Menu Grid -->
    <section>
        <?php if ($categoryId === '' && !empty($groupedItems)): ?>
            <!-- Grouped View -->
            <?php foreach ($groupedItems as $group): ?>
                <div class="mb-16">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-display font-bold text-neutral-900 whitespace-nowrap"><?php echo htmlspecialchars($group['category']['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                        <div class="h-px bg-neutral-100 flex-1"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                        <?php foreach ($group['items'] as $item): ?>
                            <?php include 'meal_card.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php elseif (!empty($menuItems)): ?>
            <!-- Single Category View -->
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-3xl font-display font-bold text-neutral-900">
                    <?php 
                        $currentCat = array_filter($categories, function($c) use ($categoryId) {
                            return (string)$c['id'] === (string)$categoryId;
                        });
                        $catName = !empty($currentCat) ? reset($currentCat)['name'] : 'Tất Cả Món Ăn';
                        echo htmlspecialchars($catName, ENT_QUOTES, 'UTF-8');
                    ?>
                </h2>
                <span class="text-sm font-bold text-neutral-400"><?php echo count($menuItems); ?> món ăn</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($menuItems as $item): ?>
                    <?php include 'meal_card.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="py-20 text-center">
                <div class="w-24 h-24 bg-neutral-100 rounded-3xl flex items-center justify-center mx-auto mb-6 text-neutral-300">
                    <i data-lucide="search-x" class="w-12 h-12"></i>
                </div>
                <h3 class="text-2xl font-display font-bold text-neutral-800 mb-2">Không tìm thấy món ăn</h3>
                <p class="text-neutral-500 max-w-sm mx-auto mb-8">Thử thay đổi từ khóa hoặc bộ lọc danh mục bạn nhé.</p>
                <a href="<?php echo url('/menu'); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-primary-500 text-white rounded-2xl font-bold hover:bg-primary-600 transition-all shadow-lg shadow-primary-500/20">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Xem lại toàn bộ
                </a>
            </div>
        <?php endif; ?>
    </section>
</div>

<!-- CTA Section -->
<section class="bg-primary-500 py-20 overflow-hidden relative">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-2xl"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-neutral-900/10 rounded-full translate-y-1/2 -translate-x-1/2 blur-2xl"></div>
    
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="text-4xl sm:text-5xl font-display font-black text-white mb-6">Sẵn sàng trải nghiệm vị giác tuyệt đỉnh?</h2>
        <p class="text-xl text-primary-50 mb-10 leading-relaxed font-medium">
            Khám phá thực đơn đặc sắc và đặt món yêu thích của bạn ngay hôm nay. Chúng tôi luôn sẵn sàng phục vụ bạn tốt nhất.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="<?php echo url('/reservation'); ?>" class="w-full sm:w-auto px-10 py-5 bg-white text-primary-600 rounded-2xl font-black text-lg hover:bg-primary-50 transition-all shadow-xl shadow-white/10 hover:-translate-y-1">
                Đặt Bàn Ngay
            </a>
            <a href="#search" class="w-full sm:w-auto px-10 py-5 bg-neutral-900 text-white rounded-2xl font-black text-lg hover:bg-neutral-800 transition-all shadow-xl shadow-neutral-900/20 hover:-translate-y-1">
                Tìm Món Ăn
            </a>
        </div>
    </div>
</section>

<!-- Floating Cart Bar Container -->
<div id="floating-cart-container" class="transition-all duration-500">
    <?php if ($cartCount > 0): ?>
        <?php include 'floating_cart_bar.php'; ?>
    <?php endif; ?>
</div>

<!-- Popup Đặt Món -->
<div id="meal-modal" class="fixed inset-0 z-[100] hidden items-center justify-center">
    <div class="fixed inset-0 bg-neutral-900/60 backdrop-blur-md opacity-0 transition-opacity duration-300 pointer-events-none modal-backdrop z-10" onclick="closeMealModal()"></div>
    
    <!-- Modal Container -->
    <div class="absolute inset-0 flex items-center justify-center p-4 z-20 pointer-events-none">
        <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row modal-content relative z-30 transition-all duration-300 transform scale-95 opacity-0 pointer-events-auto">
            
            <!-- Left: Image Section -->
            <div id="modal-image-container" class="relative w-full h-48 md:h-auto md:w-1/2 bg-neutral-100 flex-shrink-0">
                <img id="modal-image" src="" alt="" class="w-full h-full object-cover">
                <div class="absolute top-6 left-6">
                    <span id="modal-category" class="px-4 py-2 rounded-full bg-white/90 backdrop-blur-sm shadow-md text-xs font-black text-primary-600 uppercase tracking-widest border border-primary-50/50"></span>
                </div>
            </div>

            <!-- Right: Content Section -->
            <div class="flex flex-col flex-1 min-w-0 bg-white">
                <!-- Header -->
                <div class="p-8 pb-4 flex items-start justify-between">
                    <div>
                        <h2 id="modal-name" class="text-3xl font-roboto font-black text-neutral-900 leading-tight"></h2>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span id="modal-price" class="text-3xl font-roboto font-black text-primary-600"></span>
                            <span class="font-roboto text-lg font-bold text-primary-400">đ</span>
                        </div>
                    </div>
                    <button onclick="closeMealModal()" class="p-3 rounded-2xl bg-neutral-50 text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition-all active:scale-90">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <!-- Scrollable Body -->
                <div class="px-8 pb-8 overflow-y-auto custom-scrollbar space-y-8">
                    <p id="modal-description" class="font-roboto text-neutral-500 leading-relaxed"></p>

                    <!-- Quantity Control -->
                    <div class="p-6 rounded-3xl bg-neutral-50 border border-neutral-100">
                        <label class="text-xs font-black text-neutral-400 uppercase tracking-widest mb-4 block">Số lượng món ăn</label>
                        <div class="flex items-center gap-6">
                            <button onclick="updateQty(-1)" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white border border-neutral-200 text-neutral-900 hover:border-primary-500 hover:text-primary-600 transition-all shadow-sm active:scale-95">
                                <i data-lucide="minus" class="w-6 h-6"></i>
                            </button>
                            <span id="modal-qty" class="text-3xl font-roboto font-black text-neutral-900 w-12 text-center">1</span>
                            <button onclick="updateQty(1)" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-neutral-900 text-white hover:bg-primary-500 transition-all shadow-md active:scale-95">
                                <i data-lucide="plus" class="w-6 h-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="modal-notes" class="text-xs font-black text-neutral-400 uppercase tracking-widest mb-3 block">Ghi chú cho bếp</label>
                        <textarea 
                            id="modal-notes" 
                            placeholder="Ví dụ: Không cay, thêm hành, ít cơm..." 
                            class="w-full p-6 bg-neutral-50 border border-neutral-100 rounded-[2rem] focus:outline-none focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all resize-none text-neutral-900 placeholder-neutral-300"
                            rows="3"
                        ></textarea>
                    </div>

                    <!-- Summary -->
                    <div class="pt-6 border-t border-neutral-100 flex items-center justify-between">
                        <span class="text-sm font-bold text-neutral-400">Thành tiền tạm tính</span>
                        <div class="flex items-baseline gap-1">
                            <span id="modal-subtotal" class="text-2xl font-roboto font-black text-neutral-900"></span>
                            <span class="font-roboto text-xs font-bold text-neutral-400">đ</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="p-8 pt-4 border-t border-neutral-50">
                    <button id="add-to-cart-btn" onclick="submitAddToCart()" class="w-full py-5 bg-neutral-900 text-white rounded-3xl font-black text-lg hover:bg-primary-500 transition-all shadow-xl shadow-neutral-900/10 active:scale-[0.98] flex items-center justify-center gap-3 group">
                        <i data-lucide="shopping-bag" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                        <span>Thêm vào giỏ hàng</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .pl-13 { padding-left: 3.25rem; }
    
    @keyframes fade-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in-up 0.6s ease-out forwards; }

    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E5E5; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #D4D4D4; }
</style>

<script>
    let currentItem = null;
    let currentQty = 1;

    function openMealModal(btn) {
        const item = JSON.parse(btn.getAttribute('data-item'));
        currentItem = item;
        currentQty = 1;

        // Populate fields
        document.getElementById('modal-name').textContent = item.name;
        document.getElementById('modal-price').textContent = formatNumber(item.price);
        document.getElementById('modal-description').textContent = item.description;
        document.getElementById('modal-qty').textContent = currentQty;
        document.getElementById('modal-notes').value = '';
        document.getElementById('modal-category').textContent = item.category_name;
        
        const img = document.getElementById('modal-image');
        const imgContainer = document.getElementById('modal-image-container');
        if (item.image_url) {
            img.src = item.image_url;
            imgContainer.classList.remove('hidden');
        } else {
            imgContainer.classList.add('hidden');
        }

        updateModalSubtotal();

        // Show modal
        const modal = document.getElementById('meal-modal');
        const backdrop = modal.querySelector('.modal-backdrop');
        const content = modal.querySelector('.modal-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Ensure it's flex for centering
        
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
        content.classList.remove('pointer-events-none');
        content.classList.add('pointer-events-auto');

        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
            backdrop.classList.remove('pointer-events-none');
            backdrop.classList.add('pointer-events-auto');
            
        }, 50);

        document.body.style.overflow = 'hidden';
        
        // Refresh Lucide icons in modal
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeMealModal() {
        const modal = document.getElementById('meal-modal');
        const backdrop = modal.querySelector('.modal-backdrop');
        const content = modal.querySelector('.modal-content');

        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        backdrop.classList.remove('pointer-events-auto');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('pointer-events-auto');
        content.classList.add('pointer-events-none');

        setTimeout(() => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    function updateQty(delta) {
        currentQty = Math.max(1, currentQty + delta);
        document.getElementById('modal-qty').textContent = currentQty;
        updateModalSubtotal();
    }

    function updateModalSubtotal() {
        const subtotal = currentItem.price * currentQty;
        document.getElementById('modal-subtotal').textContent = formatNumber(subtotal);
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    async function submitAddToCart() {
        if (!currentItem) return;

        const btn = document.getElementById('add-to-cart-btn');
        const originalContent = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="w-6 h-6 border-4 border-white border-t-transparent rounded-full animate-spin"></i><span>Đang xử lý...</span>';

        const formData = new FormData();
        formData.append('id', currentItem.id);
        formData.append('quantity', currentQty);
        formData.append('notes', document.getElementById('modal-notes').value);

        try {
            const response = await fetch('<?php echo url("/cart/add"); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    updateFloatingCart(data);
                    closeMealModal();
                    // Optional: Show a small toast notification
                }
            }
        } catch (error) {
            console.error('Failed to add to cart:', error);
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalContent;
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    }

    async function updateFloatingCart(data) {
        const container = document.getElementById('floating-cart-container');
        
        try {
            // Re-fetch the floating bar partial via AJAX to ensure consistency
            const resp = await fetch('<?php echo url("/cart/status_bar"); ?>');
            if (resp.ok) {
                const html = await resp.text();
                container.innerHTML = html;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        } catch (error) {
            console.error('Failed to update cart bar:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Move modal outside of any transform containers (like .animate-fade-in) 
        // to ensure position: fixed works properly relative to the viewport.
        const modal = document.getElementById('meal-modal');
        if (modal) {
            document.body.appendChild(modal);
        }

        const scrollContainer = document.getElementById('featured-scroll');
        const prevBtn = document.getElementById('featured-prev');
        const nextBtn = document.getElementById('featured-next');

        if (scrollContainer && prevBtn && nextBtn) {
            nextBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({ left: 350, behavior: 'smooth' });
            });
            prevBtn.addEventListener('click', () => {
                scrollContainer.scrollBy({ left: -350, behavior: 'smooth' });
            });
        }
        
        // Handle ESC key to close modal
        document.addEventListener('keydown', (e) => {
            const modal = document.getElementById('meal-modal');
            if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                closeMealModal();
            }
        });
    });
</script>
