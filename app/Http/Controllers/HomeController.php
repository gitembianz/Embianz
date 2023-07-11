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
      return view('store.home');
    }
  }
}
