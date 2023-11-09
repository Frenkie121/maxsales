<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class ProductsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string|Htmlable|null
    {
        return __('Products chart');
    }

    protected function getData(): array
    {
        $data = $this->getProductsPerMonth();

        return [
            'datasets' => [
                [
                    'label' => __('Products created per month'),
                    'data' => [0, 0, 0, 0, 0, 0, 0, 0, 4, 5, 1, 0]
                ]
            ],
            'labels' => $data['months']
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getProductsPerMonth()
    {
        $now = now();
        $productsPerMonth = [];
        $months = collect(range(1, 12))->map(function($month) use ($now, $productsPerMonth){
            $count = Product::whereMonth('created_at', Carbon::parse($now->month($month)->format('Y-m')))->count();
            // dd($count, $now->month($month));
            $productsPerMonth[] = $count;

            return $now->month($month)->format('M');
        })->toArray();

        return [
            'productsPerMonth' => $productsPerMonth,
            'months' => $months,
        ];
    }
}
