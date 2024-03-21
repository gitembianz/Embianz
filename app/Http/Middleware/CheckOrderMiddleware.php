<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Order;

class CheckOrderMiddleware
{
    public function handle($request, Closure $next)
    {
        $queryParameters = $request->query();

        if (isset($queryParameters['id'])) {
            $decodedOrderNumber = base64_decode($queryParameters['id']);

            $order = Order::where('order_number', $decodedOrderNumber)->first();

            if ($order) {
                return redirect()->route('my_order', ['order_number' => $queryParameters['id']]);
            }
        }

        return $next($request);
    }
}
