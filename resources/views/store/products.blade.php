<x-store-head :canonical="'storeproducts' . ($can ? '/' . $can : '')" :title='($data->seo_title ?? "Produse") . " | "' :description='$data->meta_description ?? $data->name ?? ""' />

<x-store-header />
<main>
 <livewire:store-products category="{{ $data }}" />
</main>
<x-store-footer />
