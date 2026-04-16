<div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
    <section class="mb-10">
        <h1 class="text-3xl font-display font-black text-neutral-900">Giỏ hàng của bạn</h1>
        <p class="text-neutral-500 mt-2">Kiểm tra lại các món đã chọn trước khi bắt đầu đặt bàn.</p>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
        <div class="lg:col-span-2">
            <?php if (!empty($cartItems)): ?>
                <div class="bg-white border border-neutral-100 rounded-3xl overflow-hidden shadow-card">
                    <table class="w-full text-left">
                        <thead class="bg-neutral-50/50 border-b border-neutral-100">
                            <tr>
                                <th class="px-6 py-5 font-bold text-neutral-900 text-sm uppercase tracking-wider">Món ăn</th>
                                <th class="px-6 py-5 font-bold text-neutral-900 text-sm uppercase tracking-wider text-center">Số lượng</th>
                                <th class="px-6 py-5 font-bold text-neutral-900 text-sm uppercase tracking-wider text-right">Thành tiền</th>
                                <th class="px-6 py-5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-50 text-sm">
                            <?php foreach ($cartItems as $item): ?>
                                <tr class="group hover:bg-neutral-50/50 transition-colors">
                                    <td class="px-6 py-6">
                                        <div class="flex items-center gap-5">
                                            <?php if (!empty($item['image_url'])): ?>
                                                <img
                                                    src="<?php echo htmlspecialchars((string) $item['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    alt="<?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    class="w-20 h-20 rounded-2xl object-cover border border-neutral-200/50 shadow-sm"
                                                >
                                            <?php else: ?>
                                                <div class="w-20 h-20 rounded-2xl bg-neutral-100 border border-neutral-200/50 flex items-center justify-center text-xs text-neutral-400">
                                                    No image
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <p class="font-bold text-neutral-900 text-base leading-tight"><?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <?php if (!empty($item['notes'])): ?>
                                                    <p class="text-[10px] text-neutral-400 mt-1 italic leading-tight">
                                                        <span class="font-bold uppercase tracking-tighter mr-1 opacity-70">Ghi chú:</span> 
                                                        <?php echo htmlspecialchars((string) $item['notes'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </p>
                                                <?php endif; ?>
                                                <p class="text-xs font-bold text-primary-600 mt-1"><?php echo number_format((float) $item['price'], 0, ',', '.'); ?> đ</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6">
                                        <div class="flex items-center justify-center gap-4">
                                            <a
                                                href="/cart/update?cart_item_id=<?php echo urlencode((string) $item['cart_item_id']); ?>&quantity=<?php echo $item['quantity'] - 1; ?>"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl border border-neutral-200 hover:bg-white hover:text-primary-600 hover:border-primary-200 transition-all text-neutral-500 shadow-sm"
                                            >
                                                <i data-lucide="minus" class="w-4 h-4"></i>
                                            </a>
                                            <span class="w-8 text-center font-bold text-neutral-900"><?php echo (int) $item['quantity']; ?></span>
                                            <a
                                                href="/cart/update?cart_item_id=<?php echo urlencode((string) $item['cart_item_id']); ?>&quantity=<?php echo $item['quantity'] + 1; ?>"
                                                class="w-9 h-9 flex items-center justify-center rounded-xl border border-neutral-200 hover:bg-white hover:text-primary-600 hover:border-primary-200 transition-all text-neutral-500 shadow-sm"
                                            >
                                                <i data-lucide="plus" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <p class="font-black text-neutral-900 text-base"><?php echo number_format((float) $item['subtotal'], 0, ',', '.'); ?> đ</p>
                                    </td>
                                    <td class="px-6 py-6 text-right">
                                        <a
                                            href="/cart/remove?cart_item_id=<?php echo urlencode((string) $item['cart_item_id']); ?>"
                                            class="p-2 inline-flex items-center justify-center rounded-lg text-neutral-300 hover:text-red-500 hover:bg-red-50 transition-all"
                                            title="Xóa khỏi giỏ hàng"
                                        >
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="bg-white border border-neutral-100 rounded-[3rem] p-16 text-center shadow-card animate-fade-in">
                    <div class="mx-auto w-20 h-20 bg-neutral-50 rounded-3xl flex items-center justify-center text-neutral-300 mb-6 shadow-sm">
                        <i data-lucide="shopping-cart-x" class="w-10 h-10"></i>
                    </div>
                    <h2 class="text-2xl font-display font-bold text-neutral-900 mb-2">Giỏ hàng trống</h2>
                    <p class="text-neutral-500 max-w-sm mx-auto mb-8">Bạn chưa chọn món ăn nào. Hãy quay lại thực đơn để chọn những món ngon nhất.</p>
                    <a href="/menu" class="inline-flex items-center gap-2 rounded-2xl bg-primary-500 text-white px-8 py-4 font-bold hover:bg-primary-600 shadow-lg shadow-primary-500/20 transition-all">
                        <i data-lucide="utensils" class="w-5 h-5"></i>
                        Xem thực đơn
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-neutral-100 rounded-3xl p-8 shadow-card sticky top-24">
                <h2 class="text-xl font-display font-bold text-neutral-900 mb-6">Tóm tắt đơn hàng</h2>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center justify-between text-neutral-500 text-sm">
                        <span>Số lượng món:</span>
                        <span class="font-bold text-neutral-900"><?php echo count($cartItems); ?> món</span>
                    </div>
                    <div class="flex items-center justify-between text-neutral-500 text-sm">
                        <span>Tạm tính:</span>
                        <span class="font-bold text-neutral-900"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                    </div>
                    <div class="pt-5 border-t border-neutral-50 flex flex-col gap-1">
                        <span class="text-xs font-bold text-neutral-400 uppercase tracking-widest">Tổng tiền thanh toán</span>
                        <div class="flex items-baseline justify-between">
                            <span class="text-3xl font-display font-black text-primary-600"><?php echo number_format($total, 0, ',', '.'); ?></span>
                            <span class="text-lg font-bold text-primary-500 ml-1">đ</span>
                        </div>
                    </div>
                </div>

                <?php if (!empty($cartItems)): ?>
                    <a
                        href="/reservation"
                        class="w-full inline-flex items-center justify-center gap-3 rounded-2xl bg-neutral-900 text-white py-4 font-bold hover:bg-primary-600 transition-all shadow-lg active:scale-95"
                    >
                        <span>Tiếp tục đặt bàn</span>
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                <?php else: ?>
                    <button
                        disabled
                        class="w-full inline-flex items-center justify-center rounded-2xl bg-neutral-200 text-neutral-400 py-4 font-bold cursor-not-allowed"
                    >
                        Tiếp tục đặt bàn
                    </button>
                <?php endif; ?>
                
                <div class="mt-6 flex flex-col items-center gap-4">
                    <a href="/menu" class="text-sm font-bold text-neutral-500 hover:text-primary-600 transition-colors inline-flex items-center gap-2">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        <span>Tiếp tục chọn món</span>
                    </a>
                </div>
                
                <div class="mt-8 pt-6 border-t border-neutral-50">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600 flex-shrink-0">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <p class="text-[11px] text-neutral-400 leading-relaxed font-medium">
                            Đơn hàng của bạn được bảo mật và xử lý ưu tiên tại RestoMS. Vui lòng hoàn tất đặt bàn để giữ chỗ.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
