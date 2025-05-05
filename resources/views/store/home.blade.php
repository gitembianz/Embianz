@php
    $title = app()->bound('global_mainpage_metatitle') ? app('global_mainpage_metatitle') : '';
    $description = app()->bound('global_mainpage_metadescription') ? app('global_mainpage_metadescription') : '';
@endphp

<x-store-head :title="$title" :description="$description" :preload="$preload" />

@livewire('store-header')
@livewire('store-main')
<x-store-footer />
