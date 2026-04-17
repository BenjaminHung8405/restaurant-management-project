<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 font-outfit">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Bảng điều khiển</h1>
        <p class="text-slate-500 text-sm mt-1">Tổng quan về hoạt động kinh doanh của nhà hàng.</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-bold text-slate-600 shadow-sm">
            Hôm nay: <?php echo date('d/m/Y'); ?>
        </span>
    </div>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Revenue Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all hover:shadow-md group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="dollar-sign" class="w-6 h-6"></i>
            </div>
            <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg uppercase tracking-wider">Tổng doanh thu</span>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo number_format($totalRevenue, 0, ',', '.'); ?> <span class="text-sm font-medium text-slate-400">VND</span>
            </h3>
            <p class="text-slate-500 text-xs mt-1">Từ tất cả đơn hàng đã hoàn thành</p>
        </div>
    </div>

    <!-- Reservations Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all hover:shadow-md group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-all">
                <i data-lucide="calendar" class="w-6 h-6"></i>
            </div>
            <span class="text-[11px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg uppercase tracking-wider">Đặt bàn hôm nay</span>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $pendingReservations; ?> <span class="text-sm font-medium text-slate-400">Lượt chờ</span>
            </h3>
            <p class="text-slate-500 text-xs mt-1">Số lượt đặt bàn đang chờ xử lý hôm nay</p>
        </div>
    </div>

    <!-- Menu Items Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm transition-all hover:shadow-md group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i data-lucide="utensils" class="w-6 h-6"></i>
            </div>
            <span class="text-[11px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider">Thực đơn</span>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $totalMenuItems; ?> <span class="text-sm font-medium text-slate-400">Món ăn</span>
            </h3>
            <p class="text-slate-500 text-xs mt-1">Tổng cộng các món đang kinh doanh</p>
        </div>
    </div>
</div>

<!-- Chart Area -->
<div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight font-outfit">Biểu đồ doanh thu</h2>
            <p class="text-slate-500 text-sm mt-1">Số liệu thống kê 7 ngày gần nhất.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-primary-500"></div>
            <span class="text-xs font-bold text-slate-600">Doanh thu (VND)</span>
        </div>
    </div>
    <div class="h-[400px] w-full">
        <canvas id="revenueChart"></canvas>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Prepare data from PHP
    const rawData = <?php echo json_encode($chartData); ?>;
    
    // Process labels and values
    const labels = rawData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' });
    });
    const values = rawData.map(item => item.total);

    // Create Gradient
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(249, 115, 22, 0.2)');
    gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Doanh thu',
                data: values,
                borderColor: '#f97316',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#f97316',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#f97316',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11, weight: 'bold' },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + 'M';
                            if (value >= 1000) return (value / 1000) + 'K';
                            return value;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: { size: 11, weight: 'bold' }
                    }
                }
            }
        }
    });

    // Re-initialize Lucide icons if they aren't automatically done
    if (window.lucide) {
        window.lucide.createIcons();
    }
});
</script>
