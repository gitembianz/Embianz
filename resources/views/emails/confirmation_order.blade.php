<!DOCTYPE html>
<html>

<head>
 <style>
  /* General Styles */
  body {
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
   background-color: #f9f9f9;
   color: #333;
  }

  .container {
   width: 100%;
   max-width: 800px;
   margin: 0 auto;
   background: #ffffff;
   padding: 30px;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .section__header {
   text-align: center;
   margin-bottom: 30px;
  }

  .section__title {
   font-size: 28px;
   font-weight: bold;
   color: #444;
   margin: 0;
  }

  h2,
  h3 {
   color: #555;
   margin: 20px 0;
  }

  .total__container {
   margin-top: 30px;
  }

  .look__form,
  .total__info {
   margin-bottom: 20px;
   padding: 20px;
   border: 1px solid #ddd;
   border-radius: 5px;
   background-color: #f8f8f8;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .total__message {
   display: block;
   margin: 8px 0;
   font-size: 16px;
   color: #333;
  }

  .total__message strong {
   font-weight: bold;
   color: #333;
  }

  .total__item {
   display: flex;
   justify-content: space-between;
   margin-bottom: 15px;
   font-size: 16px;
  }

  .total__product {
   display: flex;
   justify-content: space-between;
   align-items: center;
   margin-bottom: 15px;
   font-size: 16px;
  }

  .total__quantity {
   margin-right: 10px;
  }

  .cart__list--img {
   width: 60px;
   height: auto;
   margin-right: 15px;
  }

  .total__price {
   font-weight: bold;
   color: #444;
  }

  /* Mobile Styles */
  @media only screen and (max-width: 600px) {
   .container {
    padding: 15px;
   }

   .section__title {
    font-size: 24px;
   }

   .total__product {
    flex-wrap: wrap;
    margin-bottom: 10px;
   }

   .total__price {
    width: 100%;
    text-align: right;
    margin-top: 10px;
   }

   .total__item {
    font-size: 14px;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 12px;
   }

   .cart__list--img {
    width: 50px;
    height: auto;
    margin-right: 10px;
   }
  }

  /* Desktop Styles */
  @media only screen and (min-width: 601px) {
   .section__header {
    text-align: left;
   }

   .total__product {
    margin-bottom: 15px;
   }

   .cart__list--img {
    margin-right: 20px;
   }
  }
 </style>
</head>

<body>
 @php
  if (app()->has('global_numberformat_element')) {
      if (app('global_numberformat_element') === '.') {
          $mill = '.';
          $decimal = ',';
      } else {
          $mill = ',';
          $decimal = '.';
      }
  } else {
      $mill = '.';
      $decimal = ',';
  }
 @endphp

 <div class="container">
  <div class="section__header">
   <h2>
    @if (app()->has('label_order_default_text_confirmation'))
     {!! app('label_order_default_text_confirmation') !!}
    @endif
   </h2>
  </div>

  <!-- Billing Address -->
  <div class="look__form">
   <h3>
    @if (app()->has('label_order_billing_check'))
     {!! app('label_order_billing_check') !!}
    @endif
   </h3>
   <span class="total__message">
    @if (app()->has('label_order_fullname'))
     {!! app('label_order_fullname') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'billing')->first()->first_name }}</strong>
    <strong>{{ $order->account->addresses->where('type', 'billing')->first()->last_name }}</strong>
   </span>
   <span class="total__message">
    @if (app()->has('label_order_phone'))
     {!! app('label_order_phone') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'billing')->first()->phone }}</strong>
   </span>
   <span class="total__message">
    @if (app()->has('label_order_address1'))
     {!! app('label_order_address1') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'billing')->first()->address1 }}</strong>
    <strong>{{ $order->account->addresses->where('type', 'billing')->first()->address2 }}</strong>
   </span>
   <span class="total__message">
    @if (app()->has('label_order_city'))
     {!! app('label_order_city') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'billing')->first()->city }}</strong>
   </span>
  </div>

  <!-- Shipping Address -->
  <div class="look__form">
   <h3>
    @if (app()->has('label_order_delivery_check'))
     {!! app('label_order_delivery_check') !!}
    @endif
   </h3>
   <span class="total__message">
    @if (app()->has('label_order_fullname'))
     {!! app('label_order_fullname') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'shipping')->first()->first_name }}</strong>
    <strong>{{ $order->account->addresses->where('type', 'shipping')->first()->last_name }}</strong>
   </span>
   <span class="total__message">
    @if (app()->has('label_order_phone'))
     {!! app('label_order_phone') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'shipping')->first()->phone }}</strong>
   </span>
   <span class="total__message">
    @if (app()->has('label_order_address1'))
     {!! app('label_order_address1') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'shipping')->first()->address1 }}</strong>
    <strong>{{ $order->account->addresses->where('type', 'shipping')->first()->address2 }}</strong>
   </span>
   <span class="total__message">
    @if (app()->has('label_order_city'))
     {!! app('label_order_city') !!}
    @endif:
    <strong>{{ $order->account->addresses->where('type', 'shipping')->first()->city }}</strong>
   </span>
  </div>

  <!-- Order Details -->
  <div class="total__info">
   @foreach ($order->orders as $cartItem)
    <div class="total__product">
     <span class="total__quantity">
      {{ $cartItem->quantity }} x
     </span>
     @if ($cartItem->product->media->where('type', 'min')->first())
      <img class="cart__list--img" title="{{ $cartItem->product->name }}"
       src="/{{ $cartItem->product->media->where('type', 'min')->first()->path }}{{ $cartItem->product->media->where('type', 'min')->first()->name }}"
       alt="{{ $cartItem->product->media->where('type', 'min')->first()->name }} {{ $cartItem->product->name }}">
     @else
      <img title="default image" class="cart__list--img" src="/images/store/default/default70.webp"
       alt="something wrong">
     @endif
     <a href="{{ route('product', ['product' => $cartItem->product->seo_id ?? $cartItem->product->id]) }}"
      target="_blank" class="total__name">{{ $cartItem->product->name }}</a>
     <span class="total__price">
      {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
      @if (app()->has('global_currency_primary_symbol'))
       {!! app('global_currency_primary_symbol') !!}
      @endif
     </span>
    </div>
   @endforeach
   <div class="total__item">
    <span>
     @if (app()->has('label_order_payment_method'))
      {!! app('label_order_payment_method') !!}
     @endif
    </span>
    <span>{{ $order->payment->description }}</span>
   </div>
   @if ($order->promotion_value > 0)
    <div class="total__item">
     <span>
      @if (app()->has('label_cart_promotion_tag'))
       {!! app('label_cart_promotion_tag') !!}
      @endif
     </span>
     <span>
      -{{ number_format($order->promotion_value, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
       {!! app('global_currency_primary_symbol') !!}
      @endif
     </span>
    </div>
   @endif
   <div class="total__item">
    <span>
     @if (app()->has('label_cart_delivery_tag'))
      {!! app('label_cart_delivery_tag') !!}
     @endif
    </span>
    <span>
     @if ($order->delivery_price == 0)
      @if (app()->has('label_cart_delivery_free'))
       {!! app('label_cart_delivery_free') !!}
      @endif
     @else
      {{ number_format($order->delivery_price, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
       {!! app('global_currency_primary_symbol') !!}
      @endif
     @endif
    </span>
   </div>
   @if ($order->voucher && $order->voucher_value > 0)
    <div class="total__item">
     <span>
      @if (app()->has('label_cart_voucher_tag'))
       {!! app('label_cart_voucher_tag') !!}
      @endif
     </span>
     <span>
      -{{ number_format($order->voucher_value, 2, $decimal, $mill) }}
      @if (app()->has('global_currency_primary_symbol'))
       {!! app('global_currency_primary_symbol') !!}
      @endif
     </span>
    </div>
   @endif
   <div class="total__item">
    <span>
     @if (app()->has('label_cart_total_tag'))
      {!! app('label_cart_total_tag') !!}
     @endif
    </span>
    <span id="final__amount">{{ number_format($order->final_amount, 2, $decimal, $mill) }}
     @if (app()->has('global_currency_primary_symbol'))
      {!! app('global_currency_primary_symbol') !!}
     @endif
    </span>
   </div>
  </div>
 </div>
</body>

</html>
