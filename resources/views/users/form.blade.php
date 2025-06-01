@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    @isset($user)
      <h1 class="mt-4">Edit User: {{ $user->name }}</h1>
    @else
      <h1 class="mt-4">Create User</h1>
    @endisset

    @isset($user)
      <form action="{{ route('users.update', $user->id) }}" method="POST">
        @method('PUT')
      @else
        <form action="{{ route('users.store') }}" method="POST">
        @endisset
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $user->name ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email"
            value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password"
            @empty($user) required @endempty>
          @isset($user)
            <small class="form-text text-muted">Leave blank to keep the current password.</small>
          @endisset
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirm Password</label>
          <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
            @empty($user) required @endempty>
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select" id="role" name="role" required>
            <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="cashier" {{ old('role', $user->role ?? '') == 'cashier' ? 'selected' : '' }}>Cashier</option>
            <option value="manager" {{ old('role', $user->role ?? '') == 'manager' ? 'selected' : '' }}>Manager</option>
          </select>
        </div>

        <div class="form-check mb-3">
          <input type="hidden" name="is_active" value="0">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $user->is_active ?? '0') == '1' ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">
          @isset($user)
            Update
          @else
            Create
          @endisset
        </button>
      </form>
  </div>
@endsection
