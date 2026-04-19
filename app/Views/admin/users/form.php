

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="<?= url('/admin/users') ?>" class="text-indigo-600 hover:text-indigo-700 flex items-center text-sm font-medium mb-4">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
            Quay lại danh sách
        </a>
        <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
        <p class="text-gray-600 text-sm mt-1"><?= $user ? 'Cập nhật thông tin nhân viên.' : 'Tạo tài khoản mới cho nhân viên.' ?></p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="<?= $user ? url('/admin/users/update?id=' . $user['id']) : url('/admin/users/store') ?>" 
              method="POST" class="p-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Họ và Tên <span class="text-red-500">*</span></label>
                    <input type="text" name="full_name" value="<?= $user['full_name'] ?? '' ?>" required
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none"
                           placeholder="VD: Nguyễn Văn A">
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên Đăng Nhập <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="<?= $user['username'] ?? '' ?>" <?= $user ? 'disabled' : 'required' ?>
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none <?= $user ? 'bg-gray-50' : '' ?>"
                           placeholder="VD: nguyenvana">
                    <?php if ($user): ?>
                    <p class="text-xs text-gray-400 mt-1">Không thể thay đổi tên đăng nhập.</p>
                    <?php endif; ?>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Vai Trò <span class="text-red-500">*</span></label>
                    <select name="role" required
                            class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none">
                        <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="cashier" <?= ($user['role'] ?? 'cashier') === 'cashier' ? 'selected' : '' ?>>Thu ngân</option>
                        <option value="waiter" <?= ($user['role'] ?? '') === 'waiter' ? 'selected' : '' ?>>Phục vụ</option>
                        <option value="kitchen" <?= ($user['role'] ?? '') === 'kitchen' ? 'selected' : '' ?>>Bếp</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Mật Khẩu <?= $user ? '(Để trống nếu không muốn thay đổi)' : '<span class="text-red-500">*</span>' ?>
                    </label>
                    <input type="password" name="password" <?= $user ? '' : 'required' ?>
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition outline-none"
                           placeholder="<?= $user ? '••••••••' : 'Nhập mật khẩu cho tài khoản' ?>">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-sm transition duration-200 flex items-center">
                    <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                    <?= $user ? 'Lưu thay đổi' : 'Tạo tài khoản' ?>
                </button>
            </div>
        </form>
    </div>
</div>


