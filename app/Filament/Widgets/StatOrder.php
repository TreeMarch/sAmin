<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatOrder extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Pending Orders', Order::where('status', 'pending')->count())
                ->description('Orders waiting for approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Card::make('Approved Orders', Order::where('status', 'approved')->count())
                ->description('Orders approved and processing')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Card::make('Shipped Orders', Order::where('status', 'shipped')->count())
                ->description('Orders on their way to customers')
                ->descriptionIcon('heroicon-o-truck')
                ->color('info'),

            Card::make('Completed Orders', Order::where('status', 'completed')->count())
                ->description('Orders successfully delivered')
                ->descriptionIcon('heroicon-o-check')
                ->color('success'),

            Card::make('Canceled Orders', Order::where('status', 'canceled')->count())
                ->description('Orders that were canceled')
                ->descriptionIcon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
