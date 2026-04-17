<div class="max-w-4xl mx-auto px-4 sm:px-8 py-12">
    <section class="mb-12 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary-100 text-primary-700 font-bold text-[10px] uppercase tracking-[0.2em] mb-4">
            <i data-lucide="calendar-check" class="w-3.5 h-3.5"></i>
            Dịch vụ đặt chỗ
        </div>
        <h1 class="text-4xl sm:text-5xl font-display font-black text-neutral-900 tracking-tight mb-4">Đặt bàn nhanh</h1>
        <p class="text-neutral-500 max-w-xl mx-auto text-lg leading-relaxed">Điền thông tin bên dưới để giữ chỗ ngay tại nhà hàng. Chúng tôi luôn sẵn sàng đón tiếp bạn.</p>
    </section>

    <?php if (!empty($errors)): ?>
        <section class="mb-10 max-w-2xl mx-auto">
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

    <section class="max-w-2xl mx-auto">
        <div class="bg-white border border-neutral-100 rounded-[3rem] p-8 md:p-12 shadow-card relative overflow-hidden">
            <!-- Background Decoration -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary-50 rounded-full blur-3xl opacity-50"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 rounded-2xl bg-neutral-900 text-white flex items-center justify-center shadow-lg">
                        <i data-lucide="user-check" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-display font-bold text-neutral-900">Thông tin đặt chỗ</h2>
                        <p class="text-xs text-neutral-400 font-medium">Vui lòng điền chính xác thông tin để chúng tôi liên hệ.</p>
                    </div>
                </div>

                <form method="POST" action="/reservation" class="space-y-8">
                    <!-- Guest Name & Phone -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2.5">
                            <label for="guest_name" class="text-sm font-bold text-neutral-700 ml-1">Họ và tên</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="guest_name"
                                    name="guest_name"
                                    type="text"
                                    value="<?php echo htmlspecialchars($formData['guest_name'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium placeholder:text-neutral-300"
                                    placeholder="Tên của bạn"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <label for="guest_phone" class="text-sm font-bold text-neutral-700 ml-1">Số điện thoại</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="guest_phone"
                                    name="guest_phone"
                                    type="tel"
                                    value="<?php echo htmlspecialchars($formData['guest_phone'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium placeholder:text-neutral-300"
                                    placeholder="09xx..."
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Date, Time, Party Size -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2.5">
                            <label for="reservation_date" class="text-sm font-bold text-neutral-700 ml-1">Ngày đến</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="calendar" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="reservation_date"
                                    name="reservation_date"
                                    type="date"
                                    value="<?php echo htmlspecialchars($formData['reservation_date'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <label for="reservation_time" class="text-sm font-bold text-neutral-700 ml-1">Giờ đặt</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="clock" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="reservation_time"
                                    name="reservation_time"
                                    type="time"
                                    value="<?php echo htmlspecialchars($formData['reservation_time'], ENT_QUOTES, 'UTF-8'); ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                    required
                                >
                            </div>
                        </div>

                        <div class="space-y-2.5">
                            <label for="party_size" class="text-sm font-bold text-neutral-700 ml-1">Số khách</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>
                                <input
                                    id="party_size"
                                    name="party_size"
                                    type="number"
                                    min="1"
                                    max="50"
                                    value="<?php echo (int) $formData['party_size']; ?>"
                                    class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2.5">
                        <label for="notes" class="text-sm font-bold text-neutral-700 ml-1">Yêu cầu đặc biệt</label>
                        <textarea
                            id="notes"
                            name="notes"
                            rows="4"
                            class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 px-6 py-4 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium placeholder:text-neutral-300 resize-none"
                            placeholder="VD: Bàn gần cửa sổ, tiệc sinh nhật..."
                        ><?php echo htmlspecialchars($formData['notes'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>

                    <div class="pt-6">
                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-neutral-900 text-white py-5 font-black text-xl hover:bg-primary-600 transition-all shadow-xl shadow-neutral-900/10 active:scale-[0.98] flex items-center justify-center gap-3 cursor-pointer group"
                        >
                            <span>Xác nhận đặt bàn</span>
                            <i data-lucide="arrow-right-circle" class="w-6 h-6 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-8 p-5 rounded-2xl bg-primary-50 border border-primary-100 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-600 shadow-sm flex-shrink-0">
                        <i data-lucide="info" class="w-5 h-5"></i>
                    </div>
                    <p class="text-[11px] text-neutral-500 leading-relaxed font-medium">
                        Khi nhấn xác nhận, thông tin của bạn sẽ được gửi đến bộ phận quản lý. Chúng tôi sẽ gọi điện xác thực trạng thái bàn trống trong vòng 5-10 phút.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Map or Location Preview (Optional aesthetic element) -->
    <section class="mt-16 text-center">
        <p class="text-neutral-400 text-xs font-bold uppercase tracking-[0.3em] mb-6">Gặp chúng tôi tại địa chỉ</p>
        <div class="flex items-center justify-center gap-4 text-neutral-600">
            <i data-lucide="map-pin" class="w-5 h-5 text-primary-500"></i>
            <span class="font-medium">123 Đường Hương Vị, TP. Long Xuyên, An Giang</span>
        </div>
    </section>
</div>
