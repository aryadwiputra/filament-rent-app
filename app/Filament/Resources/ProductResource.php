<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\PhotosRelationManager;
use App\Models\Brand;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 4;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Category id
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('brand_id', null);
                    }),
                // Brand id
                Forms\Components\Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->options(function (callable $get) {
                        $categoryId = $get('category_id');
                        if (blank($categoryId)) {
                            return [];
                        }
                        return Brand::whereHas('brandCategory', function (Builder $query) use ($categoryId) {
                            $query->where('category_id', $categoryId);
                        })->pluck('name', 'id');
                    }),
                // Name
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                // Price
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
                // About
                Forms\Components\Textarea::make('about')
                    ->required()
                    ->maxLength(255),
                // Thumbnail
                Forms\Components\FileUpload::make('thumbnail')
                    ->required()->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Category
                Tables\Columns\TextColumn::make('category.name'),
                // Brand
                Tables\Columns\TextColumn::make('brand.name'),
                // Name
                Tables\Columns\TextColumn::make('name'),
                // Price
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PhotosRelationManager::class,
        ];
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
