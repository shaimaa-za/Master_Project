<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(
                        \App\Models\User::pluck('name', 'id') // استخدام اسم المستخدم بدلاً من رقم user_id
                    )
                    ->required(),

                Forms\Components\Select::make('order_id')
                    ->label('Order')
                    ->options(
                        \App\Models\Order::pluck('id', 'id') // هنا يمكنك تخصيص كيفية عرض الطلبات
                    )
                    ->required(),

                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(
                        \App\Models\Product::pluck('name', 'id') // استخدام اسم المنتج بدلاً من رقم product_id
                    )
                    ->required(),

                Forms\Components\Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '★',
                        2 => '★★',
                        3 => '★★★',
                        4 => '★★★★',
                        5 => '★★★★★',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('comment')
                    ->label('Comment')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name') // عرض اسم المستخدم بدلاً من ID
                    ->label('User')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order.id') // عرض رقم الطلب
                    ->label('Order ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name') // عرض اسم المنتج بدلاً من ID
                    ->label('Product')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => str_repeat('★', $state)) // تحويل التقييم إلى نجوم
                    ->sortable(),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50) // تقليص حجم التعليق للعرض في الجدول
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // إضافة الفلاتر حسب الحاجة
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }    
}
