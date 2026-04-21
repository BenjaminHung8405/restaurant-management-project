<div class="space-y-6 relative">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900">Điều hành POS & Sơ đồ Bàn</h1>
            <p class="text-slate-500 mt-1">Quản lý gọi món và thanh toán thời gian thực.</p>
        </div>
        <div class="flex items-center gap-3">
            <div id="refresh-status" class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-500 shadow-sm transition-all duration-300">
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                <span>Tự động cập nhật...</span>
            </div>
            <button onclick="openAddTableModal()" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all active:scale-95 shadow-lg shadow-indigo-100">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Thêm bàn
            </button>
            <button onclick="fetchTableStatus()" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-primary-600 hover:border-primary-200 hover:bg-primary-50 transition-all active:scale-95 shadow-sm" title="Làm mới ngay">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Legend -->
    <div class="flex flex-wrap items-center gap-6 px-4 py-3 bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Trống</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Đang phục vụ</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.4)]"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Đặt trước</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-slate-400 shadow-[0_0_8px_rgba(148,163,184,0.4)]"></div>
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Dọn dẹp</span>
        </div>
        <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
        <div class="text-[10px] font-bold text-slate-400 italic">
            <span>* Click vào bàn để mở POS Panel</span>
        </div>
    </div>

    <!-- Table Grid -->
    <div id="table-grid" class="grid table-grid-responsive">
        <?php if (empty($tables)): ?>
            <!-- Skeleton Loaders -->
            <?php for($i=0; $i<10; $i++): ?>
            <div class="aspect-square bg-slate-200/50 rounded-[2.5rem] animate-pulse"></div>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>

