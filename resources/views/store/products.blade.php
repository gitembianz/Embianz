<x-store-head :title='"Produse | "' />
<x-store-header />
<main>
    <livewire:store-products category="{{ $data }}" />
</main>
<x-store-footer />
