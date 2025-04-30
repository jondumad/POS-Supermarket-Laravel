@extends('theme.default')

@section('content')

  <div class="container-fluid px-4">
    <h1 class="mt-4">Users</h1>
    <div class="row mb-3">
      <div class="col text-end">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
      </div>
    </div>
    <div class="row">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ ucfirst($user->role) }}</td>
              <td>{{ $user->is_active ? 'Yes' : 'No' }}</td>
              <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5">There are no users.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
