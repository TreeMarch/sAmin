<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Users', User::count())
                ->description($this->getUserGrowth())
                ->descriptionIcon($this->getUserGrowthIcon(),'before')
                ->color($this->getUserGrowthColor())
                ->icon('heroicon-o-user-group'),

            Card::make('Total Products', Product::count())
                ->description($this->getProductGrowth())
                ->descriptionIcon($this->getProductGrowthIcon(),'before')
                ->color($this->getProductGrowthColor())
                ->icon('heroicon-o-shopping-bag'),

            Card::make('Total Categories', Category::count())
                ->description($this->getCategoryGrowth())
                ->descriptionIcon($this->getCategoryGrowthIcon())
                ->color($this->getCategoryGrowthColor())
                ->icon('heroicon-o-folder'),

            Card::make('Total Orders', Order::count())
                ->description($this->getOrderGrowth())
                ->descriptionIcon($this->getOrderGrowthIcon(),'before')
                ->color($this->getOrderGrowthColor())
                ->icon('heroicon-o-shopping-cart'),
        ];
    }


    private function getUserGrowth(): string
    {
        return $this->calculateGrowth(User::class);
    }

    private function getUserGrowthIcon(): string
    {
        return $this->calculateGrowthIcon(User::class);
    }

    private function getUserGrowthColor(): string
    {
        return $this->calculateGrowthColor(User::class);
    }

    private function getProductGrowth(): string
    {
        return $this->calculateGrowth(Product::class);
    }

    private function getProductGrowthIcon(): string
    {
        return $this->calculateGrowthIcon(Product::class);
    }

    private function getProductGrowthColor(): string
    {
        return $this->calculateGrowthColor(Product::class);
    }

    private function getCategoryGrowth(): string
    {
        return $this->calculateGrowth(Category::class);
    }

    private function getCategoryGrowthIcon(): string
    {
        return $this->calculateGrowthIcon(Category::class);
    }

    private function getCategoryGrowthColor(): string
    {
        return $this->calculateGrowthColor(Category::class);
    }

    private function getOrderGrowth(): string
    {
        return $this->calculateGrowth(Order::class);
    }

    private function getOrderGrowthIcon(): string
    {
        return $this->calculateGrowthIcon(Order::class);
    }

     private function getOrderGrowthColor(): string
    {
        return $this->calculateGrowthColor(Order::class);
    }



    private function calculateGrowth(string $model): string
    {
        $todayCount = $model::count();
        $yesterdayCount = $model::whereDate('created_at', Carbon::yesterday())->count();
        $difference = $todayCount - $yesterdayCount;

        if ($difference === 0) {
            return 'No change';
        }

        $percentChange = $yesterdayCount > 0 ? round(($difference / $yesterdayCount) * 100, 2) : 100;
        return ($difference > 0 ? "+$percentChange%" : "$percentChange%") . ' from yesterday';
    }

    private function calculateGrowthIcon(string $model): string
    {
        $todayCount = $model::count();
        $yesterdayCount = $model::whereDate('created_at', Carbon::yesterday())->count();

        return $todayCount >= $yesterdayCount ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down';
    }

    private function calculateGrowthColor(string $model): string
    {
        $todayCount = $model::count();
        $yesterdayCount = $model::whereDate('created_at', Carbon::yesterday())->count();

        if ($todayCount > $yesterdayCount) {
            return 'success';
        } elseif ($todayCount < $yesterdayCount) {
            return 'danger';
        } else {
            return 'primary';
        }
    }
}
