<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Order;
use Carbon\Carbon;

class OrderChart extends LineChartWidget
{
    protected static ?string $heading = 'Orders Overview';

    protected function getData(): array
    {
        $orders = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->pluck('count'),
                ],
            ],
            'labels' => $orders->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d')), // Định dạng ngày tháng
        ];
    }
}
