<section class="min-h-[85vh] flex items-center justify-center py-20 px-4 sm:px-8 bg-gradient-to-br from-orange-50 via-white to-amber-50 relative overflow-hidden">
    <!-- Background blobs -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100/40 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-amber-100/50 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 animate-fade-in">
        <div class="bg-white border border-neutral-100 rounded-[2.5rem] shadow-card p-10 md:p-12">
            <div class="mb-12 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-[2rem] bg-primary-50 text-primary-600 mb-8 shadow-sm">
                    <i data-lucide="lock" class="w-10 h-10"></i>
                </div>
                <h1 class="text-4xl font-display font-black text-neutral-900 tracking-tight">Chào mừng!</h1>
                <p class="text-neutral-500 mt-3 font-medium">Đăng nhập để tiếp tục trải nghiệm cùng <span class="text-primary-600 font-bold">Resto<span class="text-neutral-900">MS</span></span>.</p>
            </div>

            <?php if (!empty($errorMessage)): ?>
                <div class="mb-8 rounded-2xl border border-red-100 bg-red-50 p-5 text-sm text-red-700 flex items-center gap-3 animate-shake shadow-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-red-500"></i>
                    <p class="font-bold"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo url('/login'); ?>" class="space-y-8">
                <div class="space-y-3">
                    <label for="identity" class="block text-sm font-bold text-neutral-700 ml-1">Tên đăng nhập / Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input
                            id="identity"
                            name="identity"
                            type="text"
                            value="<?php echo htmlspecialchars($identity, ENT_QUOTES, 'UTF-8'); ?>"
                            class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                            placeholder="Nhập tài khoản"
                            required
                        >
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="text-sm font-bold text-neutral-700">Mật khẩu</label>
                        <a href="#" class="text-xs font-bold text-primary-500 hover:text-primary-600 tracking-tight">Quên mật khẩu?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-neutral-400 group-focus-within:text-primary-500 transition-colors">
                            <i data-lucide="key-round" class="w-5 h-5"></i>
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="w-full rounded-2xl border border-neutral-200 bg-neutral-50/50 pl-12 pr-5 py-4.5 outline-none focus:bg-white focus:ring-4 focus:ring-primary-500/10 focus:border-primary-500 transition-all font-medium"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <div class="flex items-center ml-1">
                    <input id="remember" type="checkbox" class="w-5 h-5 text-primary-500 border-neutral-300 rounded-lg focus:ring-primary-500 cursor-pointer shadow-sm">
                    <label for="remember" class="ml-3 text-sm font-bold text-neutral-500 cursor-pointer select-none">Ghi nhớ tôi</label>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-neutral-900 text-white py-5 font-bold text-lg hover:bg-primary-600 transition-all shadow-xl active:scale-[0.98]"
                >
                    Đăng nhập ngay
                </button>
            </form>

            <div class="mt-12 pt-8 border-t border-neutral-50 text-center">
                <p class="text-neutral-500 text-sm font-medium">
                    Chưa có tài khoản? 
                    <a href="#" class="font-bold text-primary-500 hover:text-primary-600 ml-1">Đăng ký thành viên</a>
                </p>
            </div>
        </div>
    </div>
</section>
