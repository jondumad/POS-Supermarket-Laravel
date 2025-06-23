@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
      <div>
        <h1 class="h3 text-gray-800">Product Inventory Performance</h1>
        <p class="text-muted mb-0">Sales, profit, and stock turnover by product</p>
      </div>
      <div class="btn-group" role="group">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i>Back to Reports
        </a>
        <button class="btn btn-primary" onclick="window.print()">
          <i class="fas fa-print me-2"></i>Print Report
        </button>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Sales & Inventory Table</h6>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Quantity Sold</th>
                    <th>Revenue</th>
                    <th>Profit</th>
                    <th>Current Stock</th>
                    <th>Stock Turnover</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($productPerformance as $row)
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          @if ($row['product']->image_path)
                            <img src="{{ asset('storage/' . $row['product']->image_path) }}"
                              alt="{{ $row['product']->name }}" class="rounded me-2" width="40" height="40"
                              style="object-fit:cover;">
                          @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-2"
                              style="width:40px;height:40px;">
                              <i class="fas fa-image text-muted"></i>
                            </div>
                          @endif
                          <div>
                            <div class="fw-bold">{{ $row['product']->name }}</div>
                          </div>
                        </div>
                      </td>
                      <td>{{ $row['product']->sku }}</td>
                      <td>{{ $row['product']->category->name ?? 'N/A' }}</td>
                      <td>{{ number_format($row['quantity_sold']) }}</td>
                      <td class="text-info">${{ number_format($row['revenue'], 2) }}</td>
                      <td class="text-success">${{ number_format($row['profit'], 2) }}</td>
                      <td>{{ number_format($row['current_stock']) }}</td>
                      <td>
                        <span
                          class="badge {{ $row['stock_turns'] >= 1 ? 'bg-success' : ($row['stock_turns'] > 0.3 ? 'bg-warning' : 'bg-danger') }}">
                          {{ number_format($row['stock_turns'], 2) }}
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-4">
                        <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                        <div class="text-muted">No product performance data found.</div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
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

    .border-left-danger {
      border-left: 4px solid #e74a3b !important;
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
      background-color: #f8f9fc !important;
    }

    .table td {
      padding: 1.25rem 0.75rem;
      vertical-align: middle;
      border-bottom: 1px solid #e3e6f0;
    }

    .table-hover tbody tr:hover {
      background-color: rgba(0, 0, 0, 0.02);
    }

    .text-gray-800 {
      color: #5a5c69 !important;
    }

    .text-gray-300 {
      color: #dddfeb !important;
    }

    .fw-bold {
      font-weight: bold !important;
    }

    .fs-5 {
      font-size: 1.25rem !important;
    }
  </style>
@endsection
