@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    @isset($customer)
      <h1 class="mt-4">Edit Customer: {{ $customer->name }}</h1>
    @else
      <h1 class="mt-4">Create Customer</h1>
    @endisset

    @if (isset($customer))
      <form action="{{ route('customers.update', $customer->id) }}" method="POST">
        @method('PUT')
    @else
      <form action="{{ route('customers.store') }}" method="POST">
    @endif

    @csrf

    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" name="name"
        value="{{ old('name', $customer->name ?? '') }}" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" id="email" name="email"
        value="{{ old('email', $customer->email ?? '') }}">
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input type="text" class="form-control" id="phone" name="phone"
        value="{{ old('phone', $customer->phone ?? '') }}">
    </div>

    <div class="mb-3">
      <label for="address" class="form-label">Address</label>
      <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $customer->address ?? '') }}</textarea>
    </div>

    <div class="mb-3">
      <label for="total_purchases" class="form-label">Total Purchases</label>
      <input type="number" class="form-control" id="total_purchases" name="total_purchases" step="0.01" min="0"
        value="{{ old('total_purchases', $customer->total_purchases ?? 0) }}" readonly>
    </div>

    <div class="mb-3">
      <label for="loyalty_points" class="form-label">Loyalty Points</label>
      <input type="number" class="form-control" id="loyalty_points" name="loyalty_points" min="0"
        value="{{ old('loyalty_points', $customer->loyalty_points ?? 0) }}">
    </div>

    <button type="submit" class="btn btn-primary">
      @isset($customer)
        Update
      @else
        Create
      @endisset
    </button>
    </form>
    <script>
      document.addEventListener('click', (e) => {
        e.stopPropagation();
        console.log('Click blocked:', e.target);
      }, true);
    </script>
  </div>
@endsection
