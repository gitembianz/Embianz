<x-store-head :title="getTitle()" :description="getDescription()" />

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

<x-store-header />
<img src="/images/store/default/default300.webp" alt="something wrong" style="width:50%">
<x-store-footer />
