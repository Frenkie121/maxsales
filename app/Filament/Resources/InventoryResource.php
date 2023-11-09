<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Inventory;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\InventoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\InventoryProduct;
use Faker\Provider\ar_EG\Text;
use Filament\Tables\Actions\ViewAction;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Stock Management';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup() : string 
    {
        return __('Stock Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Inventories');
    }

    public static function getModelLabel() : string 
    {
        return __('Inventory');
    }

    public static function getPluralModelLabel() : string 
    {
        return __('Inventories');
    }

    public static function getEloquentQuery() : Builder 
    {
        return parent::getEloquentQuery()->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('Author')
                            ->default(auth()->user()->name)
                            ->translateLabel()
                            ->disabled(),
                        Select::make('store_id')
                            ->relationship('store', 'name')
                            ->required()
                            ->reactive()
                            // ->disabled(fn ($state) => ! is_null($state))
                            ->translateLabel()
                    ])->columns(2),
                Section::make()
                    ->schema([
                        Repeater::make('inventoryProducts')
                            ->label(__('Products'))
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->translateLabel()
                                    ->relationship('product', 'name')
                                    ->required()
                                    // ->unique()
                                    ->preload()
                                    ->searchable()
                                    ->placeholder(__('Select a product'))
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($set, $get, ?string $state) {
                                        $inventory_products = InventoryProduct::get();
                                        if ($inventory_products->count() >= 1 && count($product = $inventory_products->where('product_id', $state)) > 0) {
                                                $set('theoretical_quantity', $product->last()?->physical_quantity);
                                            } else {
                                                $set('theoretical_quantity', 0);
                                        }
                                    }),
                                TextInput::make('physical_quantity')
                                    ->translateLabel()
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($set, $get, ?string $state) => $set('gap', $state - (int) $get('theoretical_quantity'))),
                                TextInput::make('theoretical_quantity')
                                    ->translateLabel()
                                    ->numeric()
                                    ->readOnly(),
                                TextInput::make('gap')
                                    ->translateLabel()
                                    ->numeric()
                                    ->readOnly(),
                            ])
                            ->hidden(fn ($get) => is_null($get('store_id')))
                            ->deletable(fn ($get) => collect($get('inventoryProducts'))->count() > 1)
                            ->collapsible()
                            ->columns(4)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->rowIndex(),
                TextColumn::make('user.name')
                    ->label(__('Created by'))
                    ->searchable(),
                TextColumn::make('store.name')
                    ->label(__('Store'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('M d, Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
        ];
    }    
}
