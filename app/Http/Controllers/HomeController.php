<?php

namespace App\Http\Controllers;


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
    // $firstcategory = app()->make('cached_categories')->filter(function ($category) {
    //   return $category->slider_sequence != 0;
    // })->sortBy('slider_sequence')->first();

    // if ($firstcategory != null) {
    //   $media = $firstcategory->media->first(function ($mediaItem) {
    //     return $mediaItem->sequence == 4;
    //   });
    //   if ($media) {
    //     $preload = "/" . $media->path . $media->name;
    //   } else {
    //     $preload = "/images/store/default/default300.webp";
    //   }
    // } else {
    //   $preload = "";
    // }
    return view('store.home', compact('preload'));
  }
}