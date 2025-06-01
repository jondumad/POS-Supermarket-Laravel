@extends('theme.default')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sales Transactions</h1>
    <div class="row mb-3">
        <div class="col text-end">
            <a href="{{ route('sales.create') }}" class="btn btn-primary">New Sale</a>
        </div>
    </div>
    <div class="row">
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Cashier</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->customer->name ?? '-' }}</td>
                        <td>{{ $sale->user->name ?? '-' }}</td>
                        <td>{{ number_format($sale->total_amount, 2) }}</td>
                        <td>{{ ucfirst($sale->payment_method) }}</td>
                        <td>{{ ucfirst($sale->status) }}</td>
                        <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this sale?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No sales found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection