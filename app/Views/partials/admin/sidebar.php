<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$sidebarItems = [
    ['label' => 'Bảng điều khiển', 'href' => url('/admin'),        'icon' => 'layout-dashboard', 'active_pattern' => '/\/admin$/'],
    ['label' => 'Đơn hàng',        'href' => url('/admin/orders'), 'icon' => 'shopping-cart',    'active_pattern' => '/\/admin\/orders/'],
    ['label' => 'Nhà bếp',         'href' => url('/admin/kitchen'), 'icon' => 'chef-hat',        'active_pattern' => '/\/admin\/kitchen/'],
    ['label' => 'Đặt Bàn',        'href' => url('/admin/reservations'), 'icon' => 'calendar',    'active_pattern' => '/\/admin\/reservations/'],
    ['label' => 'Danh mục',        'href' => url('/admin/categories'), 'icon' => 'layers',        'active_pattern' => '/\/admin\/categories/'],
    ['label' => 'Bàn ăn',          'href' => url('/admin/tables'), 'icon' => 'table-2',          'active_pattern' => '/\/admin\/tables/'],
    ['label' => 'Người dùng',      'href' => '#',                  'icon' => 'users',            'active_pattern' => '/\/admin\/users/'],
    ['label' => 'Cài đặt',         'href' => '#',                  'icon' => 'settings',         'active_pattern' => '/\/admin\/settings/'],
];
?>

<!-- Desktop Sidebar -->
<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-[1050] w-64 h-full bg-white border-r border-slate-200 transition-transform duration-300 md:relative md:translate-x-0 -translate-x-full">
    <div class="flex flex-col h-full bg-white">
        <!-- Sidebar Logo -->
        <div class="flex items-center gap-2.5 px-6 py-4 h-16 border-b border-slate-100 flex-shrink-0">
            <div class="flex items-center justify-center p-2 rounded-lg bg-orange-500 shadow-sm flex-shrink-0">
                <i data-lucide="utensils-crossed" class="w-4.5 h-4.5 text-white"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-slate-900 leading-tight truncate">RestoMS</p>
                <p class="text-[11px] text-slate-500 leading-tight truncate font-medium uppercase tracking-wider">Admin Panel</p>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 overflow-y-auto py-4 no-scrollbar">
            <ul class="flex flex-col gap-1 px-3" role="list">
                <?php foreach ($sidebarItems as $item): ?>
                    <?php 
                        $isActive = preg_match($item['active_pattern'], $currentPath); 
                        $isPlaceholder = $item['href'] === '#';
                    ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>" 
                           class="group relative flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?php echo $isActive ? 'bg-primary-50 text-primary-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'; ?> <?php echo $isPlaceholder ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                            
                            <?php if ($isActive): ?>
                                <!-- Active accent -->
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-primary-500 rounded-r-full -ml-3"></div>
                            <?php endif; ?>

                            <i data-lucide="<?php echo $item['icon']; ?>" 
                               class="w-5 h-5 flex-shrink-0 transition-colors <?php echo $isActive ? 'text-primary-600' : 'text-slate-400 group-hover:text-slate-600'; ?>"></i>
                            
                            <span class="truncate"><?php echo $item['label']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-100 flex-shrink-0">
            <div class="bg-slate-50 rounded-xl p-3 flex items-center gap-3 border border-slate-100">
                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-xs font-bold ring-2 ring-primary-50">
                    <?php 
                        $initials = '';
                        if ($currentUser && isset($currentUser['full_name'])) {
                            $words = explode(' ', $currentUser['full_name']);
                            $initials = strtoupper(mb_substr($words[0] ?? '', 0, 1) . mb_substr(end($words) ?? '', 0, 1));
                        }
                        echo $initials ?: 'AD';
                    ?>
                </div>
                <div class="min-w-0 flex-1 hide-on-collapse">
                    <p class="text-xs font-bold text-slate-900 truncate"><?php echo htmlspecialchars($currentUser['full_name'] ?? 'Admin User', ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="text-[10px] text-slate-500 truncate"><?php echo htmlspecialchars($currentUser['role'] ?? 'Quản trị viên', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <a href="<?php echo url('/logout'); ?>" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors" title="Đăng xuất">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </a>
            </div>
            <p class="mt-4 text-[10px] text-slate-300 text-center select-none">© <?php echo date('Y'); ?> RestoMS v1.0</p>
        </div>
    </div>
</aside>

<!-- Sidebar Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-[1040] bg-slate-900/40 backdrop-blur-sm hidden md:hidden" onclick="closeSidebar()"></div>
