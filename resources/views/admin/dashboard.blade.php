@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard</h2>
        <p class="mt-1 text-gray-500">Ringkasan toko dan penjualan.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <x-metric-card title="Total Produk" :value="$totalProducts" href="{{ route('admin.products.index') }}" icon='<i class="fa-solid fa-cube"></i>'>
            <p class="mt-1 text-sm font-medium text-primary-600">Kelola →</p>
        </x-metric-card>
        <x-metric-card title="Total Transaksi" :value="$totalTransactions" href="{{ route('admin.transactions.index') }}" icon='<i class="fa-solid fa-receipt"></i>'>
            <p class="mt-1 text-sm font-medium text-primary-600">Lihat →</p>
        </x-metric-card>
    </div>

    <x-card>
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select id="month" name="month" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    <option value="">Semua bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ (int) $selectedMonth === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select id="year" name="year" class="rounded-xl border border-gray-200 px-3 py-2 text-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ (int) $selectedYear === (int) $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <x-button type="submit" variant="outline" size="sm">Terapkan Filter</x-button>
            <x-button href="{{ route('admin.dashboard') }}" variant="ghost" size="sm">Reset</x-button>
        </form>
    </x-card>

    <x-card>
        <h3 class="font-semibold text-gray-900 mb-4">Penjualan Paid {{ $selectedMonth ? \Carbon\Carbon::create()->month($selectedMonth)->translatedFormat('F') : 'Semua Bulan' }} {{ $selectedYear }}</h3>
        @if($salesChart->isEmpty())
            <div class="py-12 text-center text-gray-500">Belum ada data penjualan.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold text-gray-700">Tanggal</th>
                            <th class="text-right px-6 py-3 font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($salesChart as $row)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-3 text-gray-700">{{ $row->date }}</td>
                                <td class="px-6 py-3 text-right font-medium text-gray-900">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <x-card>
            <h3 class="font-semibold text-gray-900 mb-1">Grafik Pendapatan Per Bulan</h3>
            <p class="text-sm text-gray-500 mb-6">Line chart pendapatan transaksi dengan status <span class="font-semibold text-green-600">Paid</span> pada tahun {{ $selectedYear }}.</p>
            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <canvas id="monthlyRevenueLineChart" height="220"></canvas>
            </div>
        </x-card>

        <x-card>
            <h3 class="font-semibold text-gray-900 mb-1">Pie Chart Status Transaksi</h3>
            <p class="text-sm text-gray-500 mb-6">Distribusi status transaksi. Fokus utama pada status <span class="font-semibold text-green-600">Paid</span>.</p>
            @php
                $pendingCount = (int) ($statusBreakdown['pending'] ?? 0);
                $paidCount = (int) ($statusBreakdown['paid'] ?? 0);
                $cancelledCount = (int) ($statusBreakdown['cancelled'] ?? 0);
            @endphp

            <div class="rounded-2xl border border-gray-100 bg-white p-4">
                <canvas id="transactionStatusPieChart" height="220"></canvas>
            </div>

            <div class="mt-4 space-y-3 w-full">
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2 text-gray-700"><span class="w-3 h-3 rounded-full bg-amber-400"></span>Pending</span>
                    <span class="font-semibold text-gray-900">{{ $pendingCount }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2 text-gray-700"><span class="w-3 h-3 rounded-full bg-emerald-400"></span>Paid</span>
                    <span class="font-semibold text-gray-900">{{ $paidCount }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="flex items-center gap-2 text-gray-700"><span class="w-3 h-3 rounded-full bg-rose-400"></span>Cancelled</span>
                    <span class="font-semibold text-gray-900">{{ $cancelledCount }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-sm">
                    <span class="text-gray-500">Total transaksi</span>
                    <span class="font-bold text-gray-900">{{ $pendingCount + $paidCount + $cancelledCount }}</span>
                </div>
            </div>
        </x-card>
    </div>
</div>

@php
    $lineLabels = $monthlyRevenue->pluck('month')->values();
    $lineValues = $monthlyRevenue->pluck('total')->map(fn ($v) => (float) $v)->values();
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lineCanvas = document.getElementById('monthlyRevenueLineChart');
    const pieCanvas = document.getElementById('transactionStatusPieChart');
    if (!lineCanvas || !pieCanvas || typeof Chart === 'undefined') {
        return;
    }

    const lineLabels = @json($lineLabels);
    const lineValues = @json($lineValues);
    const statusValues = @json([$pendingCount, $paidCount, $cancelledCount]);

    const lineCtx = lineCanvas.getContext('2d');
    const gradient = lineCtx.createLinearGradient(0, 0, 0, lineCanvas.height);
    gradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
    gradient.addColorStop(1, 'rgba(16, 185, 129, 0.02)');

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: lineLabels,
            datasets: [{
                label: 'Pendapatan Paid',
                data: lineValues,
                tension: 0.45,
                fill: true,
                borderWidth: 2.5,
                borderColor: '#10b981',
                backgroundColor: gradient,
                pointRadius: 3,
                pointHoverRadius: 5,
                pointBackgroundColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Rp ' + Number(context.parsed.y || 0).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(203, 213, 225, 0.35)' },
                    ticks: { color: '#64748b' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(203, 213, 225, 0.35)' },
                    ticks: {
                        color: '#64748b',
                        callback: function (value) {
                            return 'Rp ' + Number(value).toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    new Chart(pieCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Paid', 'Cancelled'],
            datasets: [{
                data: statusValues,
                backgroundColor: ['#fbbf24', '#34d399', '#fb7185'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '58%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        color: '#475569'
                    }
                }
            }
        }
    });
});
</script>
@endsection