<!-- POS Offcanvas Panel (Teleported to Body via JS) -->
<div id="pos-panel" class="fixed inset-0 z-[2000] invisible pointer-events-none overflow-hidden transition-all duration-500">
    <!-- Backdrop -->
    <div id="pos-backdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md opacity-0 transition-opacity duration-500 cursor-pointer" onclick="closePosPanel()"></div>
    
    <!-- Panel Content -->
    <div id="pos-content" class="absolute right-0 top-0 bottom-0 w-full bg-white shadow-2xl translate-x-full transition-transform duration-500 ease-out flex flex-col pointer-events-auto">
        <!-- Panel Header -->
        <div class="h-20 px-8 bg-white border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-6">
                <button onclick="closePosPanel()" class="group p-3 hover:bg-slate-100 rounded-2xl text-slate-400 hover:text-slate-900 transition-all active:scale-95">
                    <i data-lucide="arrow-left" class="w-6 h-6 group-hover:-translate-x-1 transition-transform"></i>
                </button>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-black text-slate-900" id="pos-table-title">Bàn --</h2>
                        <span id="pos-badge-status" class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-[10px] font-black uppercase tracking-tighter">Đang tải...</span>
                    </div>
                    <p class="text-xs text-slate-400 font-bold mt-0.5" id="pos-order-status">Khởi tạo phiên làm việc...</p>
                </div>
            </div>
            
            <div id="pos-timer-container" class="hidden items-center gap-3 px-4 py-2 bg-rose-50 text-rose-600 rounded-2xl text-xs font-black ring-1 ring-rose-200">
                <div class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></div>
                <span id="pos-timer">00:00:00</span>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden">
            <!-- Left: Menu Area (Flex-1 with MIN-W-0 to prevent overflow) -->
            <div class="flex-1 min-w-0 flex flex-col border-r border-slate-100 bg-white">
                <!-- Search & Filters -->
                <div class="px-8 py-6 space-y-6 shrink-0 bg-slate-50/50">
                    <div class="relative group">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-primary-500 transition-colors"></i>
                        <input type="text" id="menu-search" placeholder="Số thứ tự, tên món hoặc từ khoá..." oninput="handleMenuFilter()"
                            class="w-full pl-12 pr-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-primary-100 focus:border-primary-300 outline-none transition-all shadow-sm">
                    </div>
                    <div id="category-filters" class="flex gap-3 overflow-x-auto no-scrollbar pb-1">
                        <!-- Dynamic Categories -->
                    </div>
                </div>

                <!-- Menu Grid -->
                <div id="menu-grid" class="flex-1 overflow-y-auto px-8 pb-8 pt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4 align-content-start no-scrollbar">
                    <!-- Dynamic Menu Items -->
                </div>
            </div>

            <!-- Right: Cart Area (Fixed width to remain visible) -->
            <div class="w-full lg:w-[400px] flex flex-col bg-slate-50/30 shrink-0">
                <div class="px-8 py-6 pb-2 shrink-0">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">Giỏ hàng</h3>
                        <button onclick="clearCart()" class="text-[10px] font-black text-rose-500 hover:text-rose-600 uppercase tracking-widest bg-rose-50 px-3 py-1.5 rounded-xl transition-colors">Xoá sạch</button>
                    </div>
                </div>

                <!-- Cart Items -->
                <div id="cart-container" class="flex-1 overflow-y-auto px-6 space-y-4 no-scrollbar">
                    <!-- Dynamic Cart Items -->
                </div>

                <!-- Summary & Actions -->
                <div class="p-8 bg-white border-t border-slate-100 space-y-6 shadow-[0_-12px_40px_rgba(0,0,0,0.03)]">
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs font-bold text-slate-400">
                            <span>TẠM TÍNH</span>
                            <span id="cart-subtotal" class="text-slate-600">0₫</span>
                        </div>
                        <div class="flex justify-between items-end pt-2">
                            <span class="text-xs font-black text-slate-900 uppercase tracking-widest">TỔNG CỘNG</span>
                            <span id="cart-total" class="text-3xl font-black text-primary-600 tabular-nums">0₫</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <button onclick="saveCurrentOrder()" id="btn-save-order" class="flex items-center justify-center gap-2 py-4 bg-slate-900 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-95 disabled:opacity-30 disabled:pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 inline-block text-orange-400">
                                <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 00-1.071-.136 9.742 9.742 0 00-3.539 6.177A7.547 7.547 0 016.648 6.87a.75.75 0 00-1.152-.082A9 9 0 1015.68 4.534a7.46 7.46 0 01-2.717-2.248z" clip-rule="evenodd" />
                            </svg>
                            Lưu & Gửi Bếp
                        </button>
                        <?php if (($_SESSION['user']['role'] ?? '') !== 'waiter'): ?>
                        <button onclick="handleCheckout()" id="btn-checkout" class="flex flex-col items-center justify-center gap-1 py-4 bg-primary-600 text-white rounded-[2rem] font-black text-[10px] uppercase tracking-widest hover:bg-primary-700 transition-all active:scale-95 shadow-xl shadow-primary-200 disabled:opacity-30 disabled:pointer-events-none">
                            <i data-lucide="credit-card" class="w-5 h-5 mb-1"></i>
                            Thanh Toán
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals (Teleported) -->
<div id="reserve-modal" class="fixed inset-0 z-[2001] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModals()"></div>
    <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden transition-all duration-300 scale-95 opacity-0">
        <div class="p-10 text-center">
            <div class="w-24 h-24 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <i data-lucide="calendar-check" class="w-12 h-12"></i>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-3">Lịch hẹn</h3>
            <p id="reserve-info" class="text-slate-500 mb-10 leading-relaxed font-medium"></p>
            <div class="grid grid-cols-2 gap-4">
                <button onclick="closeModals()" class="py-5 bg-slate-100 text-slate-500 rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">Đóng</button>
                <button onclick="checkInReservedTable()" class="py-5 bg-primary-600 text-white rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-primary-700 transition-all shadow-xl shadow-primary-100">Nhận bàn</button>
            </div>
        </div>
    </div>
</div>

<div id="cleaning-modal" class="fixed inset-0 z-[2001] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeModals()"></div>
    <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-sm overflow-hidden transition-all duration-300 scale-95 opacity-0">
        <div class="p-10 text-center">
            <div class="w-24 h-24 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
                <i data-lucide="brush" class="w-12 h-12"></i>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-3">Vệ sinh bàn</h3>
            <p class="text-slate-500 mb-10 font-medium">Bàn đang được vệ sinh chuẩn bị cho lượt khách tiếp theo.</p>
            <button onclick="markTableCleaned()" class="w-full py-5 bg-emerald-600 text-white rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100">
                Làm mới bàn
            </button>
        </div>
    </div>
</div>

