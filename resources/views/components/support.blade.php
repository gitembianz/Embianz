<section>
 <div class="support">
  <div class="support__container container">
   <h2 class="support__text">
    @if (app()->has('label_support_title'))
     {!! app('label_support_title') !!}
    @endif
   </h2>
   <div class="support__categories">
    <div class="support__item">
     <div>
      <img srcset="/images/store/svg/headset.svg" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="headset" loading="eager" alt="headset">
      <h3 class="support__title">
       @if (app()->has('label_support_livechat_title'))
        {!! app('label_support_livechat_title') !!}
       @endif
      </h3>
     </div>
     <span class="support__subtitle">
      @if (app()->has('label_support_livechat_description'))
       {!! app('label_support_livechat_description') !!}
      @endif
     </span>
    </div>
    <div class="support__item">
     <div>
      <img srcset="/images/store/svg/truck.svg" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="truck" loading="eager" alt="truck">
      <h3 class="support__title">
       @if (app()->has('label_support_delivery_title'))
        {!! app('label_support_delivery_title') !!}
       @endif
      </h3>
     </div>
     <span class="support__subtitle">
      @if (app()->has('label_support_delivery_description'))
       {!! app('label_support_delivery_description') !!}
      @endif
     </span>
     <div class="support__brand">
      <img srcset="/images/store/brands/dhl.webp" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="dhl" loading="eager" class="support__brand--item" alt="dhl">
      <img srcset="/images/store/brands/Fan.webp" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="fan" loading="eager" class="support__brand--item" alt="fan">
     </div>
    </div>
    <div class="support__item">
     <div>
      <img srcset="/images/store/svg/shield.svg" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="shield" loading="eager" alt="shield">
      <h3 class="support__title">
       @if (app()->has('label_support_secure_title'))
        {!! app('label_support_secure_title') !!}
       @endif
      </h3>
     </div>
     <span class="support__subtitle">
      @if (app()->has('label_support_secure_description'))
       {!! app('label_support_secure_description') !!}
      @endif
     </span>
     <div class="support__brand">
      <img srcset="/images/store/brands/visa.webp" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="visa" loading="eager" class="support__brand--item" alt="visa">
      <img srcset="/images/store/brands/mastercard.webp" sizes="(max-width: 600px) 50px, 100px" width="50"
       height="50" title="mastercard" loading="eager" class="support__brand--item" alt="mastercard">
     </div>
    </div>
    <div class="support__item">
     <div>
      <img srcset="/images/store/svg/chat.svg" sizes="(max-width: 600px) 50px, 100px" width="50" height="50"
       title="chat" loading="eager" alt="chat">
      <h3 class="support__title">
       @if (app()->has('label_support_faq_title'))
        {!! app('label_support_faq_title') !!}
       @endif
      </h3>
     </div>
     <span class="support__subtitle">
      @if (app()->has('label_support_faq_description'))
       {!! app('label_support_faq_description') !!}
      @endif
     </span>
    </div>
   </div>
  </div>
 </div>
</section>
