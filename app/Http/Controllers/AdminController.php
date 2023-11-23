<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Status;
use App\Models\Account;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


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
  public function payments()
  {
    return view('admin.payment');
  }

  public function create_voucher()
  {
    return view('admin.add_voucher');
  }


  public function store_voucher(Request $request)
  {
    // Validation rules
    $rules = [
      'start_date' => 'required|date|after_or_equal:today',
      'end_date' => 'required|date|after_or_equal:start_date',
      // Add other validation rules as needed
    ];
    // Custom validation messages
    $messages = [
      'start_date.after_or_equal' => 'The start date must be in the future or present.',
      'end_date.after_or_equal' => 'The end date must be in the future and after the start date.',
      // Add other custom messages as needed
    ];
    $validator = $this->validate($request, $rules, $messages);
    $statusId = Status::where('name', 'Active')->where('type', 'voucher')->first()->id;
    $voucher = new Voucher();
    $voucher->name = $request->name;
    $voucher->code = $request->code;
    $voucher->percent = $request->percent;
    $voucher->status_id = $statusId;
    $voucher->start_date = $request->start_date;
    $voucher->end_date = $request->end_date;
    $voucher->single_use = $request->has('single_use');
    $voucher->save();

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
  public function show_order($id)
  {
    $data = Order::find($id);
    return view('admin.show_order', compact('data'));
  }
}
