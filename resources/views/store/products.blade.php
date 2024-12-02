<x-store-head :title='($data->seo_title ?? "Produse")' :description='$data->meta_description ?? $data->name ?? ""' :preload="$preload" />
    @livewire('store-header')
    <main>
        @livewire('store-products', ['category' => $data])
    </main>
<x-store-footer />
