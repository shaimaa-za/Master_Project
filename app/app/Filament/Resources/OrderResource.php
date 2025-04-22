<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Barryvdh\DomPDF\Facade as PDF;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $pluralModelLabel = 'Orders';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('User ID')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price ($)')
                    ->numeric()
                    ->required(),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer Name')
                    ->sortable()
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->date('Y-m-d')
                    ->sortable(),
    
                // إضافة عمود حالة الدفع
                Tables\Columns\TextColumn::make('payment.status')
                    ->label('Payment Status')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (strtolower($state)) {
                        'pending'   => 'gray',
                        'completed' => 'green',
                        'failed'    => 'red',
                        default     => 'gray',
                    }),
    
                // إضافة عمود حالة الشحن
                Tables\Columns\TextColumn::make('shipping.status')
                    ->label('Shipping Status')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match (strtolower($state)) {
                        'pending'   => 'gray',
                        'shipped'   => 'blue',
                        'delivered' => 'green',
                        'canceled'  => 'red',
                        default     => 'gray',
                    }),
            ])
            ->filters([
                // يمكنك إضافة الفلاتر حسب الحاجة
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->headerActions([ 
                Tables\Actions\Action::make('exportPdf')
                    ->label('Export Orders to PDF')
                    ->action(function () {
                        $orders = Order::all();
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.orders', compact('orders'));
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'orders_report.pdf'
                        );
                    })
                ]);
    }
    

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
