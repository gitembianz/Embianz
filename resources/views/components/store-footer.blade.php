<livewire:store-footer />
<script src="/script/store/general.js" async defer></script>
@livewireScripts
@if (app()->has('global_script_body-bottom'))
 {!! app('global_script_body-bottom') !!}
@endif
</body>

</html>
