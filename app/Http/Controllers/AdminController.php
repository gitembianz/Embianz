<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
  //admin function

  public function storesettings()
  {
    return view('admin.store_settings');
  }
  public function accounts()
  {
    return view('admin.accounts');
  }
  public function show_account($id)
  {
    $data = Account::find($id);
    return view('admin.show_account', compact('data'));
  }
  public function vouchers()
  {
    return view('admin.voucher');
  }

  public function create_voucher()
  {
    return view('admin.add_voucher');
  }

  public function store_voucher(Request $request)
  {
    $item = new Voucher();

    $item->name = $request->name;
    $item->code = $request->code;
    $item->percent = $request->percent;
    $item->save();
    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully!',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }

  public function addstoresetting()
  {
    return view('admin.add_storesetting');
  }

  public function orders()
  {
    return view('admin.order');
  }
}
