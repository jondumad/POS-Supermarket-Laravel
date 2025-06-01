@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    @isset($product)
      <h1 class="mt-4">Edit Product: {{ $product->name }}</h1>
    @else
      <h1 class="mt-4">Create Product</h1>
    @endisset

    @isset($product)
      <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
      @else
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @endisset
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name"
            value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="sku" class="form-label">SKU</label>
          <input type="text" class="form-control" id="sku" name="sku"
            value="{{ old('sku', $product->sku ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
          <label for="purchase_price" class="form-label">Purchase Price</label>
          <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price"
            value="{{ old('purchase_price', $product->purchase_price ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="selling_price" class="form-label">Selling Price</label>
          <input type="number" step="0.01" class="form-control" id="selling_price" name="selling_price"
            value="{{ old('selling_price', $product->selling_price ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="category_id" class="form-label">Category</label>
          <select class="form-select" id="category_id" name="category_id">
            <option value="">-- Select Category --</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}"
                {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="image" class="form-label">Product Image</label>
          <input type="file" class="form-control" id="image" name="image">
          @isset($product->image_path)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $product->image_path) }}" alt="Product Image" width="100">
            </div>
          @endisset
        </div>

        <div class="mb-3">
          <label for="quantity" class="form-label">Quantity</label>
          <input type="number" class="form-control" id="quantity" name="quantity"
            value="{{ old('quantity', $product->quantity ?? '') }}" required min="0">
        </div>

        <div class="form-check mb-3">
          <input type="hidden" name="is_active" value="0">
          <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
            {{ old('is_active', $product->is_active ?? '1') == '1' ? 'checked' : '' }}>
          <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button type="submit" class="btn btn-primary">
          @isset($product)
            Update
          @else
            Create
          @endisset
        </button>
      </form>
  </div>
@endsection
