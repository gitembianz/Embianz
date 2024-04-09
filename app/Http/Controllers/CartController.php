<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
  public function show($id)
  {
    $data = Cart::find($id);
    return view('admin.show_cart', compact('data'));
  }
}
