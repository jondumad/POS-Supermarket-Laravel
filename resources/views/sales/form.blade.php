@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    @isset($sale)
      <h1 class="mt-4">Edit Sale: #{{ $sale->invoice_number }}</h1>
    @else
      <h1 class="mt-4">Create Sale</h1>
    @endisset

    @isset($sale)
      <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @method('PUT')
      @else
        <form action="{{ route('sales.store') }}" method="POST">
        @endisset
        @csrf

        <div class="mb-3">
          <label for="invoice_number" class="form-label">Invoice Number <span class="text-muted">(Read Only)</span></label>
          <input type="text" class="form-control" id="invoice_number" name="invoice_number"
            value="{{ old('invoice_number', $sale->invoice_number ?? ($generatedInvoiceNumber ?? '')) }}" readonly>
        </div>

        <div class="mb-3">
          <label for="customer_id" class="form-label">Customer</label>
          <select class="form-select" id="customer_id" name="customer_id">
            <option value="">-- Walk-in / Guest --</option>
            @foreach ($customers as $customer)
              <option value="{{ $customer->id }}"
                {{ old('customer_id', $sale->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                {{ $customer->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label for="payment_method" class="form-label">Payment Method</label>
          <select class="form-select" id="payment_method" name="payment_method" required>
            @foreach (['cash', 'credit_card', 'debit_card', 'mobile_payment'] as $method)
              <option value="{{ $method }}"
                {{ old('payment_method', $sale->payment_method ?? 'cash') == $method ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $method)) }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Sale Items</label>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Discount (%)</th>
                <th>Discount Amount <span class="text-muted">(Read Only)</span></th>
                <th>Subtotal <span class="text-muted">(Read Only)</span></th>
              </tr>
            </thead>
            <tbody id="sale-items-body">
              @php
                $items = old('items', isset($sale) ? $sale->items : []);
              @endphp
              @foreach ($products as $product)
                <tr>
                  <td>
                    <input type="checkbox" name="items[{{ $product->id }}][selected]" value="1"
                      {{ isset($items[$product->id]) ? 'checked' : '' }}>
                    {{ $product->name }}
                    <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                  </td>
                  <td>
                    <input type="number" name="items[{{ $product->id }}][quantity]" min="0" class="form-control"
                      value="{{ $items[$product->id]['quantity'] ?? 0 }}">
                  </td>
                  <td>
                    <input type="number" name="items[{{ $product->id }}][unit_price]" min="0" step="0.01"
                      class="form-control" value="{{ $items[$product->id]['unit_price'] ?? $product->selling_price }}">
                  </td>
                  <td>
                    <input type="number" name="items[{{ $product->id }}][discount]" min="0" max="100"
                      step="0.01" class="form-control" value="{{ $items[$product->id]['discount'] ?? 0 }}">
                  </td>
                  <td>
                    <input type="number" class="form-control" readonly
                      name="items[{{ $product->id }}][discount_amount]" value="0.00">
                  </td>
                  <td>
                    <input type="number" class="form-control" readonly value="0.00">
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mb-3">
          <label for="subtotal" class="form-label">Subtotal <span class="text-muted">(Read Only)</span></label>
          <input type="number" class="form-control" id="subtotal" name="subtotal"
            value="{{ old('subtotal', $sale->subtotal ?? 0) }}" readonly>
        </div>

        <div class="mb-3">
          <label for="tax_amount" class="form-label">Tax Amount <span class="text-muted">(Read Only)</span></label>
          <input type="number" class="form-control" id="tax_amount" name="tax_amount"
            value="{{ old('tax_amount', $sale->tax_amount ?? 0) }}" readonly>
        </div>

        <div class="mb-3">
          <label for="discount_amount" class="form-label">Discount Amount <span class="text-muted">(Read
              Only)</span></label>
          <input type="number" class="form-control" id="discount_amount" name="discount_amount"
            value="{{ old('discount_amount', $sale->discount_amount ?? 0) }}" readonly>
        </div>

        <div class="mb-3">
          <label for="total_amount" class="form-label">Total Amount <span class="text-muted">(Read Only)</span></label>
          <input type="number" class="form-control" id="total_amount" name="total_amount"
            value="{{ old('total_amount', $sale->total_amount ?? 0) }}" readonly>
        </div>

        <div class="mb-3">
          <label for="notes" class="form-label">Notes</label>
          <textarea class="form-control" id="notes" name="notes">{{ old('notes', $sale->notes ?? '') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
          @isset($sale)
            Update
          @else
            Create
          @endisset
        </button>
      </form>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      function updateTotals() {
        let subtotal = 0;
        let discountAmount = 0;
        let taxRate = 0.1; // 10% tax

        document.querySelectorAll('#sale-items-body tr').forEach(function(row) {
          let checkbox = row.querySelector('input[type="checkbox"]');
          if (checkbox && checkbox.checked) {
            let qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
            let price = parseFloat(row.querySelector('input[name*="[unit_price]"]').value) || 0;
            let discountPercent = parseFloat(row.querySelector('input[name*="[discount]"]').value) || 0;
            let discountValue = ((qty * price) * discountPercent) / 100;
            let itemSubtotal = (qty * price) - discountValue;
            subtotal += itemSubtotal;
            discountAmount += discountValue;
          }
        });

        let taxAmount = subtotal * taxRate;
        let totalAmount = subtotal + taxAmount;

        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('tax_amount').value = taxAmount.toFixed(2);
        document.getElementById('discount_amount').value = discountAmount.toFixed(2);
        document.getElementById('total_amount').value = totalAmount.toFixed(2);
      }

      document.querySelectorAll('#sale-items-body tr').forEach(function(row) {
        let checkbox = row.querySelector('input[type="checkbox"]');
        let unitPriceInput = row.querySelector('input[name*="[unit_price]"]');
        let discountInput = row.querySelector('input[name*="[discount]"]');
        let qtyInput = row.querySelector('input[name*="[quantity]"]');
        let discountAmountInput = row.querySelector('input[name*="[discount_amount]"]');
        let subtotalInput = row.querySelectorAll('input[readonly]')[1];

        function updateSubtotal() {
          let qty = parseFloat(qtyInput.value) || 0;
          let price = parseFloat(unitPriceInput.value) || 0;
          let discountPercent = parseFloat(discountInput.value) || 0;
          let discountValue = ((qty * price) * discountPercent) / 100;
          let subtotal = (qty * price) - discountValue;
          discountAmountInput.value = discountValue.toFixed(2);
          subtotalInput.value = subtotal.toFixed(2);
          updateTotals();
        }

        [qtyInput, unitPriceInput, discountInput].forEach(function(input) {
          input.addEventListener('input', updateSubtotal);
        });

        if (checkbox) {
          checkbox.addEventListener('change', updateSubtotal);
        }

        // Initial calculation for each row
        updateSubtotal();
      });

      // Initial calculation on page load
      updateTotals();
    });
  </script>
@endsection
