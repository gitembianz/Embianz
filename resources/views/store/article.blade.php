<x-store-head :image="optional($data->media->where('type', 'full')->first())->path . optional($data->media->where('type', 'full')->first())->name"  :canonical="'article/' . ($data->seo_id ?? $data->id)" :title='($data->seo_title ?? "")' :description='$data->meta_description ?? $data->name' :preload="$preload" />
@livewire('store-header')
<main>
	@livewire("store-show-article", ["articleId" => $data->id])
</main>
<x-store-footer />
