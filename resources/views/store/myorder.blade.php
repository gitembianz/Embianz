<x-store-head :canonical="'myorder'" :title="'Vizualizează comanda | '" :description="'Vizualizează comanda'"/>
<x-store-header />
<main>
    <section>
      <section class="section__header container">
        <h1 class="section__title">
          Comanda {{ $order->order_number }}
        </h1>
      </section>
			<div class="checkout container">
				<!------------------------------------------------------>
				<!-------------------- Step Numbers -------------------->
				<div class="step__container">
          <div class="step active" data-step="In asteptare">1</div>
					<span class="step__line @if ($order->status_id == app('global_order_check_payment') || $order->status_id == app('global_order_hold')) half @else full @endif"></span>
          <div class="step @if ($order->status_id == app('global_order_processing') || $order->status_id == app('global_order_cancelled') || $order->status_id == app('global_order_delivered')) active @endif" data-step="In procesare">2</div>
					<span class="step__line @if ($order->status_id == app('global_order_processing')) half @elseif ($order->status_id == app('global_order_delivered') || $order->status_id == app('global_order_cancelled')) full @endif "></span>
          @if ($order->status_id == app('global_order_cancelled'))
          <div class="step canceled @if ($order->status_id == app('global_order_cancelled')) active @endif" data-step="Anulata">3</div>
          @else
          <div class="step  @if ($order->status_id == app('global_order_delivered')) active @endif" data-step="Livrata">3</div>
          @endif
				</div>
				<div class="total__container">

						<!---------------------------------------------------->
						<!-------------- Checkout List of Forms -------------->
						<!---------------------------------------------------->
							<div class="look__form">
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de facturare &check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->first_name }}</strong>
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->last_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Telefon:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->phone }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Email:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->email }}</strong></span>
								<!---------------------------------------------------->
								@if ($order->account->type == 'juridic')

								<span class="total__message">Companie:
									<strong>{{ $order->account->company_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod de înregistrare:
									<strong>{{ $order->account->registration_code }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Număr de înregistrare:
									<strong>{{ $order->account->registration_number }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Denumirea Bancii:
									<strong>{{ $order->account->bank_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">COnt IBAN:
									<strong>{{ $order->account->account }}</strong></span>
								<!---------------------------------------------------->
								@endif
								<span class="total__message">Adresa:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->address1 }}</strong>
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $order->account->addresses->where("type", "billing")->first()->zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
								<!---------------------------------------------------->
								<!------------- Checkout Header Name --------------->
								<h3>
									Informații de livrare &check;
								</h3>
								<!----------- End Checkout Header Name ------------->
								<!---------------------------------------------------->
								<!------------- Checkout List of Items --------------->
								<span class="total__message">Nume și Prenume:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->first_name }}</strong>
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->last_name }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Telefon:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->phone }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Email:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->email }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Adresa:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->address1 }}</strong>
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->address2 }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Țara:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->country }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Județ:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->county }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Localitate (oraș, comună sau sat):
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->city }}</strong></span>
								<!---------------------------------------------------->
								<span class="total__message">Cod Poștal:
									<strong>{{ $order->account->addresses->where("type", "shipping")->first()->zipcode }}</strong></span>
								<!----------- End Checkout List of Items ------------->
								<!---------------------------------------------------->
							</div>
							<!---------------------------------------------------->
						<!---------------------------------------------------->
						<div class="total__info">
							@foreach ($order->orders as $cartItem)
								<div class="total__product">
									<span class="total__quantity">
										{{ $cartItem->quantity }} x
									</span>
									@if ($cartItem->product->media->where("type", "min")->first())
										<img loading="lazy" class="cart__list--img" src="/{{ $cartItem->product->media->where("type", "min")->first()->path }}{{ $cartItem->product->media->where("type", "min")->first()->name }}" alt="{{ $cartItem->product->media->where("type", "min")->first()->name }} {{ $cartItem->product->name }}">
									@else
										<img loading="lazy" class="cart__list--img" src="/images/store/default/default70.webp" alt="something wrong">
									@endif
									<a href="{{ route("product", ["product" => $cartItem->product->seo_id !== null && $cartItem->product->seo_id !== "" ? $cartItem->product->seo_id : $cartItem->product->id]) }}" target="_blank" class="total__name">{{ $cartItem->product->name }}</a>
									<span class="total__price">

										{{-- {{ $cartItem->product->price }} --}}
										<?php $currency = $order->currency->name; ?>
										{{ number_format($cartItem->quantity * $cartItem->price, 2, ",", ".") }}
										{{ $currency }}
									</span>

								</div>
							@endforeach

							<div class="total__item">
								<span>Modalitate de plată</span>
								<span>{{ $order->payment->description }}</span>
							</div>
							<div class="total__item">
								<span>Cost livrare</span>
								<span>
									@if (app("global_delivery_price") == 0)
										Gratuit
									@else
										{{ number_format(app("global_delivery_price"), 2, ",", ".") }}
										{{ $currency }}
									@endif
								</span>
							</div>
							@if ($order->voucher && $order->voucher_value > 0)
								<div class="total__item">
									<span>Voucher:</span>
									<span>
										-{{ number_format($order->voucher_value, 2, ",", ".") }}
										{{ $currency }}

									</span>
								</div>
							@endif
							<div class="total__item">
								<span>Total</span>

								<span>{{ number_format($order->final_amount, 2, ",", ".") }}
									{{ $currency }}
								</span>
							</div>
						</div>
						<!------------ End Checkout List of Forms ------------>
						<!---------------------------------------------------->
					</div>
			</div>

    </section>
 <x-support />
</main>
<x-store-footer />
