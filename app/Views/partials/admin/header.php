<?php
$currentUser = $_SESSION['user'] ?? null;
?>
<header class="sticky top-0 z-[1030] h-16 flex-shrink-0 flex items-center justify-between px-4 md:px-6 bg-white border-b border-slate-200">
    <!-- Left: Sidebar Toggle (Mobile) + Brand/Page Title -->
    <div class="flex items-center gap-3">
        <button type="button" onclick="openSidebar()" aria-label="Mở menu điều hướng" 
                class="md:hidden p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition-colors">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
        
        <div class="hidden md:flex items-center gap-2 text-slate-400">
            <span class="text-sm font-medium hover:text-slate-600 transition-colors">RestoMS Admin</span>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
            <span class="text-sm font-bold text-slate-800 tracking-tight"><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        
        <div class="md:hidden font-bold text-slate-800 px-1">
            <?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>

    <!-- Right: Notifications + User Actions -->
    <div class="flex items-center gap-2">
        <!-- Notification Bell -->
        <div class="relative group">
            <button class="p-2 rounded-xl text-slate-500 hover:text-primary-600 hover:bg-primary-50 transition-all">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>

        <div class="h-6 w-px bg-slate-200 mx-1"></div>

        <!-- User Dropdown (Simplified for move) -->
        <div class="flex items-center gap-3 pl-2 pr-1 py-1 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group">
            <div class="flex flex-col items-end hidden sm:flex text-right">
                <span class="text-xs font-bold text-slate-800 leading-tight truncate max-w-[120px]">
                    <?php echo htmlspecialchars($currentUser['full_name'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?>
                </span>
                <span class="text-[10px] text-slate-400 leading-tight">
                    <?php echo htmlspecialchars($currentUser['role'] ?? 'Quản trị viên', ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </div>
            
            <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-primary-600 group-hover:bg-primary-50 group-hover:border-primary-100 transition-all">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>
        </div>
    </div>
</header>
