<?php
namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use App\Models\Product;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    // تخصيص الحصول على البيانات التي يتم عرضها
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    // تخصيص محتوى العرض ليتضمن الصفات والصور
    protected function getFormSchema(): array
    {
        $product = $this->record;

        return [
            Forms\Components\TextInput::make('name')
                ->label('Product Name')
                ->default($product->name)
                ->disabled(),
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->default($product->description)
                ->disabled(),
            Forms\Components\TextInput::make('price')
                ->label('Price')
                ->default($product->price)
                ->disabled(),
            Forms\Components\TextInput::make('stock')
                ->label('Stock')
                ->default($product->stock)
                ->disabled(),
            
            // عرض الصفات المرتبطة بالمنتج
            Forms\Components\Repeater::make('attributes') // لعرض الصفات
                ->label('Product Attributes')
                ->schema([
                    Forms\Components\TextInput::make('attribute_name')
                        ->label('Attribute Name')
                        ->default(fn($get) => $get('attribute_name'))
                        ->disabled(),
                    Forms\Components\TextInput::make('value')
                        ->label('Value')
                        ->default(fn($get) => $get('value'))
                        ->disabled(),
                ])
                ->defaultItems(count($product->attributes)) // تكرار الصفات
                ->items($product->attributes) // ربط الصفات
                ->reactive(),

            // عرض الصور المرتبطة بالمنتج
            Forms\Components\Repeater::make('product_images') // لعرض الصور
                ->label('Product Images')
                ->schema([
                    Forms\Components\TextInput::make('image_url')
                        ->label('Image URL')
                        ->default(fn($get) => $get('image_url'))
                        ->disabled(),
                    Forms\Components\Checkbox::make('is_primary')
                        ->label('Is Primary Image')
                        ->default(fn($get) => $get('is_primary'))
                        ->disabled(),
                ])
                ->defaultItems(count($product->images)) // تكرار الصور
                ->items($product->images) // ربط الصور
                ->reactive(),
        ];
    }
}
