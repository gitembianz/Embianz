<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Livewire\StoreHeader;   // ✅ FIXED namespace

class StoreDataController extends Controller
{
    public function header(Request $request)
    {
        $header = app(StoreHeader::class);  // now points to App\Http\Livewire\StoreHeader

        $categories = $header->categories->map(function ($cat) {
            return [
                'id'          => $cat->id,
                'name'        => $cat->name,
                'seo_id'      => $cat->seo_id,
                'has_children'=> $cat->subcategory->count() > 0,
                'children'    => $cat->subcategory->map(function ($sub) {
                    $c = $sub->category;
                    return [
                        'id'          => $c->id,
                        'name'        => $c->name,
                        'seo_id'      => $c->seo_id,
                        'has_children'=> $c->subcategory->count() > 0,
                        'children'    => $c->subcategory->map(function ($subsub) {
                            $c2 = $subsub->category;
                            return [
                                'id'     => $c2->id,
                                'name'   => $c2->name,
                                'seo_id' => $c2->seo_id,
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'categories'    => $categories,
            'wishlistCount' => 0,
            'cartCount'     => 0,
        ]);
    }
}
