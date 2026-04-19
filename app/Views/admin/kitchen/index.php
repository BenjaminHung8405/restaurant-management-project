<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> | RestoMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto Mono', monospace; }
        .ticket-shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen flex flex-col overflow-x-hidden">
    <!-- Top Bar -->
    <header class="bg-slate-900 border-b-4 border-emerald-600 px-8 py-4 flex justify-between items-center shadow-2xl sticky top-0 z-50">
        <div class="flex items-center space-x-6">
            <div id="clock" class="text-3xl font-bold text-emerald-400 tabular-nums tracking-widest">00:00:00</div>
            <div class="h-10 w-1 bg-gray-700"></div>
            <h1 class="text-2xl font-black tracking-tighter uppercase text-white">Hệ thống Nhà bếp (KDS)</h1>
        </div>
        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Đang kết nối</span>
            </div>
            <a href="<?php echo url("/admin/dashboard"); ?>" class="bg-gray-800 hover:bg-gray-700 border border-gray-600 px-6 py-2 rounded-lg text-sm font-bold transition-all hover:scale-105 active:scale-95 uppercase tracking-wider">
                &larr; Quản trị
            </a>
        </div>
    </header>

    <!-- Main Grid -->
    <main id="tickets-container" class="p-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-8">
        <!-- Empty State -->
        <div id="empty-state" class="col-span-full flex flex-col items-center justify-center py-40 opacity-20">
            <svg class="w-32 h-32 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h2 class="text-4xl font-bold uppercase tracking-tighter">Nhà bếp đang trống</h2>
            <p class="text-xl">Đang chờ đơn hàng mới...</p>
        </div>
    </main>

    <script>
        const CONFIG = {
            baseUrl: '<?php echo url("/"); ?>',
            api: {
                pendingOrders: '<?php echo url("/admin/kitchen/api/pending-orders"); ?>',
                markItemCooking: '<?php echo url("/admin/kitchen/api/mark-item-cooking"); ?>',
                markItemDone: '<?php echo url("/admin/kitchen/api/mark-item-done"); ?>'
            }
        };

        const ticketsContainer = document.getElementById('tickets-container');
        const emptyState = document.getElementById('empty-state');
        const clockElement = document.getElementById('clock');
        let currentOrders = [];
        const urgencyTextClasses = ['text-rose-500', 'text-amber-500', 'text-emerald-400'];
        const urgencyBorderClasses = [
            'border-rose-600',
            'shadow-rose-900/40',
            'border-amber-600',
            'shadow-amber-900/40',
            'border-emerald-700',
            'shadow-emerald-900/40'
        ];

        // Update Clock
        function updateClock() {
            const now = new Date();
            clockElement.textContent = now.toLocaleTimeString('en-GB', { hour12: false });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Calculate time elapsed
        function getTimeElapsed(createdAt) {
            const start = new Date(createdAt);
            const now = new Date();
            const diffInMinutes = Math.floor((now - start) / 60000);
            return diffInMinutes;
        }

        function getUrgencyStyles(minutes) {
            if (minutes > 15) {
                return {
                    textClass: 'text-rose-500',
                    borderClasses: ['border-rose-600', 'shadow-rose-900/40']
                };
            }

            if (minutes > 10) {
                return {
                    textClass: 'text-amber-500',
                    borderClasses: ['border-amber-600', 'shadow-amber-900/40']
                };
            }

            return {
                textClass: 'text-emerald-400',
                borderClasses: ['border-emerald-700', 'shadow-emerald-900/40']
            };
        }

        function refreshWaitTimes() {
            const waitBlocks = ticketsContainer.querySelectorAll('[data-wait-created-at]');

            waitBlocks.forEach(waitBlock => {
                const createdAt = waitBlock.getAttribute('data-wait-created-at');
                const waitValue = waitBlock.querySelector('[data-wait-value]');
                const ticket = waitBlock.closest('[data-ticket]');

                if (!createdAt || !waitValue || !ticket) {
                    return;
                }

                const minutes = getTimeElapsed(createdAt);
                const urgency = getUrgencyStyles(minutes);

                waitValue.textContent = `${minutes}p`;
                waitValue.classList.remove(...urgencyTextClasses);
                waitValue.classList.add(urgency.textClass);

                ticket.classList.remove(...urgencyBorderClasses);
                ticket.classList.add(...urgency.borderClasses);
            });
        }

        // Fetch Orders
        async function fetchPendingOrders() {
            try {
                const response = await fetch(CONFIG.api.pendingOrders);
                if (!response.ok) throw new Error('API Error');
                const orders = await response.json();
                renderTickets(orders);
            } catch (error) {
                console.error('Fetch Error:', error);
            }
        }

        // Render Tickets
        function renderTickets(orders) {
            if (JSON.stringify(orders) === JSON.stringify(currentOrders)) {
                refreshWaitTimes();
                return;
            }

            currentOrders = orders;

            if (orders.length === 0) {
                ticketsContainer.innerHTML = '';
                ticketsContainer.appendChild(emptyState);
                return;
            }

            ticketsContainer.innerHTML = '';
            orders.forEach(order => {
                const minutes = getTimeElapsed(order.created_at);
                const urgency = getUrgencyStyles(minutes);

                const ticket = document.createElement('div');
                ticket.setAttribute('data-ticket', 'true');
                ticket.className = `bg-gray-800 border-2 ${urgency.borderClasses.join(' ')} rounded-xl shadow-2xl flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-300`;
                ticket.innerHTML = `
                    <div class="px-5 py-3 border-b border-gray-700 flex justify-between items-start bg-gray-700/50">
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Bàn</div>
                            <div class="text-2xl font-black text-white">${order.table_number}</div>
                        </div>
                        <div class="text-right" data-wait-created-at="${order.created_at}">
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Chờ</div>
                            <div class="text-2xl font-black ${urgency.textClass}" data-wait-value>${minutes}p</div>
                        </div>
                    </div>
                    <div class="p-5 flex-grow space-y-4">
                        ${order.items.map(item => `
                            <div id="item-${item.id}" class="group border-b border-gray-700/50 pb-3 last:border-0 hover:bg-gray-700/30 transition-colors p-2 rounded-lg ${item.status === 'cooking' ? 'bg-amber-900/10' : ''}">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-grow">
                                        <div class="text-xl font-bold leading-tight uppercase group-hover:text-emerald-300 transition-colors">
                                            <span class="text-emerald-500 mr-2">${item.quantity}x</span>${item.name}
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            ${item.status === 'cooking' ? `<span class="text-[10px] font-black bg-amber-500 text-amber-950 px-2 py-0.5 rounded uppercase tracking-tighter shadow-sm animate-pulse">Đang nấu</span>` : ''}
                                            ${item.notes ? `<div class="text-[10px] text-amber-400 font-bold uppercase italic bg-amber-900/30 px-2 py-0.5 rounded inline-block">Ghi chú: ${item.notes}</div>` : ''}
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        ${item.status === 'pending' ? `
                                            <button 
                                                onclick="markAsCooking('${item.id}', this)"
                                                class="cooking-btn bg-amber-600 hover:bg-amber-500 text-white font-black px-4 py-2 rounded-lg text-[10px] shadow-lg transition-all active:scale-95 disabled:opacity-50 uppercase flex-shrink-0"
                                            >
                                                NẤU
                                            </button>
                                        ` : ''}
                                        <button 
                                            onclick="markAsDone('${item.id}', this)"
                                            class="done-btn bg-emerald-600 hover:bg-emerald-500 text-white font-black px-4 py-2 rounded-lg text-[10px] shadow-lg transition-all active:scale-95 disabled:opacity-50 uppercase flex-shrink-0"
                                        >
                                            XONG
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                    <div class="px-5 py-2 bg-gray-900/50 border-t border-gray-700 flex justify-between items-center">
                        <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">ID: ${order.order_id.substring(0,8)}</span>
                        <span class="text-[10px] text-gray-500 font-bold tabular-nums uppercase tracking-widest">${new Date(order.created_at).toLocaleTimeString()}</span>
                    </div>
                `;
                ticketsContainer.appendChild(ticket);
            });

            refreshWaitTimes();
        }

        // Mark as Cooking
        async function markAsCooking(itemId, btn) {
            btn.disabled = true;
            btn.innerHTML = '...';
            
            try {
                const response = await fetch(CONFIG.api.markItemCooking, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_item_id: itemId })
                });

                if (response.ok) {
                    // Update state immediately for UX, then refresh
                    fetchPendingOrders();
                } else {
                    throw new Error('Failed');
                }
            } catch (error) {
                console.error('Mark Cooking Error:', error);
                btn.disabled = false;
                btn.innerHTML = 'NẤU';
            }
        }

        // Mark as Done
        async function markAsDone(itemId, btn) {
            btn.disabled = true;
            btn.innerHTML = '...';
            const itemElement = document.getElementById(`item-${itemId}`);
            itemElement.classList.add('opacity-50');

            try {
                const response = await fetch(CONFIG.api.markItemDone, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_item_id: itemId })
                });

                if (response.ok) {
                    itemElement.classList.add('ticket-shake');
                    setTimeout(() => {
                        itemElement.remove();
                        // Refresh to check if whole ticket should be gone
                        fetchPendingOrders();
                    }, 500);
                } else {
                    throw new Error('Failed');
                }
            } catch (error) {
                console.error('Mark Done Error:', error);
                btn.disabled = false;
                btn.innerHTML = 'DONE';
                itemElement.classList.remove('opacity-50');
            }
        }

        // Start Polling
        fetchPendingOrders();
        setInterval(fetchPendingOrders, 5000);
        setInterval(refreshWaitTimes, 1000);
    </script>
</body>
</html>
