<x-dashboardheader />
<x-dashboardnavbar />
{{-- Display session message --}}
@if (session()->has('message') && session()->has('item_name') && session()->has('item_id'))
    <div class="alert__session" id="alertevent">
        <Span class="alert__session-text">{!! session('message') !!} , click for view - <a
                href="{{ route('show_product', ['id' => session('item_id')]) }}">{{ session('item_name') }}</a></Span>
        <button class="alert__session-btn" type="button"
            onclick="document.getElementById('alertevent').style.display='none'" data-bs-dismiss="alert"
            aria-hidden="true">
            <svg>
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <script>
        const alertEvent = document.getElementById("alertevent");
        header.style.marginBottom = '4rem';
        alertEvent.style.opacity = '1';

        setTimeout(function() {
            alertEvent.style.opacity = '0';
            setTimeout(function() {
                alertEvent.remove();
                header.style.marginBottom = '0';
            }, 500);
        }, 2000);
    </script>
@endif
{{-- End Section session message --}}
<x-dashboardsidebar />

{{-- Page content start --}}
<section class="content">
    <form class="item" action="{{ url('/new_products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Item Header --}}
        <div class="item__header">
            <h1 id="title" class="item__header-title">{{ __('New Product') }}</h1>
            <div class="item__header-buttons">
                <a class="item__header-btn" href="{{ route('products') }}">All Products</a>
                <button class="item__header-btn" id="resetform" type="reset">Clear form</button>
            </div>
        </div>
        {{-- Item Upload --}}
        <div class="item__upload">
            <input type="file" name="media[]" id="imgUpload" multiple
                accept="image/*,video/*"onchange="filesManager(this.files)">

            <label class="item__upload-btn" for="imgUpload">
                <svg>
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                </svg>
                Upload Media
            </label>

            <table id="imageTable" class="table"></table>
        </div>
        {{-- Item Form --}}
        <div class="item__form">
            <div class="item__form-input">
                <input type="text" name="product_name" required>
                <span> Product Name</span>
            </div>
            <div class="item__form-input">
                <select id="select-category" name="category">
                    <option value="" selected="">Select a category</option>
                    @foreach ($categories as $category_name)
                        <option value="{{ $category_name }}">{{ $category_name }}</option>
                    @endforeach
                </select>
                <span>Product Category</span>
            </div>
            <div class="item__form-input">
                <input type="date" id="start_date" min="0" name="start_date">
                <span>Product Start Date</span>
            </div>
            <div class="item__form-input">
                <input type="date" id="end_date" name="end_date">
                <span>Product End Date</span>
            </div>
            <div class="item__form-input item__form-long">
                <input type="text" name="short_description" required>
                <span>Product Short Description</span>
            </div>
            <div class="item__form-input">
                <input type="number" min="0" name="quantity" required>
                <span>Product Quantity</span>
            </div>
            <div class="item__form-input">
                <select id="select-category" name="status">
                    <?php
                    $status = ['active', 'inactive', 'low stock'];
                    ?>
                    <option value="" selected="">Select a status</option>
                    @foreach ($status as $status_name)
                        <option value="{{ $status_name }}">{{ $status_name }}</option>
                    @endforeach
                </select>
                <span>Product Status</span>
            </div>
            <div class="item__form-input item__form-long">
                <textarea name="long_description" required></textarea>
                <span>Product Long Description</span>
            </div>
            <div class="item__form-input item__form-long">
                <input type="text" name="seo_title" required>
                <span>SEO Title</span>
            </div>
            <input class="item__form-btn item__form-long" type="submit" value="Add new" name="submit">
        </div>
    </form>
    <a href="#" class="top-up-btn" id="topUp">
        <svg>
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </a>
</section>
{{-- page content end --}}
<x-dashboardright />
<x-dashboardmediahanddler />
<x-dashboardscript />
<x-dashboardfooter />
