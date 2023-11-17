<x-store-head :title='($data->seo_title ?? "") . " | "' />

<x-store-header />
<main>
    <section class="container">
        @livewire('store-show-product', ['productId' => $data->id])
    </section>
</main>
<x-store-footer />
