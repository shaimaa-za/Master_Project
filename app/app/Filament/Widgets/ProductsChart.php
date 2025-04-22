<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Product;
use Illuminate\Support\Carbon;

class ProductsChart extends ChartWidget
{
    protected static ?string $heading = 'Products Chart';
    protected static string $color = 'info';
    protected static ?int $sort=2;
    protected function getData(): array
    {
        // الحصول على السنة الحالية
        $year = now()->year;

        // جلب عدد المنتجات المضافة لكل شهر من يناير إلى مايو
        $data = Product::whereYear('created_at', $year)
            ->whereBetween('created_at', [
                Carbon::create($year, 1, 1), // بداية يناير
                Carbon::create($year, 5, 31, 23, 59, 59) // نهاية مايو
            ])
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        // ترتيب البيانات بناءً على الأشهر المطلوبة (يناير - مايو)
        $months = range(1, 5);
        $productCounts = [];

        foreach ($months as $month) {
            $productCounts[] = $data[$month] ?? 0; // إذا لم يكن هناك بيانات، ضع 0
        }

        return [
            'datasets' => [
                [
                    'label' => 'Products Added',
                    'data' => $productCounts,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // يمكنك تغييره إلى 'line' أو 'pie' حسب الحاجة
    }
}