<div id="add-table-modal" class="fixed inset-0 z-[2001] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" onclick="closeAddTableModal()"></div>
    <div class="relative bg-white rounded-[3rem] shadow-2xl w-full max-w-md overflow-hidden transition-all duration-300 scale-95 opacity-0">
        <form id="form-add-table" onsubmit="saveNewTable(event)" class="p-10">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                <i data-lucide="layout-grid" class="w-10 h-10"></i>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mb-2 text-center">Thêm bàn mới</h3>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-10 text-center">Thiết lập thông số bàn</p>
            
            <div class="space-y-6 mb-10">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Số hiệu bàn</label>
                    <input type="text" name="table_number" required placeholder="Ví dụ: T1-11, VIP-05..." 
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-indigo-100 focus:border-indigo-300 outline-none transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Sức chứa (Người)</label>
                    <input type="number" name="capacity" required min="1" value="4" 
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-indigo-100 focus:border-indigo-300 outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button type="button" onclick="closeAddTableModal()" class="py-5 bg-slate-100 text-slate-500 rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all active:scale-95">Hủy bỏ</button>
                <button type="submit" id="btn-submit-add-table" class="py-5 bg-indigo-600 text-white rounded-3xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 active:scale-95">Lưu bàn</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Teleport Logic: Move POS and Modals to document.body to escape transform/z-index issues
    window.addEventListener('DOMContentLoaded', () => {
        const items = ['pos-panel', 'reserve-modal', 'cleaning-modal', 'add-table-modal'];
        items.forEach(id => {
            const el = document.getElementById(id);
            if (el) document.body.appendChild(el);
        });
    });

    // State Machine
    let currentTableSession = { tableId: null, orderId: null, tableNumber: null, status: null, reservationId: null };
    let cart = [];
    let menuData = { categories: [], items: [] };
    let activeCategoryId = 'all';

    const STATUS_MAP = {
        'available': { label: 'Trống', color: 'emerald', icon: 'user-plus' },
        'occupied': { label: 'Đang dùng', color: 'rose', icon: 'utensils' },
        'reserved': { label: 'Đặt trước', color: 'yellow', icon: 'calendar-check' },
        'cleaning': { label: 'Dọn dẹp', color: 'slate', icon: 'brush' }
    };

    // --- Core UI Logic ---

    async function fetchTableStatus() {
        const refreshEl = document.getElementById('refresh-status');
        refreshEl?.classList.add('scale-105', 'border-primary-200');
        
        try {
            const response = await fetch('<?= url('/admin/tables/api/status') ?>');
            const data = await response.json();
            renderTableGrid(data);
        } catch (error) {
            console.error('Error fetching table status:', error);
        } finally {
            setTimeout(() => {
                refreshEl?.classList.remove('scale-105', 'border-primary-200');
            }, 500);
        }
    }

    function renderTableGrid(tables) {
        const grid = document.getElementById('table-grid');
        
        // Remove skeletons on first real data load
        if (grid.querySelector('.animate-pulse')) {
            grid.innerHTML = '';
        }

        tables.forEach((table, idx) => {
            // Determine active status logic matching the current STATUS_MAP
            let status = table.active_status ? 'occupied' : (table.reservation_guest ? 'reserved' : table.table_status);
            if (!STATUS_MAP[status]) status = 'available';

            const config = STATUS_MAP[status];
            const badgeColor = status === 'available' ? 'bg-emerald-500' : (status === 'occupied' ? 'bg-rose-500' : (status === 'reserved' ? 'bg-yellow-500' : 'bg-slate-400'));
            
            let tableDiv = document.getElementById(`table-${table.id}`);
            
            if (!tableDiv) {
                // Initial creation
                tableDiv = document.createElement('div');
                tableDiv.id = `table-${table.id}`;
                tableDiv.setAttribute('data-current-status', status);
                tableDiv.onclick = () => handleTableClick(table);
                tableDiv.className = `table-card group relative aspect-square min-w-0 flex flex-col items-center justify-center border-2 border-${config.color}-100 bg-white rounded-[3rem] transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:border-${config.color}-300 cursor-pointer overflow-hidden active:scale-95 animate-[modal-in_0.5s_ease-out_forwards] opacity-0`;
                tableDiv.style.animationDelay = `${idx * 50}ms`;
                tableDiv.innerHTML = getTableInnerHTML(table, status, config, badgeColor);
                grid.appendChild(tableDiv);
            } else {
                // Smart Update: Only update if status changed
                const currentStatus = tableDiv.getAttribute('data-current-status');
                if (currentStatus !== status) {
                    tableDiv.setAttribute('data-current-status', status);
                    
                    // Update classes for color changes
                    tableDiv.className = `table-card group relative aspect-square min-w-0 flex flex-col items-center justify-center border-2 border-${config.color}-100 bg-white rounded-[3rem] transition-all duration-500 hover:scale-[1.02] hover:shadow-2xl hover:border-${config.color}-300 cursor-pointer overflow-hidden active:scale-95 ${status === 'occupied' ? 'ring-4 ring-rose-500/5' : ''}`;
                    
                    // Update content
                    tableDiv.innerHTML = getTableInnerHTML(table, status, config, badgeColor);
                }
                
                // Always update the click handler to ensure it has the latest data from the API response
                tableDiv.onclick = () => handleTableClick(table);
            }
        });
        
        // Re-initialize Lucide icons for all elements (efficiently handles updated nodes)
        lucide.createIcons();
    }

    /**
     * Helper to generate the premium inner HTML for a table card
     */
    function getTableInnerHTML(table, status, config, badgeColor) {
        const tableNumberDisplay = table.table_number.includes('-') 
            ? table.table_number.split('-').pop() 
            : table.table_number;

        return `
            <div class="table-card-watermark absolute -top-6 -right-6 font-black text-slate-900/[0.03] select-none pointer-events-none group-hover:scale-110 transition-transform duration-700">
                ${tableNumberDisplay}
            </div>

            <div class="table-card-icon-wrap text-${config.color}-500 bg-${config.color}-50 shadow-sm transition-all duration-300 group-hover:bg-white group-hover:shadow-md">
                <i data-lucide="${config.icon}" class="table-card-icon"></i>
            </div>

            <div class="table-card-meta text-center max-w-full">
                <h3 class="table-card-number font-black text-slate-800 tracking-tight">${table.table_number}</h3>
                <div class="table-card-capacity flex items-center justify-center gap-1.5 mt-1.5 text-slate-400 font-bold uppercase tracking-widest">
                    <i data-lucide="users" class="table-card-capacity-icon"></i>
                    ${table.capacity} khách
                </div>
            </div>

            <div class="table-card-badge absolute left-1/2 -translate-x-1/2 whitespace-nowrap ${badgeColor} text-white font-black uppercase tracking-[0.1em] shadow-lg shadow-${config.color}-100 transition-all duration-300">
                ${config.label}
            </div>
        `;
    }

    // --- POS Logic ---

    async function handleTableClick(table) {
        const status = table.active_status ? 'occupied' : (table.reservation_guest ? 'reserved' : table.table_status);
        
        currentTableSession = {
            tableId: table.id,
            orderId: table.order_id,
            tableNumber: table.table_number,
            status: status,
            reservationId: table.reservation_id
        };

        if (status === 'reserved') {
            document.getElementById('reserve-info').innerText = `Khách hàng "${table.reservation_guest}" đã đặt bàn thành công vào lúc ${table.reservation_time}.`;
            showModal('reserve-modal');
        } else if (status === 'cleaning') {
            showModal('cleaning-modal');
        } else {
            openPosPanel();
        }
    }

    async function openPosPanel() {
        const panel = document.getElementById('pos-panel');
        const content = document.getElementById('pos-content');
        const backdrop = document.getElementById('pos-backdrop');
        
        document.getElementById('pos-table-title').innerText = currentTableSession.tableNumber;
        document.getElementById('pos-badge-status').innerText = currentTableSession.orderId ? 'Phục vụ' : 'Bàn trống';
        document.getElementById('pos-badge-status').className = `px-3 py-1 ${currentTableSession.orderId ? 'bg-rose-500 text-white' : 'bg-emerald-500 text-white'} rounded-full text-[10px] font-black uppercase tracking-tighter`;
        document.getElementById('pos-order-status').innerText = currentTableSession.orderId ? 'Đơn hàng đang thực thi' : 'Phiên phục vụ mới';
        
        panel.classList.remove('invisible');
        setTimeout(() => {
            content.classList.remove('translate-x-full');
            backdrop.classList.add('opacity-100');
        }, 10);

        loadPosData();
    }

    async function loadPosData() {
        const [menuRes, orderRes] = await Promise.all([
            fetch('<?= url('/admin/api/pos/menu') ?>').then(r => r.json()),
            currentTableSession.tableId ? fetch(`<?= url('/admin/api/pos/order') ?>?table_id=${currentTableSession.tableId}`).then(r => r.json()) : { items: [] }
        ]);

        menuData = menuRes;
        cart = (orderRes.items || []).map(item => ({
            id: item.menu_item_id,
            name: item.menu_item_name,
            price: parseFloat(item.unit_price),
            quantity: parseInt(item.quantity),
            status: item.status,
            image_url: item.image_url,
            notes: item.notes || ''
        }));
        
        if (orderRes.order) {
            currentTableSession.orderId = orderRes.order.id;
        }

        renderCategories();
        renderMenu();
        updateCartUI();
    }

    function renderCategories() {
        const container = document.getElementById('category-filters');
        const cats = [{ id: 'all', name: 'Thực đơn' }, ...menuData.categories];
        
        container.innerHTML = cats.map(cat => `
            <button onclick="filterCategory('${cat.id}')" 
                class="px-6 py-3 rounded-2xl text-[10px] uppercase tracking-widest font-black whitespace-nowrap transition-all ${activeCategoryId === cat.id ? 'bg-slate-900 text-white shadow-xl' : 'bg-white text-slate-400 hover:text-slate-900 hover:bg-white border border-slate-100 shadow-sm'}">
                ${cat.name}
            </button>
        `).join('');
    }

    function renderMenu(filterWord = '') {
        const grid = document.getElementById('menu-grid');
        const filtered = menuData.items.filter(item => {
            const matchesCategory = activeCategoryId === 'all' || item.category_id === activeCategoryId;
            const matchesSearch = item.name.toLowerCase().includes(filterWord.toLowerCase());
            return matchesCategory && matchesSearch;
        });

        grid.innerHTML = filtered.map(item => `
            <div onclick="addToCart(${JSON.stringify(item).replace(/"/g, '&quot;')})" 
                 class="group bg-white border border-slate-100 rounded-[2rem] p-4 shadow-sm hover:shadow-2xl hover:border-primary-100 transition-all cursor-pointer active:scale-95 flex flex-col items-center text-center">
                <div class="aspect-square w-full rounded-[1.5rem] overflow-hidden mb-4 bg-slate-50 relative">
                    <img src="${item.image_url || 'https://via.placeholder.com/150'}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-primary-600/0 group-hover:bg-primary-600/10 transition-colors"></div>
                </div>
                <h4 class="font-black text-slate-800 text-xs line-clamp-2 px-1 leading-relaxed h-[2.5em] flex items-center justify-center">${item.name}</h4>
                <div class="mt-3 px-3 py-1.5 bg-slate-50 rounded-xl group-hover:bg-primary-50 transition-colors">
                    <p class="text-primary-600 font-black text-xs tabular-nums">${new Intl.NumberFormat('vi-VN').format(item.price)}₫</p>
                </div>
            </div>
        `).join('');
    }

    // --- Cart Actions ---

    function addToCart(item) {
        // Find existing pending item with same id and EMPTY notes (do not merge with locked items)
        const existing = cart.find(c => c.id === (item.id || item.menu_item_id) && (c.notes || '') === '' && (!c.status || c.status === 'pending'));
        if (existing) {
            existing.quantity++;
        } else {
            cart.unshift({
                id: item.id || item.menu_item_id,
                name: item.name,
                price: parseFloat(item.price),
                quantity: 1,
                status: 'pending',
                image_url: item.image_url,
                notes: ''
            });
        }
        updateCartUI();
    }

    function updateQuantity(index, delta) {
        const item = cart[index];
        if (!item) return;
        
        item.quantity += delta;
        if (item.quantity <= 0) {
            cart.splice(index, 1);
        }
        updateCartUI();
    }

    function updateItemNote(index, note) {
        const item = cart[index];
        if (!item) return;
        
        item.notes = note.trim();
        // Merge items with same ID and notes if applicable, and ensure target is pending
        const matchingIndex = cart.findIndex((c, i) => i !== index && c.id === item.id && (c.notes || '') === item.notes && (!c.status || c.status === 'pending'));
        if (matchingIndex !== -1) {
            cart[matchingIndex].quantity += item.quantity;
            cart.splice(index, 1);
            updateCartUI();
        }
    }

    function clearCart() {
        if (confirm('Làm trống giỏ hàng này?')) {
            cart = [];
            updateCartUI();
        }
    }

    function updateCartUI() {
        const container = document.getElementById('cart-container');
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        const saveBtn = document.getElementById('btn-save-order');
        const checkoutBtn = document.getElementById('btn-checkout');

        if (cart.length === 0) {
            container.innerHTML = `
                <div id="cart-empty" class="py-20 text-center animate-[modal-in_0.5s_ease-out]">
                    <div class="w-20 h-20 bg-white border-2 border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 shadow-sm">
                        <i data-lucide="shopping-basket" class="w-10 h-10"></i>
                    </div>
                    <p class="text-xs font-black text-slate-300 uppercase tracking-widest">Đang trống</p>
                </div>
            `;
            saveBtn.disabled = true;
            if (checkoutBtn) checkoutBtn.disabled = true;
            subtotalEl.innerText = '0₫';
            totalEl.innerText = '0₫';
            lucide.createIcons();
            return;
        }

        saveBtn.disabled = false;
        if (checkoutBtn) checkoutBtn.disabled = !currentTableSession.orderId;

        let total = 0;
        container.innerHTML = cart.map((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;
            const isLocked = item.status === 'cooking' || item.status === 'done';
            const safeNotes = (item.notes || '').replace(/"/g, '&quot;');
            
            return `
                <div class="group flex flex-col gap-3 bg-white p-4 rounded-[1.75rem] border border-slate-100 shadow-sm hover:shadow-md transition-all animate-[modal-in_0.3s_ease-out] ${isLocked ? 'bg-slate-50/50 opacity-90' : ''}">
                    <!-- Main Item Info -->
                    <div class="flex items-center gap-4">
                        <img src="${item.image_url}" class="w-14 h-14 rounded-2xl object-cover shrink-0 shadow-sm border border-slate-50">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h5 class="font-black text-slate-800 text-xs truncate">${item.name}</h5>
                                ${item.status === 'cooking' ? '<span class="px-2 py-0.5 bg-amber-100 text-amber-600 rounded-md text-[8px] font-black uppercase tracking-tighter shrink-0 animate-pulse">Đang nấu</span>' : ''}
                                ${item.status === 'done' ? '<span class="px-2 py-0.5 bg-emerald-100 text-emerald-600 rounded-md text-[8px] font-black uppercase tracking-tighter shrink-0">Xong</span>' : ''}
                            </div>
                            <p class="text-[10px] text-primary-600 font-black mt-1 tabular-nums">${new Intl.NumberFormat('vi-VN').format(item.price)}₫</p>
                        </div>
                        <div class="flex items-center gap-1.5 bg-slate-50 p-1 rounded-2xl border border-slate-100">
                            <button onclick="updateQuantity(${index}, -1)" ${isLocked ? 'disabled' : ''} 
                                class="w-8 h-8 flex items-center justify-center bg-white rounded-xl text-slate-400 hover:text-rose-500 shadow-sm transition-all active:scale-90 disabled:opacity-30 disabled:pointer-events-none">
                                <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                            </button>
                            <span class="w-8 text-center text-xs font-black text-slate-800 tabular-nums">${item.quantity}</span>
                            <button onclick="updateQuantity(${index}, 1)" ${isLocked ? 'disabled' : ''} 
                                class="w-8 h-8 flex items-center justify-center bg-white rounded-xl text-slate-400 hover:text-emerald-500 shadow-sm transition-all active:scale-90 disabled:opacity-30 disabled:pointer-events-none">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Notes Input -->
                    <div class="flex items-center relative">
                        <i data-lucide="pen-line" class="w-4 h-4 text-slate-400 absolute ml-3 pointer-events-none"></i>
                        <input type="text" placeholder="Ghi chú món (VD: ít cay, không hành...)" value="${safeNotes}" onchange="updateItemNote(${index}, this.value)" ${isLocked ? 'disabled' : ''} 
                            class="w-full text-xs font-medium pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 placeholder-slate-400 text-slate-700 transition-all ${isLocked ? 'opacity-50 cursor-not-allowed' : 'hover:border-slate-300'}">
                    </div>
                </div>
            `;
        }).join('');

        subtotalEl.innerText = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        totalEl.innerText = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        lucide.createIcons();
    }

    // --- API Handlers ---

    async function saveCurrentOrder() {
        const btn = document.getElementById('btn-save-order');
        btn.disabled = true;
        const oldContent = btn.innerHTML;
        btn.innerHTML = `<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i>`;
        lucide.createIcons();
        
        try {
            const res = await fetch('<?= url('/admin/api/pos/order/save') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    table_id: currentTableSession.tableId,
                    items: cart
                })
            });
            const data = await res.json();
            if (data.success) {
                currentTableSession.orderId = data.order_id;
                document.getElementById('pos-badge-status').innerText = 'Phục vụ';
                document.getElementById('pos-badge-status').className = `px-3 py-1 bg-rose-500 text-white rounded-full text-[10px] font-black uppercase tracking-tighter`;
                showToast('Đã ghi nhận đơn hàng!');
                fetchTableStatus();
                updateCartUI();
            }
        } catch (e) {
            showToast('Thất bại khi lưu!', 'error');
        } finally {
            btn.innerHTML = oldContent;
            btn.disabled = false;
            lucide.createIcons();
        }
    }

    async function handleCheckout() {
        if (!confirm('Xác nhận thanh toán hoàn tất?')) return;
        
        try {
            const res = await fetch('<?= url('/admin/api/pos/order/checkout') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    order_id: currentTableSession.orderId,
                    table_id: currentTableSession.tableId
                })
            });
            if ((await res.json()).success) {
                showToast('Thanh toán thành công!');
                closePosPanel();
                fetchTableStatus();
            }
        } catch (e) {
            showToast('Lỗi hệ thống!', 'error');
        }
    }

    async function checkInReservedTable() {
        if (!currentTableSession.reservationId) {
            openPosPanel();
            closeModals();
            return;
        }

        try {
            const res = await fetch('<?= url('/admin/reservations/update-status') ?>?format=json', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id=${currentTableSession.reservationId}&status=completed`
            });
            const data = await res.json();
            if (data.success) {
                showToast('Check-in thành công!');
                closeModals();
                openPosPanel();
                fetchTableStatus(); // Refresh map status
            } else {
                showToast('Lỗi khi Check-in!', 'error');
            }
        } catch (e) {
            showToast('Lỗi hệ thống!', 'error');
        }
    }

    async function markTableCleaned() {
        try {
            const res = await fetch('<?= url('/admin/api/tables/clean') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ table_id: currentTableSession.tableId })
            });
            if ((await res.json()).success) {
                showToast('Bàn đã sẵn sàng!');
                closeModals();
                fetchTableStatus();
            }
        } catch (e) {
            showToast('Lỗi!', 'error');
        }
    }

    // --- Helpers ---

    function filterCategory(catId) {
        activeCategoryId = catId;
        renderCategories();
        handleMenuFilter();
    }

    function handleMenuFilter() {
        renderMenu(document.getElementById('menu-search').value);
    }

    function closePosPanel() {
        const panel = document.getElementById('pos-panel');
        const content = document.getElementById('pos-content');
        const backdrop = document.getElementById('pos-backdrop');
        
        content.classList.add('translate-x-full');
        backdrop.classList.remove('opacity-100');
        setTimeout(() => panel.classList.add('invisible'), 500);
    }

    function showModal(id) {
        const modal = document.getElementById(id);
        const content = modal.querySelector('div:not(.absolute)');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function openAddTableModal() {
        showModal('add-table-modal');
    }

    function closeAddTableModal() {
        const modal = document.getElementById('add-table-modal');
        const content = modal.querySelector('div:not(.absolute)');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Reset form when closing
            document.getElementById('form-add-table').reset();
        }, 300);
    }

    async function saveNewTable(event) {
        event.preventDefault();
        
        const form = event.target;
        const btn = document.getElementById('btn-submit-add-table');
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        // UX: Anti-spam (Disable button)
        btn.disabled = true;
        const oldText = btn.innerText;
        btn.innerText = 'Đang lưu...';

        try {
            const res = await fetch('<?= url('/admin/tables/api/store') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (result.success) {
                showToast(result.message);
                closeAddTableModal();
                fetchTableStatus(); // Refresh the grid
            } else {
                showToast(result.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (e) {
            showToast('Lỗi kết nối hệ thống', 'error');
        } finally {
            // Restore button
            btn.disabled = false;
            btn.innerText = oldText;
        }
    }

    function closeModals() {
        document.querySelectorAll('#reserve-modal, #cleaning-modal').forEach(modal => {
            const content = modal.querySelector('div:not(.absolute)');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        });
    }

    function showToast(msg, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-8 right-8 px-8 py-4 rounded-3xl text-white font-black text-xs uppercase tracking-widest shadow-2xl z-[3000] transform transition-all translate-y-20 ${type === 'success' ? 'bg-slate-900 border-b-4 border-emerald-500' : 'bg-rose-600 border-b-4 border-rose-800'}`;
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-y-20'), 100);
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    const INITIAL_TABLES = <?= json_encode($tables ?? []) ?>;
    if (INITIAL_TABLES.length > 0) {
        renderTableGrid(INITIAL_TABLES);
    } else {
        fetchTableStatus();
    }
    setInterval(fetchTableStatus, 5000);
    
    // Auto-open POS on check-in success
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const checkinSuccess = urlParams.get('checkin_success');
        const tableId = urlParams.get('table_id');
        
        if (checkinSuccess === '1' && tableId) {
            // Wait for INITIAL_TABLES to be processed or API to return
            const tryAutoOpen = () => {
                const tableEl = document.getElementById(`table-${tableId}`);
                if (tableEl) {
                    // Flash the table to highlight it
                    tableEl.classList.add('ring-4', 'ring-emerald-500', 'ring-offset-4');
                    setTimeout(() => tableEl.classList.remove('ring-4', 'ring-emerald-500', 'ring-offset-4'), 2000);
                    
                    // Small delay to ensure everything is initialized before opening
                    setTimeout(() => {
                        showToast('Check-in thành công! Bàn đã sẵn sàng gọi món.');
                        tableEl.click();
                    }, 500);
                    
                    // Clean up URL without refreshing
                    const newUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, newUrl);
                } else {
                    // If table not found yet (maybe grid still rendering), retry once
                    setTimeout(tryAutoOpen, 500);
                }
            };
            tryAutoOpen();
        }
    });
