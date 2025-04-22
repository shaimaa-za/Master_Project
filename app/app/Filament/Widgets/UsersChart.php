<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Carbon;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Users Growth (Admins & Customers)';
    protected static ?int $sort=3;
    protected function getData(): array
    {
        // الحصول على السنة الحالية
        $year = now()->year;

        // جلب عدد المدراء لكل شهر
        $admins = User::whereYear('created_at', $year)
            ->where('role', 'admin')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        // جلب عدد الزبائن لكل شهر
        $customers = User::whereYear('created_at', $year)
            ->where('role', 'customer')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        // ترتيب البيانات للأشهر من يناير إلى ديسمبر
        $months = range(1, 12);
        $adminCounts = [];
        $customerCounts = [];

        foreach ($months as $month) {
            $adminCounts[] = $admins[$month] ?? 0; // إذا لم يكن هناك بيانات، ضع 0
            $customerCounts[] = $customers[$month] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Admins',
                    'data' => $adminCounts,
                    'borderColor' => '#ef4444', // لون المدراء (أحمر)
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                ],
                [
                    'label' => 'Customers',
                    'data' => $customerCounts,
                    'borderColor' => '#3b82f6', // لون الزبائن (أزرق)
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // مخطط خطي
    }
}
