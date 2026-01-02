<livewire:store-footer :page="$page" />
<script src="/script/store/general.js" defer></script>
@if (app()->has('global_confetti') && app('global_confetti') === 'true')
        <script src="/script/confetti.js" defer></script>
    @endif

@if (app()->has('global_script_body-bottom'))
 {!! app('global_script_body-bottom') !!}
@endif
@livewireScripts
</body>

</html>
