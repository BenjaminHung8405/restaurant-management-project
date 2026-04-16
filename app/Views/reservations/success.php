<div class="max-w-7xl mx-auto px-4 sm:px-8 py-20">
    <div class="rounded-[3rem] border border-neutral-100 bg-white p-10 md:p-16 text-center max-w-3xl mx-auto shadow-card relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-16 -right-16 w-48 h-48 bg-emerald-50 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-primary-50 rounded-full blur-3xl opacity-60"></div>

        <div class="relative z-10 flex flex-col items-center">
            <div class="w-24 h-24 bg-emerald-50 text-emerald-500 rounded-[2rem] flex items-center justify-center mb-8 shadow-sm">
                <i data-lucide="check-circle-2" class="w-12 h-12"></i>
            </div>
            
            <h2 class="text-4xl font-display font-black text-neutral-900 mb-4 tracking-tight">Đặt bàn thành công!</h2>
            <p class="text-neutral-500 text-lg max-w-xl mx-auto mb-12 font-medium">
                Cảm ơn bạn đã tin tưởng chọn <span class="text-primary-600 font-bold">RestoMS</span>. Đội ngũ nhân viên của chúng tôi sẽ sớm liên hệ xác nhận trạng thái bàn trống cho bạn.
            </p>

            <div class="w-full bg-neutral-50/50 rounded-3xl p-8 border border-neutral-100 shadow-sm mb-12 text-left space-y-6">
                <div class="flex items-center gap-3 border-b border-neutral-100 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-600 shadow-sm">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-display font-bold text-neutral-900">Chi tiết thông tin</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Mã hiệu đặt bàn</span>
                        <p class="font-mono text-sm font-bold text-neutral-700 truncate"><?php echo htmlspecialchars($reservationId, ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                    <div class="space-y-1 text-right md:text-left">
                        <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-widest">Tổng dự kiến (đã bao gồm VAT)</span>
                        <p class="font-display font-black text-primary-600 text-2xl">
                            <?php echo number_format($grandTotal, 0, ',', '.'); ?> <span class="text-sm">đ</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
                <a href="/menu" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-2xl bg-neutral-900 text-white px-10 py-5 font-bold hover:bg-primary-600 shadow-xl active:scale-[0.98] transition-all">
                    <span>Xem thêm món khác</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                </a>
                <a href="/" class="w-full sm:w-auto inline-flex items-center justify-center rounded-2xl border border-neutral-200 bg-white text-neutral-500 px-10 py-5 font-bold hover:bg-neutral-50 hover:text-neutral-700 transition-all">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</div>
