<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function getNavigationGroup() : string 
    {
        return __('Configuration');
    }

    public static function getNavigationLabel(): string
    {
        return __('Stores');
    }

    public static function getModelLabel() : string 
    {
        return __('Store');
    }

    public static function getPluralModelLabel() : string 
    {
        return __('Stores');
    }

    // public static function createButtonLabel() : string
    // {
    //     return __('Create new');
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                        ->schema([
                            TextInput::make('name')
                                        ->required()
                                        ->unique(ignoreRecord: true),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('#')
                    ->state(fn ($column) => $column->getRowLoop()->iteration),
                TextColumn::make('name')
                        ->label(__('Name')
                    )->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                        ->dateTime('d-M-Y')
                        ->label(__('Created at')
                    )->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageStores::route('/'),
        ];
    }
}
