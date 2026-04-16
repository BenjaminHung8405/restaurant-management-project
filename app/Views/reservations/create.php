<div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
    <section class="mb-12">
        <div class="flex flex-col gap-2">
            <span class="inline-flex items-center gap-2 text-primary-600 font-bold text-xs uppercase tracking-[0.2em]">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Bước cuối cùng
            </span>
            <h1 class="text-4xl font-display font-black text-neutral-900 tracking-tight">Hoàn tất đặt bàn</h1>
            <p class="text-neutral-500 max-w-xl">Vui lòng cung cấp thông tin liên hệ và thời gian để chúng tôi chuẩn bị đón tiếp bạn chu đáo nhất.</p>
        </div>
    </section>

    <?php if (!empty($errors)): ?>
        <section class="mb-10">
            <div class="rounded-3xl border border-red-100 bg-red-50 p-6 text-red-700 flex flex-col gap-3 shadow-sm">
                <div class="flex items-center gap-2 font-bold uppercase text-xs tracking-wider">
                    <i data-lucide="alert-octagon" class="w-5 h-5"></i>
                    <span>Thông tin chưa hợp lệ:</span>
                </div>
                <ul class="list-disc pl-10 space-y-1 text-sm font-medium opacity-90">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>
    <?php endif; ?>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
        <!-- Form Area -->
        <div class="lg:col-span-2 space-y-10">
            <div class="bg-white border border-neutral-100 rounded-[2.5rem] p-8 md:p-12 shadow-card relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-primary-100 text-primary-600 flex items-center justify-center shadow-sm">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-2xl font-display font-bold text-neutral-900">Thông tin liên hệ</h2>
                </div>

                <form method="POST" action="/reservation" class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <label for="guest_name" class="text-sm font-bold text-neutral-700 ml-1">Họ và tên của bạn</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="guest_name"
                                    name="guest_name"
                                    type="text"
                                    value="<?php echo htmlspecialchars($formData['guest_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                    placeholder="VD: Nguyễn Văn A"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="guest_phone" class="text-sm font-bold text-neutral-700 ml-1">Số điện thoại liên lạc</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="guest_phone"
                                    name="guest_phone"
                                    type="tel"
                                    value="<?php echo htmlspecialchars($formData['guest_phone'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                    placeholder="09xx..."
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-3">
                            <label for="reservation_date" class="text-sm font-bold text-neutral-700 ml-1">Chọn ngày đến</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="calendar" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="reservation_date"
                                    name="reservation_date"
                                    type="date"
                                    value="<?php echo htmlspecialchars($formData['reservation_date'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="reservation_time" class="text-sm font-bold text-neutral-700 ml-1">Giờ đặt bàn</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="reservation_time"
                                    name="reservation_time"
                                    type="time"
                                    value="<?php echo htmlspecialchars($formData['reservation_time'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label for="party_size" class="text-sm font-bold text-neutral-700 ml-1">Số lượng khách</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="party_size"
                                    name="party_size"
                                    type="number"
                                    min="1"
                                    max="20"
                                    value="<?php echo (int) $formData['party_size']; ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label for="notes" class="text-sm font-bold text-neutral-700 ml-1">Yêu cầu đặc biệt</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 px-6 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                            placeholder="VD: Bàn gần cửa sổ, tiệc sinh nhật, dị ứng thức ăn..."
                        ><?php echo htmlspecialchars($formData['notes'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="pt-8 flex flex-col md:flex-row items-center gap-6">
                        <button
                            type="submit"
                            class="w-full md:flex-1 rounded-2xl bg-neutral-900 text-white py-5 font-bold text-lg hover:bg-primary-600 transition-all shadow-xl active:scale-[0.98] flex items-center justify-center gap-3"
                        >
                            <span>Xác nhận đặt bàn</span>
                            <i data-lucide="arrow-right-circle" class="w-6 h-6"></i>
                        </button>
                        <a
                            href="/cart"
                            class="w-full md:w-auto rounded-2xl border border-neutral-200 bg-white px-10 py-5 text-neutral-500 font-bold hover:bg-neutral-50 hover:text-neutral-700 transition-all text-center"
                        >
                            Quay lại giỏ hàng
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Sidebar -->
        <div class="lg:col-span-1 lg:sticky lg:top-24">
            <div class="bg-neutral-900 rounded-[2.5rem] p-8 md:p-10 text-white shadow-card overflow-hidden relative">
                <!-- Decoration -->
                <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary-500/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h2 class="text-xl font-display font-bold mb-8 flex items-center gap-3">
                        <i data-lucide="receipt" class="w-6 h-6 text-primary-500"></i>
                        Tóm tắt thực đơn
                    </h2>

                    <div class="space-y-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex items-center gap-4 group">
                                <div class="relative shrink-0">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img
                                            src="<?php echo htmlspecialchars((string) $item['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                            alt="..."
                                            class="w-14 h-14 rounded-xl object-cover border border-white/5"
                                        >
                                    <?php else: ?>
                                        <div class="w-14 h-14 rounded-xl bg-neutral-800 flex items-center justify-center border border-white/5">
                                            <i data-lucide="image" class="w-5 h-5 text-neutral-600"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute -top-2 -right-2 w-6 h-6 bg-primary-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-neutral-900 shadow-sm">
                                        <?php echo (int) $item['quantity']; ?>
                                    </span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-neutral-100 truncate mb-0.5">
                                        <?php echo htmlspecialchars((string) $item['name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <p class="text-[10px] font-bold text-neutral-500 uppercase tracking-widest italic">
                                        <?php echo number_format((float) $item['price'], 0, ',', '.'); ?> đ
                                    </p>
                                </div>

                                <p class="text-sm font-bold text-neutral-300">
                                    <?php echo number_format((float) $item['subtotal'], 0, ',', '.'); ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-10 pt-8 border-t border-white/5 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-500 text-xs font-bold uppercase tracking-[0.2em]">Tạm tính</span>
                            <span class="text-neutral-300 font-bold text-sm"><?php echo number_format($grandTotal, 0, ',', '.'); ?> đ</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-neutral-500 text-xs font-bold uppercase tracking-[0.2em]">Phí dịch vụ</span>
                            <span class="text-emerald-500 font-bold text-sm">Miễn phí</span>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4">
                            <span class="text-white font-display font-bold">Tổng thanh toán</span>
                            <div class="text-right flex flex-col items-end">
                                <span class="text-3xl font-display font-black text-primary-500">
                                    <?php echo number_format($grandTotal, 0, ',', '.'); ?>
                                </span>
                                <span class="text-[9px] font-bold text-neutral-500 uppercase tracking-[0.3em] mt-1">Việt Nam Đồng</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 p-5 rounded-2xl bg-white/5 border border-white/5 flex items-start gap-4">
                        <i data-lucide="info" class="w-5 h-5 text-primary-600 shrink-0"></i>
                        <p class="text-[11px] text-neutral-400 leading-relaxed font-medium">
                            Chúng tôi sẽ liên lạc lại để xác nhận trạng thái bàn trống của bạn trong thời gian sớm nhất.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
