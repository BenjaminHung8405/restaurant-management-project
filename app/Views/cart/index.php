<div class="bg-neutral-50 min-h-screen">
    <div id="cart-page-container" class="max-w-7xl mx-auto px-4 py-8 lg:py-12 sm:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex flex-col gap-1">
                <h1 class="text-3xl sm:text-4xl font-display font-black text-neutral-900 tracking-tight">
                    Giỏ hàng của bạn
                </h1>
                <p class="text-neutral-500 text-sm">Kiểm tra lại các món đã chọn trước khi gửi xuống bếp.</p>
            </div>
            <a
                href="<?php echo url('/menu'); ?>"
                class="text-sm font-bold text-neutral-500 hover:text-primary-600 hover:underline underline-offset-4 transition-all p-2 flex items-center gap-2"
            >
                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                Quay lại thực đơn
            </a>
        </div>

        <div id="cart-content-wrapper">
            <?php if (!empty($cartItems)): ?>
                <!-- 2-Column Responsive Layout -->
                <div class="lg:grid lg:grid-cols-12 lg:gap-10 flex flex-col gap-8 relative">
                    <!-- ── Left Column: Cart Items List ── -->
                    <div id="cart-items-list" class="lg:col-span-8 flex flex-col gap-4">
                        <?php foreach ($cartItems as $item): ?>
                            <div
                                id="cart-item-<?php echo $item['cart_item_id']; ?>"
                                class="group flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 p-4 sm:p-5 bg-white rounded-2xl border border-neutral-100 shadow-card hover:shadow-card-hover transition-all duration-300"
                            >
                                <!-- Product Image -->
                                <div class="relative w-full sm:w-28 h-40 sm:h-28 flex-shrink-0 bg-neutral-100 rounded-xl overflow-hidden border border-neutral-50">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img
                                            src="<?php echo htmlspecialchars((string) $item['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                            alt="<?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        >
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-neutral-300">
                                            <i data-lucide="utensils" class="w-10 h-10"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1 min-w-0 w-full">
                                    <h3 class="text-xl sm:text-lg font-bold text-neutral-900 line-clamp-2">
                                        <?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </h3>

                                    <!-- Custom Notes -->
                                    <?php if (!empty($item['notes'])): ?>
                                        <div class="mt-2 p-2 bg-primary-50 rounded-lg border border-primary-100/50">
                                            <p class="text-xs text-neutral-500 italic flex items-start gap-1.5">
                                                <span class="font-bold text-primary-700/70 not-italic uppercase tracking-tighter">Ghi chú:</span>
                                                <span class="line-clamp-2"><?php echo htmlspecialchars((string) $item['notes'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="text-primary-600 font-black mt-3 sm:mt-2 text-lg">
                                        <?php echo number_format((float) $item['price'], 0, ',', '.'); ?> đ
                                    </div>
                                </div>

                                <!-- Controls -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto mt-2 sm:mt-0 pt-4 sm:pt-0 border-t border-neutral-100 sm:border-none">
                                    <!-- Quantity Adjuster -->
                                    <div class="flex items-center bg-neutral-50 rounded-full border border-neutral-200 shadow-sm overflow-hidden p-1">
                                        <button
                                            onclick="updateCartItem('<?php echo $item['cart_item_id']; ?>', -1)"
                                            class="w-9 h-9 flex items-center justify-center text-neutral-500 hover:text-primary-600 hover:bg-white rounded-full transition-all"
                                            aria-label="Giảm"
                                        >
                                            <i data-lucide="minus" class="w-4 h-4"></i>
                                        </button>
                                        <span id="qty-<?php echo $item['cart_item_id']; ?>" class="w-10 text-center font-bold text-neutral-900 select-none">
                                            <?php echo (int) $item['quantity']; ?>
                                        </span>
                                        <button
                                            onclick="updateCartItem('<?php echo $item['cart_item_id']; ?>', 1)"
                                            class="w-9 h-9 flex items-center justify-center text-neutral-500 hover:text-primary-600 hover:bg-white rounded-full transition-all"
                                            aria-label="Tăng"
                                        >
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                        </button>
                                    </div>

                                    <!-- Remove Button -->
                                    <button
                                        onclick="removeCartItem('<?php echo $item['cart_item_id']; ?>')"
                                        class="p-2.5 text-neutral-300 hover:text-red-500 hover:bg-red-50 rounded-full transition-all flex-shrink-0"
                                        title="Xóa khỏi giỏ hàng"
                                    >
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4">
                            <a
                                href="<?php echo url('/menu'); ?>"
                                class="inline-flex items-center gap-2 text-sm font-bold text-primary-600 hover:text-primary-700 transition-colors group"
                            >
                                <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                                Tiếp tục chọn món
                            </a>
                        </div>
                    </div>

                    <!-- ── Right Column: Order Summary (Sticky) ── -->
                    <div class="lg:col-span-4 h-full">
                        <div class="bg-white rounded-[2.5rem] p-8 sticky top-24 border border-neutral-100 shadow-card">
                            <h2 class="text-xl font-bold text-neutral-900 mb-6 border-b border-neutral-100 pb-4">
                                Tóm tắt đơn hàng
                            </h2>

                            <!-- Table Validation Banner -->
                            <?php if (empty($_SESSION['table_id'])): ?>
                                <div class="mb-6 p-4 bg-orange-50 border-2 border-orange-200 rounded-2xl flex gap-3 animate-pulse">
                                    <div class="flex-shrink-0 text-orange-600">
                                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-orange-900">
                                            Vui lòng quét mã QR tại bàn
                                        </p>
                                        <p class="text-[11px] text-orange-700 mt-0.5 leading-tight">
                                            Chúng tôi sẽ tự động nhận diện bàn của bạn để phục vụ chính xác nhất.
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-emerald-700 font-bold uppercase tracking-wider">Bàn của bạn đã sẵn sàng</p>
                                        <p class="text-[10px] text-emerald-600 mt-0.5 opacity-80 italic">(Hệ thống đã nhận diện được vị trí)</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Order items count -->
                            <div class="flex flex-col gap-4 mb-6">
                                <div class="flex justify-between items-center text-neutral-500 text-sm">
                                    <span class="font-medium">Tổng số món</span>
                                    <span id="summary-cart-count" class="font-bold text-neutral-900"><?php echo count($cartItems); ?> món</span>
                                </div>
                            </div>

                            <!-- Subtotal -->
                            <div class="border-t border-neutral-100 border-dashed pt-5 mb-8">
                                <div class="flex justify-between items-end">
                                    <span class="text-neutral-500 font-bold text-sm uppercase tracking-widest">Tổng cộng</span>
                                    <div class="text-right flex flex-col items-end">
                                        <span id="summary-cart-total" class="text-4xl font-black text-primary-500 tracking-tighter">
                                            <?php echo number_format($total, 0, ',', '.'); ?>
                                        </span>
                                        <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-[0.2em] mt-1">Việt Nam Đồng</span>
                                    </div>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <form action="<?php echo url('/cart/place-order'); ?>" method="POST">
                                <button
                                    type="submit"
                                    class="flex items-center justify-center gap-3 w-full py-5 rounded-2xl font-black text-lg shadow-xl transition-all duration-300 transform active:scale-95 bg-primary-500 text-white hover:bg-primary-600 shadow-primary-500/20 cursor-pointer"
                                >
                                    <span>Gửi Bếp</span>
                                    <i data-lucide="send" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </form>

                            <!-- Info note -->
                            <div class="mt-8 flex flex-col gap-3 items-center text-center">
                                <div class="flex items-center gap-2 text-[10px] text-neutral-400 font-bold uppercase tracking-widest">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    <span>Thời gian chờ dự kiến: 15-20p</span>
                                </div>
                                <p class="text-[11px] text-neutral-400 leading-relaxed font-medium">
                                    Nhân viên phục vụ sẽ mang món ăn đến ngay khi hoàn tất chế biến.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="bg-white border border-neutral-100 rounded-[3rem] p-16 sm:p-24 text-center shadow-card max-w-3xl mx-auto">
                    <div class="mx-auto w-24 h-24 bg-primary-50 rounded-[2rem] flex items-center justify-center text-primary-500 mb-8 shadow-inner shadow-primary-500/5">
                        <i data-lucide="shopping-basket" class="w-12 h-12" stroke-width="1.5"></i>
                    </div>
                    <h2 class="text-3xl font-display font-black text-neutral-900 mb-3 tracking-tight">Giỏ hàng đang trống</h2>
                    <p class="text-neutral-500 max-w-md mx-auto mb-10 text-lg leading-relaxed">Đừng để chiếc bụng đói! Hãy dạo quanh thực đơn và chọn lựa những món ăn tinh túy nhất của chúng tôi.</p>
                    <a href="<?php echo url('/menu'); ?>" class="inline-flex items-center gap-3 rounded-2xl bg-primary-500 text-white px-10 py-5 font-black text-lg hover:bg-primary-600 shadow-xl shadow-primary-500/20 transition-all hover:-translate-y-1 active:translate-y-0">
                        <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                        Khám phá thực đơn ngay
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<template id="empty-cart-template">
    <div class="bg-white border border-neutral-100 rounded-[3rem] p-16 sm:p-24 text-center shadow-card max-w-3xl mx-auto animate-fade-in">
        <div class="mx-auto w-24 h-24 bg-primary-50 rounded-[2rem] flex items-center justify-center text-primary-500 mb-8 shadow-inner shadow-primary-500/5">
            <i data-lucide="shopping-basket" class="w-12 h-12" stroke-width="1.5"></i>
        </div>
        <h2 class="text-3xl font-display font-black text-neutral-900 mb-3 tracking-tight">Giỏ hàng đang trống</h2>
        <p class="text-neutral-500 max-w-md mx-auto mb-10 text-lg leading-relaxed">Đừng để chiếc bụng đói! Hãy dạo quanh thực đơn và chọn lựa những món ăn tinh túy nhất của chúng tôi.</p>
        <a href="<?php echo url('/menu'); ?>" class="inline-flex items-center gap-3 rounded-2xl bg-primary-500 text-white px-10 py-5 font-black text-lg hover:bg-primary-600 shadow-xl shadow-primary-500/20 transition-all hover:-translate-y-1 active:translate-y-0">
            <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
            Khám phá thực đơn ngay
        </a>
    </div>
</template>

<script>
    async function updateCartItem(cartItemId, delta) {
        const qtyEl = document.getElementById(`qty-${cartItemId}`);
        if (!qtyEl) return;
        
        const currentQty = parseInt(qtyEl.textContent);
        const newQty = currentQty + delta;
        
        // If final quantity is 0, we treat it as a removal
        if (newQty <= 0) {
            removeCartItem(cartItemId);
            return;
        }

        try {
            const response = await fetch(`<?php echo url('/cart/update'); ?>?cart_item_id=${cartItemId}&quantity=${newQty}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            if (data.success) {
                // Update specific quantity
                qtyEl.textContent = newQty;
                
                // Update global totals
                updateCartUI(data);
            }
        } catch (err) {
            console.error('Failed to update cart item:', err);
        }
    }

    async function removeCartItem(cartItemId) {
        const itemEl = document.getElementById(`cart-item-${cartItemId}`);
        if (!itemEl) return;

        // Visual feedback
        itemEl.classList.add('opacity-50', 'pointer-events-none', 'translate-x-4');
        
        try {
            const response = await fetch(`<?php echo url('/cart/remove'); ?>?cart_item_id=${cartItemId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            
            if (data.success) {
                // Remove from DOM with a delay for animation
                itemEl.classList.add('opacity-0');
                setTimeout(() => {
                    itemEl.remove();
                    
                    // If no items left, show empty state
                    const remainingItems = document.querySelectorAll('[id^="cart-item-"]');
                    if (remainingItems.length === 0) {
                        const wrapper = document.getElementById('cart-content-wrapper');
                        const template = document.getElementById('empty-cart-template');
                        wrapper.innerHTML = template.innerHTML;
                        if (typeof lucide !== 'undefined') lucide.createIcons();
                    }
                }, 300);
                
                // Update global totals
                updateCartUI(data);
            }
        } catch (err) {
            itemEl.classList.remove('opacity-50', 'pointer-events-none', 'translate-x-4');
            console.error('Failed to remove cart item:', err);
        }
    }

    function updateCartUI(data) {
        const totalEl = document.getElementById('summary-cart-total');
        const countEl = document.getElementById('summary-cart-count');
        
        if (totalEl) totalEl.textContent = data.cartTotalFormatted;
        if (countEl) {
            const uniqueItemsCount = document.querySelectorAll('[id^="cart-item-"]').length;
            countEl.textContent = `${uniqueItemsCount} món`;
        }
        
        // Sync the header badge and floating bar
        if (typeof window.updateCartUI === 'function') {
            window.updateCartUI();
        }
    }
</script>
</div>
