<div>
    <div class="product">
        <div class="product__preview">
            <div class="product__image">
                <button class="product__image-prev">
                    <svg>
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <img class="thumbnail-active" src="/images/store/bottle1.png" alt="Product Image" id="openModal">
                <button class="product__image-next">
                    <svg>
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
            <div class="product__nails">
                <img class="thumbnail" src="/images/store/bottle-img1.jpeg" alt="Thumbnail 1">
                <img class="thumbnail" src="/images/store/bottle-img2.webp" alt="Thumbnail 2">
                <img class="thumbnail" src="/images/store/bottle-img3.webp" alt="Thumbnail 3">
                <img class="thumbnail" src="/images/store/bottle-img4.png" alt="Thumbnail 1">
                <img class="thumbnail" src="/images/store/bottle2.png" alt="Thumbnail 2">
                <img class="thumbnail" src="/images/store/bottle3.png" alt="Thumbnail 3">
                <img class="thumbnail" src="/images/store/bottle1.png" alt="Thumbnail 1">
                <img class="thumbnail" src="/images/store/bottle2.png" alt="Thumbnail 2">
                <img class="thumbnail" src="/images/store/bottle3.png" alt="Thumbnail 3">
            </div>
            <div class="product__modal" id="modal">
                <div class="slideshow">
                    <!-- Full-width images with number and caption text -->
                    <div class="slideshow--slides">
                        <img src="/images/store/bottle-img1.jpeg">
                    </div>
                    <div class="slideshow--slides">
                        <img src="/images/store/bottle-img2.webp">
                    </div>
                    <div class="slideshow--slides">
                        <img src="/images/store/bottle-img3.webp">
                    </div>
                    <div class="slideshow--slides">
                        <img src="/images/store/bottle-img4.png">
                    </div>
                    <div class="slideshow--slides">
                        <img src="/images/store/bottle2.png">
                    </div>

                    <div class="slideshow--slides">
                        <img src="/images/store/bottle3.png">
                    </div>

                    <!-- Next and previous buttons -->
                    <a class="prev" onclick="plusSlides(-1)">
                        <svg>
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                    </a>
                    <a class="next" onclick="plusSlides(1)">
                        <svg>
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                    <!-- Dots buttons -->
                    <div class="dots" id="dots">
                    </div>
                    <button id="closeModal">
                        <svg>
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="product__info">
            <h1>{{ $product->name }}</h1>
            <p>{{ $product->short_description }}</p>
            <div class="product__price">
                <div class="product__count">
                    <button id="countDecrease">
                        <svg>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <input type="number" name="count" id="count" min="1" value="1">
                    <button id="countIncrease">
                        <svg>
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>
                <h3>50.00€</h3>
            </div>
            <div class="product__buttons">
                <button class="product__btn">Add to cart</button>
                <button class="product__btn">
                    <svg>
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="tab">
        <div class="tab__header">
            <button class="tab__header--btn">Description</button>
            <button class="tab__header--btn">Details</button>
        </div>
        <div class="tab__content">
            <div class="tab__pane active">
                <p>{{ $product->long_description }}</p>
            </div>
            <div class="tab__pane">
                <div class="table__wrapper">
                    <table class="table__info">
                        <thead>
                            <tr>
                                <th>Specification </th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($product->product_specs->first() !== null)
                                @foreach ($product->product_specs as $spec)
                                    <tr>
                                        <td>{{ $spec->spec->name }}</td>
                                        <td>{{ $spec->value }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No Specs for this product</td>
                                </tr>

                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
