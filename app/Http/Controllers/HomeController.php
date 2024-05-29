<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
  public function redirect()
  {
    $usertype = Auth::user()->usertype;
    if ($usertype == '1') {
      return view('admin.home');
    } else {
      return route('home');
    }
  }

  public function home()
  {
    $preload = null;
    $firstcategory = Category::where('slider_sequence', '!=', 0)->orderby('slider_sequence')->first();
    if ($firstcategory != null) {
      $media = $firstcategory->media()->where('sequence', 4)->first();
      if ($media) {
        $preload = "/" . $media->path . $media->name;
      } else {
        $preload = "/images/store/default/default300.webp";
      }
    } else {
      $preload = "";
    }
    return view('store.home', compact('preload'));
  }
}