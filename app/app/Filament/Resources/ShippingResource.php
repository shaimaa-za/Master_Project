<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingResource\Pages;
use App\Models\Shipping;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class ShippingResource extends Resource
{
    protected static ?string $model = Shipping::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Shippings';
    protected static ?string $pluralModelLabel = 'Shippings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->label('Order')
                    ->relationship('order', 'id')
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('address_id')
                    ->label('Shipping Address')
                    ->relationship('address', 'country') // يفترض أن هناك حقل 'full_address' في جدول العناوين
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('company_shipping')
                    ->label('company_shipping')
                    ->required(),

                Forms\Components\TextInput::make('shipping_fee')
                    ->label('Shipping Fee ($)')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('tracking_number')
                    ->label('Tracking Number')
                    ->helperText('Optional if not available'),

                Forms\Components\Select::make('status')
                    ->label('Shipping Status')
                    ->options([
                        'pending'   => 'Pending',
                        'shipped'   => 'Shipped',
                        'delivered' => 'Delivered',
                        'canceled'  => 'Canceled',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Shipping ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('address.country')
                    ->label('country')
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('address.city')
                    ->label('City')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('address.zip_code')
                    ->label('zip_code')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('company_shipping')
                    ->label('Company Shipping')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('shipping_fee')
                    ->label('Shipping Fee ($)')
                    ->sortable()
                    ->money('USD'),

                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('Tracking Number')
                    ->sortable()
                    ->default('Not Available'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Shipping Status')
                    ->badge()
                    ->sortable()
                    ->color(fn ($state) => match (strtolower($state)) {
                        'pending'   => 'gray',
                        'shipped'   => 'blue',
                        'delivered' => 'green',
                        'canceled'  => 'red',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Shipping Status')
                    ->options([
                        'pending'   => 'Pending',
                        'shipped'   => 'Shipped',
                        'delivered' => 'Delivered',
                        'canceled'  => 'Canceled',
                    ]),

                SelectFilter::make('carrier')
                    ->label('Carrier')
                    ->options([
                        'DHL'   => 'DHL',
                        'FedEx' => 'FedEx',
                        'UPS'   => 'UPS',
                    ]),
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
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListShippings::route('/'),
            'create' => Pages\CreateShipping::route('/create'),
            'view'   => Pages\ViewShipping::route('/{record}'),
            'edit'   => Pages\EditShipping::route('/{record}/edit'),
        ];
    }
}
