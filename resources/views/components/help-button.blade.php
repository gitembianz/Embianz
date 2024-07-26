<a class="help__button" href="tel: @if (app()->has('global_support_phone_number')) {!! app('global_support_phone_number') !!} @endif">
 @if (app()->has('label_support_phone_text'))
  {!! app('label_support_phone_text') !!}
 @endif
</a>
