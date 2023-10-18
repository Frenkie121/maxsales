<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                // ->successRedirectUrl(
                //     route('users.index')
                // )
                ->successNotification(
                    Notification::make()
                         ->success()
                         ->title(__('User registered'))
                         ->body(__('The user has been created successfully.')),
                 ),
        ];
    }
}
