<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Spatie\Permission\Models\Role;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\CreateUser;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Users Management';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup() : string 
    {
        return __('Users Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getModelLabel() : string 
    {
        return __('User');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function getEloquentQuery() : Builder 
    {
        return parent::getEloquentQuery()->latest();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\TextInput::make('login')
                        ->translateLabel()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name')
                        ->translateLabel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('password')
                        ->label(__('Password'))
                        ->password()
                        ->maxLength(255)
                        ->minValue(6)
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (Page $livewire) : bool
                            => ($livewire instanceof CreateUser)
                        )->label(fn (Page $livewire) : string
                            => ($livewire instanceof EditUser) ? 
                            __('Change password') : __('Password')),
                    Forms\Components\TextInput::make('phone')
                        ->label(__('Phone'))
                        ->tel()
                        ->unique(ignoreRecord: true)
                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                        ->startsWith(['65', '67', '68', '69'])
                        ->required()
                        ->length(9)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('location')
                        ->label(__('Location'))
                        ->maxLength(255),
                    Forms\Components\TextInput::make('nic')
                        ->label(__('NIC'))
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('Account Status'))
                        ->required(),
                ])
                ->columnSpan(2)
                ->columns(2),
                Section::make([
                    Forms\Components\Select::make('roles.name')
                        ->relationship('roles', 'id')
                        ->options(Role::pluck('name', 'id'))
                        ->label(__('Role'))
                        ->preload()
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('stores.name')
                        ->relationship('stores', 'name')
                        ->label(__('Store(s)'))
                        ->multiple()
                        ->preload()
                        ->required()
                        ->helperText(__('Select at least one item.'))
                ])
                ->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')
                    ->state(fn ($column) => $column->getRowLoop()->iteration),
                Tables\Columns\TextColumn::make('login')
                    ->label(__('Login'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->translateLabel()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label(__('Role(s)'))
                    ->listWithLineBreaks(false)
                    ->badge()
                    ->limitList(3)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Status'))
                    ->boolean()
                    ->tooltip(fn (Model $record) => $record->is_active ? __('Active') : __('Disabled')),
                Tables\Columns\TextColumn::make('nic')
                    ->label(__('NIC'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Action::make('Enable')
                        ->translateLabel()
                        ->action(function (User $record) {
                            $record->is_active = true;
                            $record->save();
                        })
                        ->hidden(fn (User $record): bool => $record->is_active)
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('...')
                                ->body(__("User's account has been enabled."))
                        ),
                    Action::make('Disable')
                        ->translateLabel()
                        ->action(function (User $record) {
                            $record->is_active = false;
                            $record->save();
                        })
                        ->visible(fn (User $record): bool => $record->is_active)
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('...')
                                ->body(__("User's account has been disabled."))
                        ),
                        Tables\Actions\DeleteAction::make(),
                ])->tooltip('Actions')
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }    
}