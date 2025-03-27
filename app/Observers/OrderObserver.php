<?php

namespace App\Observers;

use Illuminate\Support\Facades\Notification;
use App\Models\Order;
use Mockery\Matcher\Not;

class OrderObserver
{
    public function created(Order $order):void
    {
        Notification::make()
            ->title('Đơn hàng mới' . $order->name)
            ->sendToDatabase($order->user);
    }
}
