<div class="bg-white">
    <!-- 1. HERO SECTION -->
    <section class="relative flex items-center justify-center min-h-[85vh] px-4 sm:px-8 py-24 overflow-hidden bg-gradient-to-br from-orange-50 via-white to-amber-50">
        <!-- Background decorative blobs -->
        <div class="absolute -top-32 -right-32 w-[480px] h-[480px] rounded-full bg-primary-100/40 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-[360px] h-[360px] rounded-full bg-amber-100/50 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-[900px] mx-auto text-center flex flex-col items-center gap-8">
            <!-- Eyebrow label -->
            <span class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-bold shadow-sm bg-orange-50 text-primary-700 border border-orange-200">
                <i data-lucide="star" class="w-4 h-4 text-amber-500 fill-amber-500"></i>
                Ẩm thực tinh hoa — Long Xuyên
            </span>

            <!-- Brand Name -->
            <div class="flex flex-col items-center gap-2">
                <span class="text-primary-600 font-extrabold tracking-widest uppercase text-sm">Chào mừng đến với</span>
                <h1 class="font-display font-black text-6xl sm:text-7xl lg:text-8xl text-neutral-900 tracking-tight">
                    Resto<span class="text-primary-500">MS</span>
                </h1>
            </div>

            <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-5xl text-neutral-800 leading-tight text-balance">
                Hương vị thượng hạng <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-500 to-amber-600">trong từng món ăn</span>
            </h2>
            
            <p class="max-w-[600px] text-lg sm:text-xl leading-relaxed text-neutral-600 font-medium text-pretty">
                Khám phá không gian ẩm thực đỉnh cao với thực đơn đa dạng và dịch vụ tận tâm. Mang đến trải nghiệm hoàn hảo cho mọi thực khách.
            </p>

            <div class="flex flex-col xs:flex-row items-center gap-4 mt-2">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo url('/login'); ?>" class="w-full sm:w-auto px-10 py-4 rounded-xl bg-primary-500 text-white font-bold text-lg shadow-lg shadow-primary-500/20 hover:bg-primary-600 hover:-translate-y-1 transition-all flex items-center justify-center">
                        Bắt đầu
                    </a>
                <?php else: ?>
                    <a href="<?php echo url('/menu'); ?>" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-primary-500 text-white font-bold text-lg shadow-lg shadow-primary-500/20 hover:bg-primary-600 hover:-translate-y-1 transition-all flex items-center justify-center">
                        Khám phá thực đơn
                    </a>
                <?php endif; ?>
                <button onclick="openReservationModal()" class="w-full sm:w-auto px-8 py-4 rounded-xl border-2 border-primary-500 text-primary-600 font-bold text-lg hover:bg-orange-50 transition-all cursor-pointer">
                    Đặt bàn ngay
                </button>
            </div>

            <!-- Social proof -->
            <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-8 pt-8">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">⭐</span>
                    <span class="text-neutral-700">
                        <span class="font-bold text-neutral-900">4.9</span>/5.0 từ <span class="font-bold text-neutral-900">1000+</span> đánh giá
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. FEATURES SECTION -->
    <section class="py-20 px-4 sm:px-8 bg-neutral-50" aria-labelledby="features-heading">
        <div class="max-w-7xl mx-auto flex flex-col items-center gap-12">
            <div class="text-center max-w-[520px]">
                <p class="text-sm font-semibold uppercase tracking-widest mb-2 text-primary-700">Dịch vụ của chúng tôi</p>
                <h2 id="features-heading" class="font-display font-bold text-3xl sm:text-4xl text-neutral-900">Tại sao nên chọn RestoMS?</h2>
            </div>

            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Feature 1 -->
                <div class="group flex flex-col items-center text-center p-8 rounded-2xl bg-white border border-neutral-100 shadow-card hover:shadow-card-hover transition-all duration-300 hover:-translate-y-1">
                    <div class="mb-5 flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-100 text-primary-700 group-hover:bg-orange-200 transition-colors">
                        <i data-lucide="chef-hat" class="w-7 h-7"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-neutral-900 font-display">Đầu bếp hàng đầu</h3>
                    <p class="text-sm leading-relaxed text-neutral-600">Đội ngũ đầu bếp giàu kinh nghiệm, tận tâm trong từng công thức chế biến.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group flex flex-col items-center text-center p-8 rounded-2xl bg-white border border-neutral-100 shadow-card hover:shadow-card-hover transition-all duration-300 hover:-translate-y-1">
                    <div class="mb-5 flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-100 text-primary-700 group-hover:bg-orange-200 transition-colors">
                        <i data-lucide="utensils" class="w-7 h-7"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-neutral-900 font-display">Nguyên liệu tươi sạch</h3>
                    <p class="text-sm leading-relaxed text-neutral-600">Cam kết sử dụng nguyên liệu tươi ngon nhất, đảm bảo vệ sinh an toàn thực phẩm.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group flex flex-col items-center text-center p-8 rounded-2xl bg-white border border-neutral-100 shadow-card hover:shadow-card-hover transition-all duration-300 hover:-translate-y-1">
                    <div class="mb-5 flex items-center justify-center w-14 h-14 rounded-2xl bg-orange-100 text-primary-700 group-hover:bg-orange-200 transition-colors">
                        <i data-lucide="clock" class="w-7 h-7"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-neutral-900 font-display">Phục vụ nhanh chóng</h3>
                    <p class="text-sm leading-relaxed text-neutral-600">Quy trình đặt và phục vụ tối ưu, giảm thiểu thời gian chờ đợi của khách hàng.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. FEATURED DISHES SECTION -->
    <?php if (!empty($featuredDishes)): ?>
    <section class="py-24 px-4 sm:px-8 bg-white" aria-labelledby="dishes-heading">
        <div class="max-w-7xl mx-auto flex flex-col items-center gap-14">
            <div class="text-center max-w-[600px]">
                <p class="text-sm font-bold uppercase tracking-widest mb-3 text-primary-600">Thực đơn nổi bật</p>
                <h2 id="dishes-heading" class="font-display font-black text-4xl sm:text-5xl text-neutral-900 mb-4">Các món ăn được yêu thích</h2>
                <p class="text-lg text-neutral-500 leading-relaxed">Khám phá những món ăn đặc sắc nhất của nhà chúng tôi.</p>
            </div>

            <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($featuredDishes as $dish): ?>
                <article class="group relative flex flex-col bg-white rounded-3xl overflow-hidden border border-neutral-100 shadow-sm hover:-translate-y-2 hover:shadow-2xl transition-all duration-300">
                    <!-- Image -->
                    <div class="relative h-64 w-full overflow-hidden bg-neutral-100">
                        <img 
                            src="<?php echo htmlspecialchars($dish['image_url']); ?>" 
                            alt="<?php echo htmlspecialchars($dish['name']); ?>"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        >
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                        <span class="absolute top-4 left-4 px-3 py-1.5 rounded-full bg-amber-500 text-white text-xs font-bold tracking-wide shadow-md z-10">
                            Phổ biến
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="flex flex-col flex-1 p-6 gap-3 bg-white">
                        <h3 class="text-xl font-bold text-neutral-900 font-display line-clamp-1 group-hover:text-primary-600 transition-colors duration-200">
                            <?php echo htmlspecialchars($dish['name']); ?>
                        </h3>

                        <p class="text-sm leading-relaxed text-neutral-600 line-clamp-2 flex-grow">
                            <?php echo htmlspecialchars($dish['description']); ?>
                        </p>

                        <!-- Price + CTA row -->
                        <div class="flex items-center justify-between pt-4 mt-auto border-t border-neutral-100">
                            <span class="text-lg font-bold text-primary-600">
                                <?php echo number_format($dish['price'], 0, ',', '.'); ?> đ
                            </span>
                            <a href="<?php echo url('/menu'); ?>" class="px-4 py-2 rounded-lg bg-primary-500 text-white font-semibold text-sm hover:bg-primary-600 hover:-translate-y-px shadow-sm hover:shadow-md transition-all">
                                Xem ngay
                            </a>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <div class="mt-4">
                <a href="<?php echo url('/menu'); ?>" class="px-8 py-3 rounded-lg border-2 border-primary-500 text-primary-600 font-bold hover:bg-orange-50 transition-all">
                    Xem toàn bộ thực đơn
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- 4. CTA BANNER -->
    <section class="relative flex items-center justify-center min-h-[50dvh] px-4 sm:px-8 py-20 overflow-hidden bg-neutral-900">
        <!-- Background blobs -->
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-56 h-56 rounded-full bg-white/5 pointer-events-none"></div>

        <div class="relative z-10 max-w-[800px] mx-auto text-center flex flex-col items-center gap-6">
            <h2 class="font-display font-bold text-3xl sm:text-4xl text-white leading-tight text-balance">
                Sẵn sàng để <span class="text-amber-400">thưởng thức</span> những món ăn tuyệt vời nhất?
            </h2>

            <p class="max-w-[480px] text-base sm:text-lg text-white/80 leading-relaxed">
                Đừng bỏ lỡ cơ hội trải nghiệm không gian và hương vị độc đáo tại RestoMS.
            </p>

            <div class="flex flex-col xs:flex-row items-center gap-3 pt-2">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo url('/login'); ?>" class="px-8 py-4 rounded-xl bg-white text-primary-700 font-bold hover:bg-neutral-100 transition-all">
                        Bắt đầu ngay
                    </a>
                <?php else: ?>
                    <a href="<?php echo url('/menu'); ?>" class="px-8 py-4 rounded-xl bg-white text-primary-700 font-bold hover:bg-neutral-100 transition-all">
                        Khám phá menu
                    </a>
                <?php endif; ?>
                <button onclick="openReservationModal()" class="px-8 py-4 rounded-xl border border-white/40 text-white font-bold hover:bg-white/10 transition-all cursor-pointer">
                    Đặt bàn ngay
                </button>
            </div>
        </div>
    </section>
</div>
