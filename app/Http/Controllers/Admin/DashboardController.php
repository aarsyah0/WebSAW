<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = (int) now()->year;
        $selectedYear = (int) $request->input('year', $currentYear);
        $monthInput = $request->input('month');
        $selectedMonth = is_numeric($monthInput) ? (int) $monthInput : null;

        if ($selectedYear < 2000 || $selectedYear > 2100) {
            $selectedYear = $currentYear;
        }
        if ($selectedMonth !== null && ($selectedMonth < 1 || $selectedMonth > 12)) {
            $selectedMonth = null;
        }

        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $salesChartQuery = Transaction::query()
            ->where('status', 'paid')
            ->whereYear('created_at', $selectedYear);
        if ($selectedMonth !== null) {
            $salesChartQuery->whereMonth('created_at', $selectedMonth);
        }
        $salesChart = $salesChartQuery
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyRevenueRows = Transaction::query()
            ->where('status', 'paid')
            ->whereYear('created_at', $selectedYear)
            ->selectRaw('MONTH(created_at) as month_num, SUM(total) as total')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        $monthlyRevenue = collect(range(1, 12))->map(function ($month) use ($monthlyRevenueRows) {
            $row = $monthlyRevenueRows->get($month);

            return [
                'month' => now()->month($month)->translatedFormat('M'),
                'total' => (float) ($row->total ?? 0),
            ];
        });

        $statusBaseQuery = Transaction::query()->whereYear('created_at', $selectedYear);
        if ($selectedMonth !== null) {
            $statusBaseQuery->whereMonth('created_at', $selectedMonth);
        }

        $statusBreakdown = [
            'pending' => (clone $statusBaseQuery)->where('status', 'pending')->count(),
            'paid' => (clone $statusBaseQuery)->where('status', 'paid')->count(),
            'cancelled' => (clone $statusBaseQuery)->where('status', 'cancelled')->count(),
        ];

        $availableYears = Transaction::query()
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($year) => (int) $year)
            ->filter()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([$currentYear]);
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalTransactions',
            'salesChart',
            'monthlyRevenue',
            'statusBreakdown',
            'selectedYear',
            'selectedMonth',
            'availableYears'
        ));
    }
}
