<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function dashboard()
  {
    return view('dashboard');
  }

  public function users(Request $request): \Illuminate\Contracts\View\View
  {
    $users = User::all();
    return view('users', compact('users'));
  }

  public function index()
  {
    $users = User::all();
    return view('users', compact('users'));
  }

  public function create()
  {
    return view('users.form');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'role' => 'required|in:admin,cashier,manager',
      'is_active' => 'boolean', // Assuming this field exists
    ]);

    $validated['password'] = Hash::make($validated['password']);
    User::create($validated);

    return redirect()->route('users')->with('success', 'User created successfully.');
  }

  public function show(User $user)
  {
    return view('users.show', compact('user'));
  }

  public function edit(User $user)
  {
    return view('users.form', compact('user')); // Renders users/edit.blade.php
  }

  public function update(Request $request, User $user)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
      'role' => 'required|in:admin,cashier,manager',
      'is_active' => 'required|boolean',
    ]);

    if ($request->filled('password')) {
      $request->validate([
        'password' => 'required|string|min:8|confirmed',
      ]);
      $validated['password'] = Hash::make($request->password);
    }

    $user->update($validated);

    return redirect()->route('users')->with('success', 'User updated successfully.');
  }

  public function destroy(User $user)
  {
    $user->delete();
    return redirect()->route('users')->with('success', 'User deleted successfully.');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
      $request->session()->regenerate();
      return redirect()->intended('reports');
    }

    return back()->withErrors([
      'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login')->with('success', 'Logged out successfully.');
  }  
}
