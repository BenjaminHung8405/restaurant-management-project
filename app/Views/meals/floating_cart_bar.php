<?php if ($cartCount > 0): ?>
<div class="fixed bottom-4 inset-x-4 md:inset-x-auto md:w-[420px] md:right-8 z-50 flex items-center justify-between gap-4 px-5 py-4 rounded-2xl bg-amber-500 text-white shadow-2xl transition-all duration-300 ease-in-out animate-fade-in border border-amber-400">
    <!-- Left: Cart icon + Items count + Total price -->
    <div class="flex items-center gap-3">
        <div class="flex-shrink-0 p-3 bg-white/20 rounded-xl backdrop-blur-sm">
            <i data-lucide="shopping-cart" class="w-6 h-6 stroke-[2.5]"></i>
        </div>
        <div class="flex flex-col">
            <span class="text-xs font-bold text-white/90 uppercase tracking-wider">
                <?php echo $cartCount; ?> <?php echo $cartCount === 1 ? 'món' : 'món'; ?>
            </span>
            <span class="text-xl font-black text-white">
                <?php echo number_format($cartTotal, 0, ',', '.'); ?> <span class="text-sm font-bold opacity-80">đ</span>
            </span>
        </div>
    </div>

    <!-- Right: Checkout link -->
    <a 
        href="<?php echo url('/cart'); ?>" 
        class="flex-shrink-0 px-6 py-3 rounded-xl bg-white text-amber-600 font-black text-sm uppercase tracking-tight shadow-sm hover:bg-amber-50 hover:scale-105 transition-all active:scale-95 flex items-center gap-2 group"
    >
        <span>Xem giỏ</span>
        <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-1"></i>
    </a>
</div>

<style>
    @keyframes slide-in-bottom {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-fade-in {
        animation: slide-in-bottom 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
<?php endif; ?>
