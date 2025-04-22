<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FavoriteResource\Pages;
use App\Filament\Resources\FavoriteResource\RelationManagers;
use App\Models\Favorite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FavoriteResource extends Resource
{
    protected static ?string $model = Favorite::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('User ID')
                    ->required()
                    ->numeric()
                    ->disabled() // منع التعديل على الـ user_id بشكل يدوي
                    ->default(auth()->id()), // تعيين الـ user_id تلقائيًا للمستخدم الحالي
                Forms\Components\TextInput::make('product_id')
                    ->label('Product ID')
                    ->required()
                    ->numeric()
                    ->searchable() // السماح بالبحث عن المنتجات
                    ->placeholder('Enter Product ID'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('User')
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->user->name ?? 'N/A'), // عرض اسم المستخدم
                Tables\Columns\TextColumn::make('product_id')
                    ->label('Product')
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->product->name ?? 'N/A'), // عرض اسم المنتج
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListFavorites::route('/'),
            'create' => Pages\CreateFavorite::route('/create'),
            'view' => Pages\ViewFavorite::route('/{record}'),
            'edit' => Pages\EditFavorite::route('/{record}/edit'),
        ];
    }
    
    // منع إنشاء المفضلة إذا كانت موجودة مسبقًا
    public static function canCreate(): bool
    {
        // التحقق إذا كان المنتج موجودًا بالفعل في المفضلة لهذا المستخدم
        if (Favorite::where('user_id', auth()->id())->where('product_id', request('product_id'))->exists()) {
            return false;
        }
        return true;
    }
}
