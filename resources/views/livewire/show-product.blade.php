<div>
  <div class="item__header">
    <h1 class="item__header-title" id="title">Product- {{ $product->name }}</h1>
    <div class="item__header-buttons">
        <a class="item__header-btn" href="{{ route('products') }}">Back</a>
        <a class="item__header-btn" href="{{ route('add_product') }}">New</a>
        <input class="item__header-btn" type="button" value="Edit" name="edit" id="edit">
        <input class="item__header-btn delete" type="button" value="Delete" name="delete">
    </div>
</div>
</div>
