<x-store-head :title='($data->seo_title ?? "") . " | "' />

<x-store-header />
<main>

        @livewire('store-show-product', ['productId' => $data->id])
</main>
<x-store-footer />