</script>

<style>
    @keyframes modal-in {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    #table-grid.table-grid-responsive {
        grid-template-columns: repeat(auto-fit, minmax(185px, 1fr));
        gap: clamp(0.75rem, 1.3vw, 1.5rem);
    }

    #table-grid .table-card {
        min-width: 0;
        padding: clamp(0.75rem, 1.1vw, 1.5rem);
    }

    #table-grid .table-card-watermark {
        font-size: clamp(4.5rem, 7.5vw, 7.5rem);
        line-height: 1;
    }

    #table-grid .table-card-icon-wrap {
        margin-bottom: clamp(0.45rem, 1vw, 1.25rem);
        padding: clamp(0.7rem, 1.2vw, 1.25rem);
        border-radius: clamp(1rem, 2vw, 2rem);
    }

    #table-grid .table-card-icon {
        width: clamp(1.5rem, 2.4vw, 2.5rem);
        height: clamp(1.5rem, 2.4vw, 2.5rem);
    }

    #table-grid .table-card-number {
        font-size: clamp(1.2rem, 2vw, 1.75rem);
        line-height: 1.1;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #table-grid .table-card-capacity {
        font-size: clamp(0.52rem, 0.85vw, 0.65rem);
        letter-spacing: 0.08em;
    }

    #table-grid .table-card-capacity-icon {
        width: clamp(0.7rem, 1vw, 0.875rem);
        height: clamp(0.7rem, 1vw, 0.875rem);
    }

    #table-grid .table-card-badge {
        bottom: clamp(0.55rem, 1.1vw, 1.5rem);
        padding: clamp(0.2rem, 0.45vw, 0.375rem) clamp(0.5rem, 1vw, 1rem);
        border-radius: clamp(0.7rem, 1.4vw, 1rem);
        font-size: clamp(0.43rem, 0.68vw, 0.56rem);
        line-height: 1;
        letter-spacing: 0.08em;
    }

    @media (max-width: 1024px) {
        #table-grid.table-grid-responsive {
            grid-template-columns: repeat(auto-fit, minmax(165px, 1fr));
        }
    }

    @media (max-width: 768px) {
        #table-grid.table-grid-responsive {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    
    .no-scrollbar::-webkit-scrollbar { display: none; }
</style>
