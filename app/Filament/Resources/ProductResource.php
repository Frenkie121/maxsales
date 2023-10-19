<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Products Management';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup() : string 
    {
        return __('Products Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    public static function getModelLabel() : string 
    {
        return __('Product');
    }

    public static function getPluralModelLabel() : string 
    {
        return __('Products');
    }

    public static function getEloquentQuery() : Builder 
    {
        return parent::getEloquentQuery()->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->translateLabel()
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('code', generateProductCode($state))),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    // ->disabled()
                                    ->dehydrated()
                                    ->maxLength(255),
                                Forms\Components\Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->translateLabel()
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->translateLabel()
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('store_id')
                                    ->relationship('store', 'name')
                                    ->translateLabel()
                                    ->required()
                                    ->columnSpanFull(),
                            ])->columns(2),
                        ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('purchase_price')
                                    ->translateLabel()
                                    ->required()
                                    ->numeric()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('sale_price')
                                    ->translateLabel()
                                    // ->helperText(__('The default selling price'))
                                    ->gte('purchase_price')
                                    ->required()
                                    ->numeric()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('day_price')
                                    ->translateLabel()
                                    ->nullable()
                                    ->gte('purchase_price')
                                    ->numeric(),
                                Forms\Components\TextInput::make('night_price')
                                    ->translateLabel()
                                    ->nullable()
                                    ->requiredWith('day_price')
                                    ->gte('purchase_price')
                                    ->numeric(),
                                Forms\Components\TextInput::make('weekend_price')
                                    ->translateLabel()
                                    ->nullable()
                                    ->gte('purchase_price')
                                    ->numeric(),
                                Forms\Components\TextInput::make('bonus')
                                    ->translateLabel()
                                    ->nullable()
                                    ->lt('purchase_price')
                                    ->default(0)
                                    ->numeric(),
                    ])->columns(2)
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    // ->cap
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('Created by'))
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('brand.name')
                    ->translateLabel()
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->translateLabel()
                    ->badge()
                    ->color('warning')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('store.name')
                    ->translateLabel()
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->translateLabel()
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->translateLabel()
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('day_price')
                    ->translateLabel()
                    ->money('XAF')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('night_price')
                    ->translateLabel()
                    ->money('XAF')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('weekend_price')
                    ->translateLabel()
                    ->money('XAF')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bonus')
                    ->translateLabel()
                    ->money('XAF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('d-M, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime('d-M, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('brand')
                    ->translateLabel()
                    ->relationship('brand', 'name'),
                SelectFilter::make('category')
                    ->translateLabel()
                    ->relationship('category', 'name'),
                SelectFilter::make('store')
                    ->translateLabel()
                    ->relationship('store', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
            ;
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}
