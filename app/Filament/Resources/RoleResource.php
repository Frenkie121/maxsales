<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoleResource\RelationManagers;
use Filament\Forms\Components\CheckboxList;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup() : string 
    {
        return __('Configuration');
    }

    public static function getNavigationLabel(): string
    {
        return __('Roles');
    }

    public static function getModelLabel() : string 
    {
        return __('Role');
    }

    public static function getPluralModelLabel() : string 
    {
        return __('Roles');
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
                        TextInput::make('name')
                            ->translateLabel()
                            ->required()
                            ->unique(ignoreRecord: true),
                        CheckboxList::make('Permissions')
                            ->relationship('permissions', 'name')
                            ->required()
                            ->helperText(__('Select at least one item.'))
                            ->columns(4)
                    ]),
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
                Tables\Actions\DeleteAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoles::route('/'),
        ];
    }    
}
