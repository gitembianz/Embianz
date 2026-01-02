@php
    // Validate product
    if (!$product || !$product->name) {
        return;
    }

    // Get price
    $price = ($product->product_prices && $product->product_prices->count() > 0) 
        ? (string)$product->product_prices->first()->value 
        : '0';
    
    if ($price === '0') {
        return;
    }
    
    // Get currency
    $currency = 'MDL';
    
    // Get media
    $media = ($product->media && $product->media->count() > 0)
        ? url($product->media->first()->path . $product->media->first()->name)
        : url('/images/store/default/default300.webp');
    
    // Clean description
    $description = $product->long_description 
        ? strip_tags($product->long_description) 
        : '';
    
    // Build base JSON-LD
    $jsonLd = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => $media,
        'description' => $description,
        'sku' => $product->sku ?? '',
        'brand' => [
            '@type' => 'Brand',
            'name' => $product->brand ?? 'Unknown'
        ]
    ];
    
    // IMPORTANT: Add aggregateRating BEFORE offers
    if ($product->reviews && $product->reviews->count() > 0) {
        // Get approved reviews with valid ratings
        $approvedReviews = $product->reviews
            ->filter(function($review) {
                return isset($review->approved) && $review->approved === 1;
            })
            ->filter(function($review) {
                return isset($review->value) && (float)$review->value > 0;
            });
        
        if ($approvedReviews->count() > 0) {
            // Calculate average from ALL approved reviews
            $avgRating = $approvedReviews->avg('value');
            $reviewCount = $approvedReviews->count();
            
            // Add aggregateRating at ROOT LEVEL
            $jsonLd['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => round($avgRating, 2),
                'reviewCount' => (int)$reviewCount
            ];
            
            // Add review array at ROOT LEVEL
            $jsonLd['review'] = $approvedReviews
                ->sortByDesc('created_at')
                ->take(10)
                ->map(function($review) {
                    return [
                        '@type' => 'Review',
                        'reviewRating' => [
                            '@type' => 'Rating',
                            'ratingValue' => (int)round($review->value),
                            'bestRating' => 5,
                            'worstRating' => 1
                        ],
                        'author' => [
                            '@type' => 'Person',
                            'name' => $review->acronim ?? 'Anonymous'
                        ],
                        'reviewBody' => $review->comment ?? '',
                        'datePublished' => $review->created_at 
                            ? $review->created_at->format('c')
                            : ''
                    ];
                })
                ->values()
                ->toArray();
        }
    }
    
    // Add offers AFTER aggregateRating
    $jsonLd['offers'] = [
        '@type' => 'Offer',
        'url' => url('/product/' . ($product->seo_id ?? $product->id)),
        'priceCurrency' => $currency,
        'price' => $price,
        'availability' => ($product->quantity && $product->quantity > 0) 
            ? 'https://schema.org/InStock' 
            : 'https://schema.org/OutOfStock',
        'shippingDetails' => [
            '@type' => 'OfferShippingDetails',
            'shippingRate' => [
                '@type' => 'PriceSpecification',
                'priceCurrency' => $currency,
                'price' => '0'
            ]
        ],
        'hasMerchantReturnPolicy' => true
    ];
    
    // Only add priceValidUntil if not default date
    if ($product->end_date && $product->end_date !== '2099-01-01') {
        $jsonLd['offers']['priceValidUntil'] = is_string($product->end_date) 
            ? $product->end_date 
            : $product->end_date->format('Y-m-d');
    }
@endphp

@if ($product->name && $price > 0)
    <!-- Structured Data for {{ $product->name }} -->
    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endif
