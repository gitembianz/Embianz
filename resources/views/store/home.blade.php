<x-store-head :title="getTitle()" :description="getDescription()" :preload="$preload" />

@php
 function getTitle()
 {
     try {
         return app('global_mainpage_metatitle');
     } catch (\Throwable $e) {
         return '';
     }
 }

 function getDescription()
 {
     try {
         return app('global_mainpage_metadescription');
     } catch (\Throwable $e) {
         return '';
     }
 }
@endphp

@livewire('store-header')
@livewire('store-main')
<x-store-footer />
