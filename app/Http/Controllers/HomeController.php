<?php

namespace App\Http\Controllers;

use App\Models\Todolist;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function redirect(){
        $usertype=Auth::user()->usertype;
        $todolists = Todolist::all();
        if($usertype=='1'){
            return view('admin.home', compact('todolists'));
        } else{
            return view('site.home');
        }

    }
}
