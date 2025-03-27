<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Customer;
use App\Models\Order;

class CustomerStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Tổng số khách hàng', Customer::count())
                ->icon('heroicon-o-user-group'),

            Card::make('Tổng số đơn hàng', Order::count())
                ->icon('heroicon-o-shopping-cart'),

            Card::make('Tổng doanh thu', number_format(Order::sum('total_price'), 0, ',', '.') . ' VNĐ')
                ->icon('heroicon-o-currency-dollar'),

            Card::make('Khách hàng có nhiều đơn nhất', function () {
                $topCustomer = Customer::withCount('orders')->orderByDesc('orders_count')->first();
                return $topCustomer ? "{$topCustomer->name} ({$topCustomer->orders_count} đơn)" : 'Chưa có dữ liệu';
            })->icon('heroicon-o-trophy'),
        ];
    }
}
