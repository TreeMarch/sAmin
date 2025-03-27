<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\UserChart;
use App\Filament\Widgets\ProductChart;
use App\Filament\Widgets\LatestUsers;
use App\Filament\Widgets\OrderChart;




class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-m-home';


    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            OrderChart::class,
            // UserChart::class,
            ProductChart::class,
            LatestUsers::class,
        ];
    }

}
