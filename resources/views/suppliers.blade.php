@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <h1 class="mt-4">Suppliers</h1>
    <div class="row mb-3">
      <div class="col text-end">
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Create Supplier</a>
      </div>
    </div>
    <div class="row">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Contact Person</th>
            <th>Active</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suppliers as $supplier)
            <tr>
              <td>{{ $supplier->id }}</td>
              <td>{{ $supplier->name }}</td>
              <td>{{ $supplier->email ?? 'N/A' }}</td>
              <td>{{ $supplier->phone ?? 'N/A' }}</td>
              <td>{{ $supplier->address ?? 'N/A' }}</td>
              <td>{{ $supplier->contact_person ?? 'N/A' }}</td>
              <td>{{ $supplier->is_active ? 'Yes' : 'No' }}</td>
              <td>
                {{-- Edit Button --}}
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-warning">Edit</a>

                {{-- Delete Button --}}
                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
                  style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Are you sure you want to delete this supplier?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8">There are no suppliers.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
