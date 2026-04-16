<?php
$pageTitle = isset($title) ? $title : 'Quản trị | RestoMS';
$currentUser = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="vi" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?> | RestoMS</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                            950: '#431407',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body { @apply font-sans antialiased text-slate-900; }
            h1, h2, h3, h4, h5, h6 { @apply font-outfit; }
        }
        @layer components {
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        }
    </style>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="h-full overflow-hidden">
    <div class="flex h-full overflow-hidden bg-slate-50">
        <!-- Sidebar Navigation -->
        <?php require VIEW_PATH . '/partials/admin/sidebar.php'; ?>

        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <!-- Top Header -->
            <?php require VIEW_PATH . '/partials/admin/header.php'; ?>

            <!-- Main Content Area -->
            <main id="admin-main-content" class="flex-1 overflow-y-auto p-4 md:p-6">
                <div class="max-w-[1400px] mx-auto w-full animate-fade-in">
                    <?php echo $content; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        const sidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebarContent = document.getElementById('sidebar-content');
        
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
