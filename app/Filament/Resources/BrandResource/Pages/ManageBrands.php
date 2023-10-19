<?php

namespace App\Filament\Resources\BrandResource\Pages;

use Filament\Actions;
use App\Filament\Resources\BrandResource;
use Filament\Resources\Pages\ManageRecords;

class ManageBrands extends ManageRecords
{
    protected static string $resource = BrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
