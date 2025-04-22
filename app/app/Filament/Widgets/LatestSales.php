<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;

class LatestSales extends BaseWidget
{
    protected static ?string $heading = 'Latest Sales'; // عنوان الودجت
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort=5;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::whereHas('order.payment', function ($query) {
                    $query->where('status', 'completed'); // الطلبات المدفوعة فقط
                })
                ->whereHas('order', function ($query) {
                    $query->where('created_at', '>=', Carbon::now()->subMonth()); // الطلبات في آخر شهر
                })
                ->latest('created_at') // ترتيب تنازلي حسب تاريخ البيع
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price ($)')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Sale Date')
                    ->date('Y-m-d')
                    ->sortable(),
            ]);
    }
}
