<x-store-head :canonical="'search'" :title="'Caută | '" :description="'Căutare generală'" />

<x-store-header />
<main>
	@livewire("store-search", ["data" => $data])
</main>
<x-store-footer />
