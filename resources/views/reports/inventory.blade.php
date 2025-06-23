@extends('theme.default')

@section('content')
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="fw-bold">Inventory Report</h4>
      <a href="{{ route('reports.export.inventory') }}" class="btn btn-success btn-sm">Export CSV</a>
    </div>

    {{-- Summary cards --}}
    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6>Total Stock Value</h6>
            <h4 class="text-primary">${{ number_format($totalStockValue, 2) }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6>Low Stock Items</h6>
            <h4 class="text-warning">{{ $lowStockCount }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h6>Out of Stock Items</h6>
            <h4 class="text-danger">{{ $outOfStockCount }}</h4>
          </div>
        </div>
      </div>
    </div>

    {{-- Stock by Category --}}
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-white fw-bold">Stock Valuation by Category</div>
      <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
          <thead class="table-light">
            <tr>
              <th>Category</th>
              <th>Total Items</th>
              <th>Total Quantity</th>
              <th>Total Value</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($stockByCategory as $category)
              <tr>
                <td>{{ $category['category'] }}</td>
                <td>{{ $category['total_items'] }}</td>
                <td>{{ $category['total_quantity'] }}</td>
                <td>${{ number_format($category['total_value'], 2) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Inventory Details --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-bold">Inventory Details</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered table-sm mb-0">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Alert Threshold</th>
                <th>Location</th>
                <th>Stock Value</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($inventoryData as $item)
                <tr
                  class="{{ $item['status'] === 'Out of Stock' ? 'table-danger' : ($item['status'] === 'Low Stock' ? 'table-warning' : '') }}">
                  <td>{{ $item['product']->name }}</td>
                  <td>{{ $item['product']->category->name ?? 'Uncategorized' }}</td>
                  <td>{{ $item['quantity'] }}</td>
                  <td>{{ $item['alert_threshold'] }}</td>
                  <td>{{ $item['location'] }}</td>
                  <td>${{ number_format($item['stock_value'], 2) }}</td>
                  <td>{{ $item['status'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <style>
    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .border-left-info {
      border-left: 4px solid #36b9cc !important;
    }

    .border-left-warning {
      border-left: 4px solid #f6c23e !important;
    }

    .card {
      border-radius: 12px;
      border: none !important;
    }

    .card-header {
      border-radius: 12px 12px 0 0 !important;
      border-bottom: 1px solid #e3e6f0;
    }

    .table th {
      border-top: none;
      font-weight: 600;
      color: #5a5c69;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 1rem 0.75rem;
    }

    .table td {
      padding: 1rem 0.75rem;
      vertical-align: middle;
      border-bottom: 1px solid #e3e6f0;
    }

    .avatar-circle {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 0.9rem;
    }

    .rank-badge {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      color: white;
      font-size: 0.9rem;
    }

    .rank-1 {
      background: #ffd700;
    }

    .rank-2 {
      background: #c0c0c0;
    }

    .rank-3 {
      background: #cd7f32;
    }

    .rank-badge:not(.rank-1):not(.rank-2):not(.rank-3) {
      background: #6c757d;
    }

    .text-gray-800 {
      color: #5a5c69 !important;
    }

    .text-gray-300 {
      color: #dddfeb !important;
    }
  </style>
@endsection
