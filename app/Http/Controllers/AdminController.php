<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class AdminController extends Controller
{
  //admin function

  public function storesettings()
  {
    return view('admin.store_settings');
  }

  public function vouchers()
  {
    return view('admin.voucher');
  }

  public function addstoresetting()
  {
    return view('admin.add_storesetting');
  }
}
