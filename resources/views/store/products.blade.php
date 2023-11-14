<x-store-head :title='"Produse | "' />
<x-store-header />
<main>
    <section class="products container">
        <livewire:store-products category="{{ $data }}" />
        {{-- filter part --}}

    </section>
</main>
<x-store-footer />
