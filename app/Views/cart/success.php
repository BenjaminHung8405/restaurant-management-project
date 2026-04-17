<div class="flex flex-col items-center justify-center min-h-[70vh] px-4 py-20 text-center">
    <!-- Animated Check Circle -->
    <div class="mb-10 relative">
        <div class="w-24 h-24 bg-emerald-100 rounded-[2.5rem] flex items-center justify-center animate-bounce shadow-lg shadow-emerald-500/10">
            <i data-lucide="check" class="w-12 h-12 text-emerald-600" stroke-width="3"></i>
        </div>
        <div class="absolute -z-10 top-0 left-0 w-24 h-24 bg-emerald-400/20 rounded-[2.5rem] blur-xl animate-pulse"></div>
    </div>

    <!-- Content -->
    <div class="max-w-md mx-auto space-y-4">
        <h1 class="text-4xl font-display font-black text-neutral-900 tracking-tight">
            Đã gửi đơn hàng thành công!
        </h1>
        <p class="text-neutral-500 text-lg leading-relaxed">
            Bếp đã nhận được thực đơn của bạn. Các đầu bếp đang bắt đầu chuẩn bị những món ăn tươi ngon nhất.
        </p>
        
        <?php if (isset($orderId)): ?>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-neutral-100 border border-neutral-200 text-xs font-bold text-neutral-500 uppercase tracking-widest mt-4">
                Mã đơn hàng: <span class="text-neutral-900"><?php echo substr($orderId, 0, 8); ?>...</span>
            </div>
        <?php endif; ?>

        <div class="pt-10 flex flex-col gap-4 items-center">
            <p class="text-sm font-medium text-neutral-400 italic">
                Vui lòng đợi trong giây lát, phục vụ viên sẽ mang món đến bàn của bạn.
            </p>
            
            <div class="flex items-center gap-4 mt-4">
                <a 
                    href="<?php echo url('/menu'); ?>" 
                    class="px-8 py-4 rounded-2xl bg-primary-500 text-white font-black text-lg hover:bg-primary-600 shadow-xl shadow-primary-500/20 transition-all hover:-translate-y-1"
                >
                    Tiếp tục gọi món
                </a>
                <a 
                    href="<?php echo url('/'); ?>" 
                    class="px-8 py-4 rounded-2xl border border-neutral-200 bg-white text-neutral-600 font-bold hover:bg-neutral-50 transition-all"
                >
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Redirect back to menu after 5 seconds automatically if they don't click anything
    /*
    setTimeout(() => {
        window.location.href = '<?php echo url('/menu'); ?>';
    }, 5000);
    */
</script>
