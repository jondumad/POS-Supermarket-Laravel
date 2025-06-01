@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <h1 class="mt-4">Sale Details</h1>
    <div class="card">
      <div class="card-body">
        <p><strong>Invoice #:</strong> {{ $sale->invoice_number }}</p>
        <p><strong>Customer:</strong> {{ $sale->customer->name ?? '-' }}</p>
        <p><strong>Cashier:</strong> {{ $sale->user->name ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($sale->status) }}</p>
        <p><strong>Date:</strong> {{ $sale->created_at->format('Y-m-d H:i') }}</p>
        <p><strong>Total:</strong> {{ number_format($sale->total_amount, 2) }}</p>
        <hr>
        <h5>Items</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Product</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Discount</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sale->items as $item)
              <tr>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->discount, 2) }}</td>
                <td>{{ number_format($item->subtotal, 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back to Sales</a>
        <form action="{{ route('sales.void', $sale->id) }}" method="POST" style="display:inline-block;">
          @csrf
          <button type="submit" class="btn btn-sm btn-danger"
            onclick="return confirm('Are you sure you want to cancel (void) this sale?')">
            Void
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
