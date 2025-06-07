<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
     public function index(){
        $users = user::latest()->get();
        return view('admin.Pengguna.Data', compact('users'));
    }
}
