<div class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-2rem)] max-w-lg animate-fade-in">
    <a href="<?php echo url('/cart'); ?>" class="flex items-center justify-between bg-neutral-900/90 backdrop-blur-xl text-white p-4 pr-6 rounded-[2rem] shadow-2xl border border-white/10 hover:bg-primary-600 hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex items-center gap-4">
            <div class="relative">
                <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center transition-colors group-hover:bg-white/20">
                    <i data-lucide="shopping-basket" class="w-7 h-7"></i>
                </div>
                <span class="absolute -top-2 -right-2 bg-primary-500 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center shadow-lg border-2 border-neutral-900 group-hover:scale-110 transition-transform">
                    <?php echo $cartCount; ?>
                </span>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/50 mb-0.5">Giỏ hàng của bạn</p>
                <p class="text-xl font-display font-black">
                    <?php echo number_format($cartTotal, 0, ',', '.'); ?> <span class="text-sm font-bold opacity-70 text-primary-400">đ</span>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="font-bold text-sm tracking-tight hidden sm:inline">Xem đơn hàng</span>
            <div class="w-10 h-10 rounded-full bg-white text-neutral-900 flex items-center justify-center group-hover:bg-primary-50 group-hover:translate-x-1 transition-all">
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </div>
        </div>
    </a>
</div>
