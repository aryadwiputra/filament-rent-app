<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Store id
                Forms\Components\Select::make('store_id')
                    ->required()
                    ->relationship('store', 'name'),
                // Product id
                Forms\Components\Select::make('product_id')
                    ->required()
                    ->label('Product')
                    ->preload()
                    ->searchable()
                    ->relationship('product', 'name'),
                // TRX Id
                Forms\Components\TextInput::make('trx_id')
                    ->required()
                    ->label('Transaction ID')
                    ->maxLength(255),
                // Date
                Forms\Components\DatePicker::make('started_at')
                    ->required(),
                Forms\Components\DatePicker::make('ended_at')
                    ->required(),
                // Name
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->maxLength(255)
                    ->label('Phone Number')
                    ->numeric(),
                Forms\Components\TextArea::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->required()
                    ->prefix('Rp. '),
                // Is Paid
                Forms\Components\Select::make('is_paid')
                    ->required()
                    ->options([
                        true => 'Paid',
                        false => 'Unpaid',
                    ])
                    ->default('unpaid'),
                // Duration
                Forms\Components\TextInput::make('duration')
                    ->label('Duration')
                    ->required()
                    ->numeric()
                    ->prefix('Days'),
                // Proof
                Forms\Components\FileUpload::make('proof')
                    ->required()->image(),
                // Delivery Type
                Forms\Components\Select::make('delivery_type')
                    ->required()
                    ->options([
                        'pickup' => 'Pickup',
                        'home_delivery' => 'Delivery',
                    ])
                    ->default('pickup'),
                // is

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('product.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('trx_id')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone_number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('address')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('total_amount')->money('idr')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('duration')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('delivery_type')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('is_paid')
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-s-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('started_at')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('ended_at')->sortable()->searchable(),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
