<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Product;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class OrdersChart extends ChartWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): string|Htmlable|null
    {
        return __('Orders chart');
    }
    
    protected function getData(): array
    {
        $data = $this->getOrdersPerMonth();

        return [
            'datasets' => [
                [
                    'label' => __('Cashed orders per month'),
                    'data' => [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                ]
            ],
            'labels' => $data['months']
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getOrdersPerMonth()
    {
        $now = now();
        $ordersPerMonth = [];
        $months = collect(range(1, 12))->map(function($month) use ($now, $ordersPerMonth){
            $count = Product::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();
            $ordersPerMonth[] = $count;

            return $now->month($month)->format('M');
        })->toArray();

        return [
            'ordersPerMonth' => $ordersPerMonth,
            'months' => $months,
        ];
    }
}
