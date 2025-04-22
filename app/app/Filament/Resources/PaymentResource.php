<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?string $pluralModelLabel = 'Payments';

    public static function canCreate(): bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'paypal' => 'PayPal',
                        'cod' => 'Cash on Delivery',
                    ])
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ])
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Payment ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order ID')
                    ->sortable(),

                // عمود طريقة الدفع
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->sortable()
                    ->searchable(),

                // عمود حالة الدفع
                Tables\Columns\TextColumn::make('status')
                    ->label('Payment Status')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'yellow',
                        'completed' => 'green',
                        'failed' => 'red',
                        default => 'gray',
                    }),

              
                // عمود تاريخ الدفع
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Payment Date')
                    ->date('Y-m-d')
                    ->sortable(),
            ])
            ->filters([
                // فلتر حالة الدفع
                SelectFilter::make('status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),

                // فلتر طريقة الدفع
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'paypal' => 'PayPal',
                        'cod' => 'Cash on Delivery',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([ 
                Tables\Actions\Action::make('exportPaymentsPdf')
                    ->label('Export Payments to PDF')
                    ->action(function () {
                        $payments = Payment::all();
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.payments', compact('payments'));
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'payments_report.pdf'
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
            'index' => Pages\ListPayments::route('/'),
            //'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
