<x-store-head :canonical="'storeproducts' . ($can ? '/' . $can : '')" :description="'All Products'" :title="'Produse | '" />

<x-store-header />
<main>
 <livewire:store-products category="{{ $data }}" />
</main>
<x-store-footer />
