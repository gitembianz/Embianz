<x-store-head :title='($data->seo_title ?? "Blog")' :description='$data->meta_description ?? $data->name ?? ""' :preload="$preload" />
    @livewire('store-header')
    <main>
        @livewire('store-blog', ['category' => $data])
    </main>
<x-store-footer />
