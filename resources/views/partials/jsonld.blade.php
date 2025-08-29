@php
  $price = $product->product_prices[0]->value ?? 0;
  $ratingValue = $product->reviews[0]->value ?? 0;
  $reviewCount = $product->reviews[0]->count ?? 0;
  $currency = 'MDL'; // Or dynamic
  $media = isset($product->media[0])
      ? url($product->media[0]->path . $product->media[0]->name)
      : url('/images/store/default/default300.webp');
  $description = strip_tags($product->long_description ?? '');

  $jsonLd = [
    "@context" => "https://schema.org/",
    "@type" => "Product",
    "name" => $product->name,
    "image" => $media,
    "description" => $description,
    "brand" => [
      "@type" => "Brand",
      "name" => $product->brand
    ],
    "sku" => $product->sku,
    "offers" => [
      "@type" => "Offer",
      "url" => url('/product/' . ($product->seo_id ?? $product->id)),
      "priceCurrency" => $currency,
      "price" => $price,
      "availability" => "https://schema.org/InStock",
      "priceValidUntil" => $product->end_date,
      "hasMerchantReturnPolicy" => ["value" => true],
      "shippingDetails" => [
        "type" => "FreeShipping",
        "price" => "0"
      ],
      "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => $ratingValue,
        "reviewCount" => $reviewCount
      ],
      "review" => [
        "@type" => "Review",
        "reviewRating" => [
          "@type" => "Rating",
          "ratingValue" => $ratingValue,
          "bestRating" => 5
        ],
        "author" => [
          "@type" => "Person",
          "name" => "anonim"
        ]
      ]
    ]
  ];
@endphp

@if ($product->name && $price > 0)
  <!-- Structured Data for {{ $product->name }} -->
  <script type="application/ld+json">
    {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
  </script>
@endif
