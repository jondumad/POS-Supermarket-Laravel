@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    @isset($order)
      <h1 class="mt-4">Edit Purchase Order: {{ $order->reference_number }}</h1>
    @else
      <h1 class="mt-4">Create Purchase Order</h1>
    @endisset

    @isset($order)
      <form action="{{ route('purchase_orders.update', $order->id) }}" method="POST">
        @method('PUT')
      @else
        <form action="{{ route('purchase_orders.store') }}" method="POST">
        @endisset
        @csrf

        <div class="mb-3">
          <label for="reference_number" class="form-label">Reference Number</label>
          <input type="text" class="form-control" id="reference_number" name="reference_number"
            value="{{ old('reference_number', isset($order) ? $order->reference_number : '') }}" required>
        </div>

        <div class="mb-3">
          <label for="supplier_id" class="form-label">Supplier</label>
          <select class="form-select" id="supplier_id" name="supplier_id" required>
            @foreach ($suppliers as $supplier)
              <option value="{{ $supplier->id }}"
                {{ old('supplier_id', $order->supplier_id ?? '') == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="order_date" class="form-label">Order Date</label>
          <input type="date" class="form-control" id="order_date" name="order_date"
            value="{{ old('order_date', $order->order_date ?? '') }}" required>
        </div>

        <div class="mb-3">
          <label for="delivery_date" class="form-label">Delivery Date</label>
          <input type="date" class="form-control" id="delivery_date" name="delivery_date"
            value="{{ old('delivery_date', $order->delivery_date ?? '') }}">
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Notes</label>
          <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $order->notes ?? '') }}</textarea>
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" id="status" name="status" required>
            <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>Pending
            </option>
            <option value="received" {{ old('status', $order->status ?? '') == 'received' ? 'selected' : '' }}>Received
            </option>
            <option value="cancelled" {{ old('status', $order->status ?? '') == 'cancelled' ? 'selected' : '' }}>
              Cancelled</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="items" class="form-label">Order Items</label>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $product)
                <tr>
                  <td>
                    {{ $product->name }}
                    <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                  </td>
                  <td>
                    <input type="number" class="form-control quantity-input" name="items[{{ $product->id }}][quantity]"
                      min="0"
                      value="{{ old('items.' . $product->id . '.quantity', isset($order) ? $order->items->where('product_id', $product->id)->first()->quantity ?? 0 : 0) }}"
                      data-product-id="{{ $product->id }}" data-unit-price="{{ $product->purchase_price }}">
                  </td>
                  <td>
                    {{ number_format($product->purchase_price, 2) }}
                    <input type="hidden" name="items[{{ $product->id }}][unit_price]"
                      value="{{ $product->purchase_price }}">
                  </td>
                  <td id="subtotal-{{ $product->id }}">
                    {{ isset($order) ? number_format($order->items->where('product_id', $product->id)->first()->subtotal ?? 0, 2) : '0.00' }}
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td id="total-amount">0.00</td>
              </tr>
            </tfoot>
          </table>
        </div>

        <button type="submit" class="btn btn-primary">
          @isset($order)
            Update
          @else
            Create
          @endisset
        </button>
      </form>
  </div>

  <script>
    function updateTotalAmount() {
      let total = 0;
      document.querySelectorAll('.quantity-input').forEach(function(input) {
        const unitPrice = parseFloat(input.dataset.unitPrice);
        const quantity = parseFloat(input.value) || 0;
        total += quantity * unitPrice;
      });
      document.getElementById('total-amount').textContent = total.toFixed(2);
    }

    document.querySelectorAll('.quantity-input').forEach(function(input) {
      input.addEventListener('input', function() {
        const productId = this.dataset.productId;
        const unitPrice = parseFloat(this.dataset.unitPrice);
        const quantity = parseFloat(this.value) || 0;
        const subtotal = (quantity * unitPrice).toFixed(2);

        // Update the subtotal cell
        document.getElementById(`subtotal-${productId}`).textContent = subtotal;

        // Update the total amount
        updateTotalAmount();
      });
    });
  </script>
@endsection
