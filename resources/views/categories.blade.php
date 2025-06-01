@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <h1 class="mt-4">Categories</h1>
    <div class="row mb-3">
      <div class="col text-end">
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Create Category</a>
      </div>
    </div>
    <div class="row">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
            <tr>
              <td>{{ $category->id }}</td>
              <td>{{ $category->name }}</td>
              <td>{{ $category->description ?? 'N/A' }}</td>
              <td>
                {{-- Edit Button --}}
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>

                {{-- Delete Button --}}
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                  style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4">There are no categories.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
