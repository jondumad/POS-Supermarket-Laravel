@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <h1 class="mt-4">Purchase Orders</h1>
    <div class="row mb-3">
      <div class="col text-end">
        <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary">Create Purchase Order</a>
      </div>
    </div>
    <div class="row">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Reference #</th>
            <th>Supplier</th>
            <th>Created By</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Delivery Date</th>
            <th>Notes</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($purchaseOrders as $order)
            <tr>
              <td>{{ $order->id }}</td>
              <td>{{ $order->reference_number }}</td>
              <td>{{ $order->supplier->name ?? '-' }}</td>
              <td>{{ $order->user->name ?? '-' }}</td>
              <td>{{ number_format($order->total_amount, 2) }}</td>
              <td>{{ ucfirst($order->status) }}</td>
              <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : '-' }}</td>
              <td>{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '-' }}</td>
              <td>{{ $order->notes ?? '-' }}</td>
              <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
              <td>
                <a href="{{ route('purchase_orders.edit', $order->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('purchase_orders.destroy', $order->id) }}" method="POST"
                  style="display:inline-block;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Are you sure you want to delete this purchase order?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="11">There are no purchase orders.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
