<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $slug = 'sv23810310115-products';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([

                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->required(),

                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'out_of_stock' => 'Out of Stock',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('price')
                    ->label('Giá tiền')
                    ->numeric()
                    ->prefix('VND')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $discount = $get('discount_percent');

                        if ($state && $discount) {
                            $final = $state - ($state * $discount / 100);
                            $set('final_price', round($final));
                        }
                    }),

                    Forms\Components\TextInput::make('stock_quantity')
                        ->numeric()
                        ->integer()
                        ->required(),

                    //TRƯỜNG SÁNG TẠO
                    Forms\Components\TextInput::make('discount_percent')
                    ->label('Giảm giá (%)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $price = $get('price');

                        if ($price && $state) {
                            $final = $price - ($price * $state / 100);
                            $set('final_price', round($final));
                        }
                    }),
                    Forms\Components\TextInput::make('final_price')
                    ->label('Giá sau giảm')
                    ->disabled()
                    ->dehydrated(true),

                    // ✅ ẢNH
                    Forms\Components\FileUpload::make('image_path')
                        ->label('Ảnh sản phẩm')
                        ->image()
                        ->directory('products')
                        ->imagePreviewHeight('150')
                        ->downloadable()
                        ->openable(),

                    Forms\Components\RichEditor::make('description')
                        ->columnSpanFull(),

                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),

              
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá')
                    ->formatStateUsing(fn ($state) =>
                        number_format($state, 0, ',', '.') . ' đ'
                    ),
                
                    Tables\Columns\TextColumn::make('final_price')
                    ->label('Giá sau giảm')
                    ->formatStateUsing(fn ($state) =>
                        number_format($state, 0, ',', '.') . ' đ'
                    ),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Số lượng'),


                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Ảnh')
                    ->height(60),

                Tables\Columns\BadgeColumn::make('status'),

                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Giảm giá')
                    ->suffix('%'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
