<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;


class CustomerController extends Controller
{
    public function show($id)
    {
        $customer = Customer::with(['orders.product'])->findOrFail($id);

        return view('customers.show', compact('customer'));
    }
}
