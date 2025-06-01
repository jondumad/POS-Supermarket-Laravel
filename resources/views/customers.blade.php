@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
      <h1 class="mt-4">Customers</h1>
    <div class="row mb-3">
      <div class="col text-end">
        <a href="{{ route('customers.create') }}" class="btn btn-primary">Create Customer</a>
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
            <th>Total Purchases</th>
            <th>Loyalty Points</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($customers as $customer)
            <tr>
              <td>{{ $customer->id }}</td>
              <td>{{ $customer->name }}</td>
              <td>{{ $customer->email ?? 'N/A' }}</td>
              <td>{{ $customer->phone ?? 'N/A' }}</td>
              <td>{{ $customer->address ?? 'N/A' }}</td>
              <td>{{ number_format($customer->total_purchases, 2) }}</td>
              <td>{{ $customer->loyalty_points }}</td>
              <td>
                {{-- Edit Button --}}
                <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">Edit</a>

                {{-- Delete Button --}}
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                  style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Are you sure you want to delete this customer?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8">There are no customers.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
