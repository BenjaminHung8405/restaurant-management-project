

<div class="flex flex-col space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Quản lý Nhân sự</h1>
            <p class="text-gray-600 text-sm mt-1">Quản lý tài khoản nhân viên và phân quyền truy cập hệ thống.</p>
        </div>
        <a href="<?= url('/admin/users/create') ?>" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center transition duration-200">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
            Thêm Nhân viên
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Họ và Tên</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên Đăng Nhập</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vai Trò</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng Thái</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày Tạo</th>
                    <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao Tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                            </div>
                            <span class="font-medium text-gray-900"><?= htmlspecialchars($user['full_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 font-mono text-sm leading-relaxed">
                        <?= htmlspecialchars($user['username']) ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        $roleColors = [
                            'admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                            'cashier' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'kitchen' => 'bg-orange-100 text-orange-700 border-orange-200'
                        ];
                        $roleLabels = [
                            'admin' => 'Admin',
                            'cashier' => 'Thu ngân',
                            'kitchen' => 'Bếp'
                        ];
                        $color = $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                        $label = $roleLabels[$user['role']] ?? $user['role'];
                        ?>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium border <?= $color ?>">
                            <?= $label ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm leading-relaxed">
                        <?php if ($user['status'] === 'active'): ?>
                            <span class="flex items-center text-green-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600 mr-2"></span>
                                Hoạt động
                            </span>
                        <?php else: ?>
                            <span class="flex items-center text-gray-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-2"></span>
                                Khóa
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm leading-relaxed">
                        <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-2">
                            <?php 
                            $canManage = false;
                            if ($currentUser && $user['id'] !== $currentUser['id']) {
                                $currPrio = $rolePriority[$currentUser['role']] ?? 0;
                                $targetPrio = $rolePriority[$user['role']] ?? 0;
                                if ($currPrio > $targetPrio) {
                                    $canManage = true;
                                }
                            }
                            ?>
                            <?php if ($canManage): ?>
                                <a href="<?= url('/admin/users/edit?id=' . $user['id']) ?>" 
                                   class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                   title="Chỉnh sửa">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="<?= url('/admin/users/toggle-status?id=' . $user['id']) ?>" method="POST" class="inline">
                                    <button type="submit" 
                                            class="p-2 <?= $user['status'] === 'active' ? 'text-red-500 hover:bg-red-50' : 'text-green-500 hover:bg-green-50' ?> rounded-lg transition"
                                            title="<?= $user['status'] === 'active' ? 'Khóa' : 'Mở khóa' ?>">
                                        <i data-lucide="<?= $user['status'] === 'active' ? 'lock' : 'unlock' ?>" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-300 p-2 italic text-xs">Không có quyền</span>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


