<section>
	<!------------------------------------------------------>
	<!------------------- Support Center ------------------>
	<div class="support">
		<div class="support__container container">
			<h2 class="support__text">
				@if (app()->has('label_support_title')){!! app('label_support_title') !!} @endif</h2>
			<div class="support__categories">
				<div class="suport__item">
					<div>
						<img loading="eager" src="/images/store/svg/headset.svg" alt="headset">
						<h3 class="support__title">@if (app()->has('label_support_livechat_title')){!! app('label_support_livechat_title') !!} @endif</h3>
					</div>
					<span class="support__subtitle">@if (app()->has('label_support_livechat_description')){!! app('label_support_livechat_description') !!} @endif</span>
				</div>
				<div class="suport__item">
					<div>
						<img loading="eager" src="/images/store/svg/truck.svg" alt="truck">
						<h3 class="support__title">@if (app()->has('label_support_delivery_title')){!! app('label_support_delivery_title') !!} @endif</h3>
					</div>
					<span class="support__subtitle">@if (app()->has('label_support_delivery_description')){!! app('label_support_delivery_description') !!} @endif</span>
					<div class="support__brand">
						<img loading="eager" class="support__brand--item" src="/images/store/brands/dhl.webp" alt="dhl">
						<img loading="eager" class="support__brand--item" src="/images/store/brands/Fan.webp" alt="fan">
					</div>
				</div>
				<div class="suport__item">
					<div>
						<img loading="eager" src="/images/store/svg/shield.svg" alt="shield">
						<h3 class="support__title">@if (app()->has('label_support_secure_title')){!! app('label_support_secure_title') !!} @endif</h3>
					</div>
					<span class="support__subtitle">@if (app()->has('label_support_secure_description')){!! app('label_support_secure_description') !!} @endif</span>
					<div class="support__brand">
						<img loading="eager" class="support__brand--item" src="/images/store/brands/visa.webp" alt="visa">
						<img loading="eager" class="support__brand--item" src="/images/store/brands/mastercard.webp" alt="mastercard">
					</div>
				</div>
				<div class="suport__item">
					<div>
						<img loading="eager" src="/images/store/svg/chat.svg" alt="chat">
						<h3 class="support__title">@if (app()->has('label_support_faq_title')){!! app('label_support_faq_title') !!} @endif</h3>
					</div>
					<span class="support__subtitle">@if (app()->has('label_support_faq_description')){!! app('label_support_faq_description') !!} @endif</a></span>
				</div>
			</div>
		</div>
	</div>
</section>
