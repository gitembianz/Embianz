<x-store-head :canonical="$page->route" :title="$page->name" :description="$page->description" />
@livewire('store-header')

<main>
 {!! $page->content !!}
</main>

<x-store-footer />
