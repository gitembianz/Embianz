<x-store-head :canonical="'product/' . ($data->seo_id ?? $data->id)" :title='($data->seo_title ?? "") . " | "' :description='$data->short_description ?? ""' />

<x-store-header />
<main>

	@livewire("store-show-product", ["productId" => $data->id])
</main>
<x-store-footer />
