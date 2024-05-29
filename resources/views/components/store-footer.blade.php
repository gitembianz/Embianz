<livewire:store-footer />
<script src="/script/store/general.js" defer></script>
<script type="text/partytown" src="/script/third-party.js"></script>
@livewireScripts
@if (app()->has('global_script_body-bottom'))
 {!! app('global_script_body-bottom') !!}
@endif
</body>

</html>
