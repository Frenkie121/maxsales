<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\User;
use App\Models\Store;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('Stores'), Store::count())
                ->chart([4, 5, 3, 8])
                ->color('success'),
            Stat::make(__('Staff members'), User::count() - User::role('Administrator')->count())
                ->chart([4, 5, 3, 7, 8, 8, 8])
                ->color('warning'),
            Stat::make(__('Products'), Product::count())
                ->chart([4, 5, 3, 7, 9, 14, 25, 8])
                ->color('danger'),
        ];
    }
}
