<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
  /**
   * Write code on Method
   *
   * @return \Illuminate\Contracts\View\View
   */
  public function dashboard()
  {
    return view('dashboard');
  }

  /**
   * Write code on Method
   *
   * @return \Illuminate\Contracts\View\View
   */
  public function users(Request $request): \Illuminate\Contracts\View\View
  {
    $users = User::get();
    return view('users', compact('users'));
  }
}
