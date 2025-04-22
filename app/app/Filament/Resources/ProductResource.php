<?php
namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description'),

            // حقل لإضافة الصفات الخاصة بالمنتج
            Forms\Components\Repeater::make('attributes')
            ->label('Product Attributes')
            ->relationship('attributes') // تحديد العلاقة مع attributes
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Attribute Name')
                ->required(),
                 Forms\Components\TextInput::make('pivot.value') // حفظ في الجدول الوسيط
                ->label('Attribute Value')
                ->required(),
            ])
            ->defaultItems(1) // يمكن إضافة صفة واحدة مبدئيًا
            ->columns(2),

            Forms\Components\Select::make('category_id')
                ->options(Category::pluck('name', 'id'))
                ->required(),

            Forms\Components\TextInput::make('price')->numeric()->required(),
            Forms\Components\TextInput::make('stock')->numeric()->required(),

            // رفع الصور
            Forms\Components\Repeater::make('images') // يجب أن يكون مطابقًا للعلاقة في Product
            ->label('Product Images')
            ->relationship('images') // ربط الصور بالمنتج
            ->schema([
                FileUpload::make('image_url')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('product_images'),
                Forms\Components\Checkbox::make('is_primary')
                    ->label('Is Primary Image')
                    ->default(false),
            ])
            ->defaultItems(1)
            ->columns(2),
        
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            ->headerActions([ 
                Tables\Actions\Action::make('exportProductsPdf')
                    ->label('Export Products to PDF')
                    ->action(function () {
                        $products = Product::all();
                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.products', compact('products'));
                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'products_report.pdf'
                        );
                    })
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['images', 'attributes']);
    }
}
