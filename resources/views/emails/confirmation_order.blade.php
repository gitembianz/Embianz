<!DOCTYPE html>
<html>

<head>
 <style>
  /* General Styles */
  body {
   font-family: 'Roboto', Arial, sans-serif;
   margin: 0;
   padding: 0;
   background-color: #f4f4f4;
   color: #333;
  }

  .container {
   width: 90%;
   max-width: 800px;
   margin: 20px auto;
   background-color: #fff;
   padding: 25px;
   border-radius: 10px;
   box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  /* Section Header */

  .section__title {
   font-size: 22px;
   font-weight: 400;
   margin: 10px 0;
   text-align: center;
   color: #555;
  }

  /* Order Details */
  .total__info {
   margin: 30px 0;
   padding-top: 10px;
  }

  .total__product {
   display: flex;
   align-items: center;
   justify-content: space-between;
   margin-bottom: 15px;
   padding: 10px 0;
  }

  .total__product img {
   width: 50px;
   height: 50px;
   object-fit: cover;
   border-radius: 6px;
   flex-shrink: 0;
  }

  .total__details {
   display: flex;
   justify-content: space-between;
   align-items: center;
   flex-grow: 1;
   margin-left: 10px;
  }

  .total__details .product__info {
   display: flex;
   flex-direction: column;
   gap: 5px;
   flex-grow: 1;
  }

  .product__name {
   font-size: 14px;
   font-weight: 400;
   color: #333;
  }

  .total__quantity {
   font-size: 14px;
   color: #777;
  }

  .total__price {
   font-size: 16px;
   font-weight: 400;
   color: #333;
   text-align: right;
   white-space: nowrap;
  }

  /* Total Items */
  .total__item {
   display: flex;
   justify-content: space-between;
   align-items: center;
   margin: 15px 0;
   font-size: 15px;
   font-weight: 400;
   padding: 10px 0;
   border-bottom: 1px solid #eaeaea;
  }

  .total__item span {
   display: inline-block;
   color: #555;
  }

  #final__amount {
   font-size: 18px;
   font-weight: 400;
   color: #222;
   text-align: right;
  }

  /* Shipping Address */
  .look__form {
   margin-top: 25px;
   padding: 20px;
   background-color: #f9f9f9;
   border-radius: 8px;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  }

  .look__form h3 {
   font-size: 20px;
   font-weight: 400;
   color: #444;
   margin-bottom: 15px;
  }

  .total__message {
   font-size: 14px;
   margin: 8px 0;
   color: #555;
   line-height: 1.6;
  }

  /* Buttons */
  .button {
   display: inline-block;
   padding: 10px 20px;
   font-size: 14px;
   font-weight: 4old;
   color: #fff;
   background-color: #007bff;
   text-decoration: none;
   border-radius: 5px;
   text-align: center;
   transition: background-color 0.3s ease;
  }

  .button:hover {
   background-color: #0056b3;
  }

  /* Responsive Styles */
  @media screen and (max-width: 768px) {
   .container {
    padding: 20px;
   }

   .total__product {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
   }

   .total__product img {
    width: 45px;
    height: 45px;
   }

   .total__details {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    flex-grow: 1;
    margin-left: 10px;
   }

   .product__info {
    max-width: calc(100% - 100px);
   }

   .product__name,
   .total__quantity {
    font-size: 14px;
   }

   .total__price {
    font-size: 14px;
   }

   .total__item {
    font-size: 14px;
   }

   #final__amount {
    font-size: 16px;
   }

   .look__form h3 {
    font-size: 18px;
   }

   .total__message {
    font-size: 13px;
   }
  }

  @media screen and (max-width: 480px) {
   .total__product {
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex-wrap: nowrap;
   }

   .total__product img {
    width: 40px;
    height: 40px;
   }

   .total__details {
    margin-left: 8px;
   }

   .total__price {
    font-size: 13px;
   }

   .total__item {
    font-size: 13px;
   }

   #final__amount {
    font-size: 15px;
   }

   .look__form h3 {
    font-size: 16px;
   }

   .total__message {
    font-size: 12px;
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
   <h3 class="section__title">
    @if (app()->has('label_mail_title'))
     {!! app('label_mail_title') !!}
    @endif
   </h3>
  </div>
  <section class="section__header container">
   <h3 class="section__title">
    @if (app()->has('label_mail_subtitle_p1'))
     {!! app('label_mail_subtitle_p1') !!}
    @endif
    {{ $order->order_number }}
    @if (app()->has('label_mail_subtitle_p2'))
     {!! app('label_mail_subtitle_p2') !!}
    @endif
   </h3>
  </section>
  <!-- Order Details -->
  <div class="total__info">
   @foreach ($order->orders as $cartItem)
    <div class="total__product">
     <span class="total__quantity">
      {{ $cartItem->quantity }} x
     </span>
     @php
      $productMedia = $cartItem->product->media->where('type', 'min')->first();
     @endphp

     @if ($productMedia)
      <img src="{{ $message->embed(public_path($productMedia->path . $productMedia->name)) }}"
       alt="{{ $cartItem->product->name }}" style="max-width: 200px;">
     @else
      <p>No product image available.</p>
     @endif
     {{ $cartItem->product->name }}
     <span class="total__price">
      {{ number_format($cartItem->quantity * $cartItem->price, 2, $decimal, $mill) }}
      @if (app()->has('global_currency_primary_symbol'))
       {!! app('global_currency_primary_symbol') !!}
      @endif
     </span>
    </div>
   @endforeach
   @if ($order->promotion_value > 0 || $order->voucher_value > 0)
    <div class="total__item">
     <span>
      @if (app()->has('label_mail_reducere'))
       {!! app('label_mail_reducere') !!}
      @endif
     </span>
     <span>
      -{{ number_format($order->promotion_value + $order->voucher_value, 2, $decimal, $mill) }} @if (app()->has('global_currency_primary_symbol'))
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
   <div class="total__item">
    <span>
     @if (app()->has('label_order_payment_method'))
      {!! app('label_order_payment_method') !!}
     @endif
    </span>
    <span>{{ $order->payment->description }}</span>
   </div>
  </div>

  <!-- Shipping Address -->
  <div class="look__form">
   <h3>
    @if (app()->has('label_mail_delivery_details'))
     {!! app('label_mail_delivery_details') !!}
    @endif
   </h3>
   <span class="total__message">
    @if (app()->has('label_order_fullname'))
     {!! app('label_order_fullname') !!}
    @endif
    {{ $order->account->addresses->where('type', 'shipping')->first()->first_name }}
    {{ $order->account->addresses->where('type', 'shipping')->first()->last_name }}
   </span><br>
   <span class="total__message">
    @if (app()->has('label_order_phone'))
     {!! app('label_order_phone') !!}
    @endif:
    {{ $order->account->addresses->where('type', 'shipping')->first()->phone }}
   </span><br>

   <span class="total__message">
    @if (app()->has('label_order_address1'))
     {!! app('label_order_address1') !!}
    @endif:
    {{ $order->account->addresses->where('type', 'shipping')->first()->address1 }}
    {{ $order->account->addresses->where('type', 'shipping')->first()->address2 }}
    ,{{ $order->account->addresses->where('type', 'shipping')->first()->city }}
    ,{{ $order->account->addresses->where('type', 'shipping')->first()->county }}
    ,{{ $order->account->addresses->where('type', 'shipping')->first()->country }}
    ,{{ $order->account->addresses->where('type', 'shipping')->first()->zipcode }}


   </span>
  </div>
  <br>
  <br>
  <div style="margin-top: 10px" class="total__item">
   <span class="total__message">
    @if (app()->has('label_mail_footer'))
     {!! app('label_mail_footer') !!}
    @endif
   </span>
  </div>
 </div>
</body>

</html>
