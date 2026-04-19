<?php
/**
 * @var string $flashSuccess
 * @var string $flashError
 */
?>

<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Quản lý Đơn hàng</h1>
            <p class="text-slate-500 mt-1">Theo dõi và cập nhật trạng thái đơn hàng của khách tại quán.</p>
        </div>
        <div class="flex items-center gap-3">
            <div id="filter-indicator" class="hidden flex items-center gap-2 px-3 py-1.5 bg-primary-50 border border-primary-200 rounded-full text-xs font-bold text-primary-600 shadow-sm animate-pulse">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span id="filter-text">Đang lọc...</span>
                <button onclick="clearFilter()" class="ml-1 hover:text-primary-800 transition-colors" title="Bỏ lọc">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </button>
            </div>
            <div id="refresh-status" class="flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-500 shadow-sm transition-all duration-300">
                <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                <span>Đang tự động cập nhật...</span>
            </div>
            <button onclick="cleanupOldOrders()" class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 hover:bg-amber-100 hover:border-amber-300 transition-all active:scale-95 shadow-sm font-bold text-sm" title="Dọn dẹp đơn cũ">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Dọn dẹp nhanh</span>
            </button>
            <button onclick="fetchOrders()" class="p-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:text-primary-600 hover:border-primary-200 hover:bg-primary-50 transition-all active:scale-95 shadow-sm" title="Làm mới ngay">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Stats Overview (Optional, can be expanded) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Chờ xác nhận</p>
                <p id="stat-pending" class="text-2xl font-bold text-slate-900">0</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <i data-lucide="chef-hat" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Đang chuẩn bị</p>
                <p id="stat-preparing" class="text-2xl font-bold text-slate-900">0</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <i data-lucide="utensils" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Đang phục vụ</p>
                <p id="stat-serving" class="text-2xl font-bold text-slate-900">0</p>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Hôm nay</p>
                <p id="stat-completed" class="text-2xl font-bold text-slate-900">0</p>
            </div>
        </div>
    </div>

    <!-- Orders Table Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">Mã Đơn</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">Bàn</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">Khách hàng</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">Thời gian</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap text-right">Tổng tiền</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest whitespace-nowrap">Trạng thái</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body" class="divide-y divide-slate-100">
                    <!-- Data will be populated by JS -->
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-10 h-10 border-4 border-primary-100 border-t-primary-500 rounded-full animate-spin"></div>
                                <p class="text-slate-400 font-medium">Đang tải danh sách đơn hàng...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="empty-state" class="hidden py-16 text-center">
            <div class="flex flex-col items-center gap-3 opacity-40">
                <i data-lucide="package-search" class="w-16 h-16 text-slate-300"></i>
                <p class="text-lg font-medium text-slate-400">Không có đơn hàng nào trong danh sách.</p>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div id="details-modal" class="fixed inset-0 z-[60] hidden overflow-hidden transition-all duration-300">
    <div id="modal-overlay" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div id="modal-content" class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl scale-95 opacity-0 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary-100 text-primary-600 rounded-lg">
                        <i data-lucide="receipt" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 flex items-center gap-2">
                            Chi tiết đơn hàng <span id="modal-order-id" class="text-primary-600">#...</span>
                        </h3>
                        <p id="modal-order-info" class="text-xs text-slate-500 font-medium mt-0.5">Bàn T1-01 • 17:45 18/04/2026</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="p-2 hover:bg-white hover:shadow-sm rounded-full text-slate-400 hover:text-slate-600 transition-all">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div id="modal-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                <!-- Items will be populated by JS -->
                <div class="space-y-4 animate-pulse">
                    <div class="h-12 bg-slate-100 rounded-lg"></div>
                    <div class="h-12 bg-slate-100 rounded-lg"></div>
                    <div class="h-12 bg-slate-100 rounded-lg"></div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex flex-col">
                    <span class="text-xs text-slate-400 uppercase tracking-widest font-bold">Tổng cộng</span>
                    <span id="modal-total-amount" class="text-xl font-extrabold text-primary-600">0 ₫</span>
                </div>
                <div class="flex items-center gap-3">
                    <button id="modal-cancel-btn" class="hidden px-6 py-2.5 bg-red-50 border border-red-200 text-red-600 font-bold rounded-xl hover:bg-red-100 transition-all shadow-sm active:scale-95">
                        Hủy đơn
                    </button>
                    <button onclick="closeModal()" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all shadow-sm active:scale-95">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let ordersData = [];

    const STATUS_MAP = {
        'pending': { label: 'Chờ xác nhận', class: 'bg-amber-100 text-amber-700' },
        'preparing': { label: 'Đang chuẩn bị', class: 'bg-blue-100 text-blue-700' },
        'serving': { label: 'Đang phục vụ', class: 'bg-indigo-100 text-indigo-700' },
        'completed': { label: 'Đã hoàn thành', class: 'bg-emerald-100 text-emerald-700' },
        'cancelled': { label: 'Đã hủy', class: 'bg-slate-100 text-slate-500' }
    };

    function formatVND(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }) + ' ' + date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' });
    }

    async function fetchOrders() {
        const refreshEl = document.getElementById('refresh-status');
        const indicatorEl = document.getElementById('filter-indicator');
        const indicatorTextEl = document.getElementById('filter-text');
        
        refreshEl.classList.add('scale-105', 'border-primary-200');
        
        // Get table_id from URL
        const urlParams = new URLSearchParams(window.location.search);
        const tableId = urlParams.get('table_id');
        
        let apiUrl = '<?= url('/admin/orders/api/list') ?>';
        if (tableId) {
            apiUrl += `?table_id=${tableId}`;
            indicatorEl.classList.remove('hidden');
            indicatorTextEl.innerText = 'Đang lọc theo bàn chọn';
        } else {
            indicatorEl.classList.add('hidden');
        }

        try {
            const response = await fetch(apiUrl);
            const result = await response.json();
            
            if (result.success) {
                ordersData = result.data;
                renderOrders();
                updateStats();
                
                // If filtered and data exists, update label with table number if possible
                if (tableId && ordersData.length > 0) {
                    indicatorTextEl.innerText = `Đang lọc: Bàn ${ordersData[0].table_number}`;
                }
            }
        } catch (error) {
            console.error('Error fetching orders:', error);
        } finally {
            setTimeout(() => {
                refreshEl.classList.remove('scale-105', 'border-primary-200');
            }, 500);
            lucide.createIcons();
        }
    }

    function clearFilter() {
        const url = new URL(window.location.href);
        url.searchParams.delete('table_id');
        window.history.pushState({}, '', url);
        fetchOrders();
    }

    function renderOrders() {
        const tbody = document.getElementById('orders-table-body');
        const emptyState = document.getElementById('empty-state');
        
        if (ordersData.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        tbody.innerHTML = ordersData.map(order => {
            const status = STATUS_MAP[order.order_status] || { label: order.order_status, class: 'bg-slate-100 text-slate-700' };
            
            return `
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-6 py-4">
                        <button onclick="viewDetails('${order.id}')" class="font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1.5 transition-all active:scale-95 group-hover:translate-x-1">
                            #${order.id.substring(0, 8)}
                            <i data-lucide="external-link" class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </button>
                    </td>
                    <td class="px-6 py-4 font-semibold text-slate-700">
                        <div class="inline-flex items-center gap-1.5 px-2 py-1 bg-slate-100 rounded-md text-xs">
                            <i data-lucide="table" class="w-3 h-3"></i>
                            ${order.table_number || 'N/A'}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                ${ (order.customer_name || 'G').charAt(0) }
                            </div>
                            <span class="text-sm font-medium text-slate-700">${order.customer_name || 'Khách vãng lai'}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-500 font-medium whitespace-nowrap">
                        ${formatDate(order.created_at)}
                    </td>
                    <td class="px-6 py-4 text-sm font-black text-slate-900 text-right tabular-nums whitespace-nowrap">
                        ${formatVND(order.total_amount)}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold ${status.class}">
                            ${status.label}
                        </span>
                    </td>
                </tr>
            `;
        }).join('');
        
        lucide.createIcons();
    }

    function updateStats() {
        const counts = { pending: 0, preparing: 0, serving: 0, completed: 0 };
        const today = new Date().toISOString().split('T')[0];

        ordersData.forEach(order => {
            if (counts.hasOwnProperty(order.order_status)) {
                counts[order.order_status]++;
            }
            if (order.order_status === 'completed' && order.created_at.startsWith(today)) {
                // Today count logic could be different, but let's just use status for now
            }
        });

        document.getElementById('stat-pending').innerText = counts.pending;
        document.getElementById('stat-preparing').innerText = counts.preparing;
        document.getElementById('stat-serving').innerText = counts.serving;
        document.getElementById('stat-completed').innerText = ordersData.filter(o => o.order_status === 'completed' && o.created_at.startsWith(today)).length;
    }

    async function updateStatus(id, newStatus, currentStatus) {
        if (newStatus === 'cancelled' && !confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) return;

        const formData = new FormData();
        formData.append('order_id', id);
        formData.append('status', newStatus);
        formData.append('current_status', currentStatus);

        try {
            const response = await fetch('<?= url('/admin/orders/update-status') ?>', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                fetchOrders(); // Refresh the list
            } else {
                alert(result.message);
                if (result.latest_status) fetchOrders();
            }
        } catch (error) {
            console.error('Error updating status:', error);
            alert('Có lỗi xảy ra khi kết nối server.');
        }
    }

    async function cleanupOldOrders() {
        if (!confirm('Hệ thống sẽ chuyển tất cả các đơn hàng chưa hoàn thành từ ngày hôm trước sang trạng thái "Đã hủy". Bạn có chắc chắn muốn dọn dẹp không?')) {
            return;
        }

        try {
            const response = await fetch('<?= url('/admin/orders/cleanup') ?>', {
                method: 'POST'
            });
            const result = await response.json();
            
            if (result.success) {
                alert(result.message);
                fetchOrders(); // Refresh list
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Error during cleanup:', error);
            alert('Có lỗi xảy ra khi dọn dẹp.');
        }
    }

    async function viewDetails(id) {
        const order = ordersData.find(o => o.id === id);
        if (!order) return;

        document.getElementById('modal-order-id').innerText = '#' + id.substring(0, 8);
        document.getElementById('modal-order-info').innerText = `Bàn ${order.table_number || 'N/A'} • ${formatDate(order.created_at)}`;
        document.getElementById('modal-total-amount').innerText = formatVND(order.total_amount);
        
        const container = document.getElementById('modal-items-container');
        container.innerHTML = `
            <div class="flex flex-col items-center py-8">
                <div class="w-8 h-8 border-3 border-primary-100 border-t-primary-500 rounded-full animate-spin"></div>
            </div>
        `;

        openModal();

        try {
            const response = await fetch('<?= url('/admin/orders/api/items') ?>?id=' + id);
            const result = await response.json();
            
            if (result.success) {
                container.innerHTML = result.data.map(item => `
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-slate-100 hover:border-primary-100 hover:bg-primary-50/30 transition-all group">
                        <div class="w-14 h-14 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0 shadow-sm">
                            <img src="${item.image_url || 'https://via.placeholder.com/100'}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="${item.menu_item_name}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 truncate">${item.menu_item_name}</h4>
                            <div class="flex items-center gap-2 text-xs text-slate-500 font-medium mt-1">
                                <span class="px-1.5 py-0.5 bg-slate-100 rounded">SL: ${item.quantity}</span>
                                <span>•</span>
                                <span>${formatVND(item.unit_price)}</span>
                            </div>
                            ${item.notes ? `<p class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded inline-block mt-2 font-medium">Ghi chú: ${item.notes}</p>` : ''}
                        </div>
                        <div class="text-right font-black text-slate-900 whitespace-nowrap">
                            ${formatVND(item.unit_price * item.quantity)}
                        </div>
                    </div>
                `).join('');
            }
        } catch (error) {
            container.innerHTML = '<p class="text-center text-red-500">Lỗi khi tải dữ liệu.</p>';
        }
        
        lucide.createIcons();
    }

    function openModal() {
        const modal = document.getElementById('details-modal');
        const overlay = document.getElementById('modal-overlay');
        const content = document.getElementById('modal-content');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.add('opacity-100');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('details-modal');
        const overlay = document.getElementById('modal-overlay');
        const content = document.getElementById('modal-content');
        
        overlay.classList.remove('opacity-100');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Initial Fetch
    fetchOrders();

    // Auto Refresh every 5 seconds
    setInterval(fetchOrders, 5000);

    // Initial Lucide
    lucide.createIcons();
</script>
