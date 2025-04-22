<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;
use Illuminate\Support\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Orders & Sales Overview';
    protected static ?int $sort=4;
    protected function getData(): array
    {
        // الحصول على السنة الحالية
        $year = now()->year;

        // جلب عدد الطلبات لكل شهر
        $orders = Order::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        // جلب عدد الطلبات المدفوعة (المبيعات) لكل شهر
        $sales = Order::whereYear('created_at', $year)
            ->whereHas('payment', function ($query) {
                $query->where('status', 'completed'); // الطلبات التي تم دفعها
            })
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        // ترتيب البيانات للأشهر من يناير إلى ديسمبر
        $months = range(1, 12);
        $orderCounts = [];
        $salesCounts = [];

        foreach ($months as $month) {
            $orderCounts[] = $orders[$month] ?? 0; // إذا لم يكن هناك بيانات، ضع 0
            $salesCounts[] = $sales[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Orders',
                    'data' => $orderCounts,
                    'borderColor' => '#f59e0b', // لون الطلبات (برتقالي)
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                ],
                [
                    'label' => 'Completed Sales',
                    'data' => $salesCounts,
                    'borderColor' => '#10b981', // لون المبيعات المدفوعة (أخضر)
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // مخطط خطي
    }
}
