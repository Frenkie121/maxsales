<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup() : string 
    {
        return __('Configuration');
    }

    public static function getNavigationLabel(): string
    {
        return __('Permissions');
    }

    public static function getModelLabel() : string 
    {
        return __('Permission');
    }

    public static function getPluralModelLabel() : string 
    {
        return __('Permissions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->translateLabel()
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
                    ->translateLabel()
                    ->formatStateUsing(fn (string $state) : string => trans($state))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('d-M-Y')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePermissions::route('/'),
        ];
    }    
}
