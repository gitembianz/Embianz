<x-store-head />
<x-store-header />
<main>
    @livewire("store-show-product", ["product" => $data], key($data->id))
</main>
<x-store-footer />
