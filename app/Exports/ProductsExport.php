<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ProductsExport  implements
FromQuery
{

    use Exportable;

    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function query()
    {
        return Product::query()->whereKey($this->products);
    }
}
