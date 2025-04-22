<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Shipping;
use Carbon\Carbon;

class AdminstatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // حساب عدد المدراء وزبائن النظام بالكامل
        $adminCountTotal = User::where('role', 'admin')->count();
        $customerCountTotal = User::where('role', 'customer')->count();

        // حساب عدد الشحنات المشحونة وغير المشحونة
        $shippedCount = Shipping::whereIn('status', ['shipped', 'delivered'])->count();
        $notShippedCount = Shipping::whereIn('status', ['pending', 'canceled'])->count();

        return [
            // إحصائية المدراء
            Stat::make('Admins', "$adminCountTotal")
                ->description('Total Admins')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            // إحصائية الزبائن
            Stat::make('Customers', "$customerCountTotal")
                ->description('Total Customers')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            // إحصائية المنتجات
            Stat::make('Products', Product::query()->count())
                ->description('Number Of Products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            // إحصائية الطلبات
            Stat::make('Orders', Order::query()->count())
                ->description('Number Of Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            // عدد الشحنات المشحونة
            Stat::make('Shipped Orders', "$shippedCount")
                ->description('Total Shipped Orders')
                ->descriptionIcon('heroicon-m-truck')
                ->color('blue'),

            // عدد الشحنات غير المشحونة
            Stat::make('Pending Orders', "$notShippedCount")
                ->description('Orders Not Yet Shipped')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('gray'),
        ];
    }
}
