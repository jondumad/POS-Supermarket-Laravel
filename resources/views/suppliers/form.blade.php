@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    @isset($supplier)
      <h1 class="mt-4">Edit Supplier: {{ $supplier->name }}</h1>
    @else
      <h1 class="mt-4">Create Supplier</h1>
    @endisset

    @isset($supplier)
      <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @method('PUT')
      @else
        <form action="{{ route('suppliers.store') }}" method="POST">
        @endisset
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $supplier->name ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email"
            value="{{ old('email', $supplier->email ?? '') }}">
        </div>

        <div class="mb-3">
          <label for="phone" class="form-label">Phone</label>
          <input type="text" class="form-control" id="phone" name="phone"
            value="{{ old('phone', $supplier->phone ?? '') }}">
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">Address</label>
          <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $supplier->address ?? '') }}</textarea>
        </div>

        <div class="mb-3">
          <label for="contact_person" class="form-label">Contact Person</label>
          <input type="text" class="form-control" id="contact_person" name="contact_person"
            value="{{ old('contact_person', $supplier->contact_person ?? '') }}">
        </div>

        <div class="form-check mb-3">
          <input type="hidden" name="is_active" value="0">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $supplier->is_active ?? '1') == '1' ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">
          @isset($supplier)
            Update
          @else
            Create
          @endisset
        </button>
      </form>
  </div>
@endsection
