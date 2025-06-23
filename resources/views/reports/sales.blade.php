@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
      <div>
        <h1 class="h3 text-gray-800">Sales Report</h1>
        <p class="text-muted mb-0">Analyze sales performance and revenue trends</p>
      </div>
      <div class="btn-group" role="group">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left me-2"></i>Back to Reports
        </a>
        <a href="{{ route('reports.export.sales', request()->query()) }}" class="btn btn-primary">
          <i class="fas fa-download me-2"></i>Export Data
        </a>
      </div>
    </div>

    <!-- Date Filter -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <form method="GET" action="{{ route('reports.sales') }}" class="row g-3 align-items-end">
              <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                  value="{{ request('start_date', $startDate) }}">
              </div>
              <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                  value="{{ request('end_date', $endDate) }}">
              </div>
              <div class="col-md-3">
                <label for="group_by" class="form-label">Group By</label>
                <select class="form-select" id="group_by" name="group_by">
                  <option value="day" {{ $groupBy === 'day' ? 'selected' : '' }}>Daily</option>
                  <option value="week" {{ $groupBy === 'week' ? 'selected' : '' }}>Weekly</option>
                  <option value="month" {{ $groupBy === 'month' ? 'selected' : '' }}>Monthly</option>
                </select>
              </div>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="fas fa-filter me-2"></i>Apply Filter
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Sales</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($totalSales, 2) }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Transactions</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalTransactions) }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-receipt fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Average Transaction</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($averageTransaction, 2) }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Period</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                  {{ \Carbon\Carbon::parse($startDate)->format('M d') }} -
                  {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Sales Chart -->
      <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sales Trend</h6>
          </div>
          <div class="card-body">
            <canvas id="salesChart" height="300"></canvas>
          </div>
        </div>
      </div>

      <!-- Payment Methods -->
      <div class="col-xl-4 col-lg-5">
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Payment Methods</h6>
          </div>
          <div class="card-body">
            <canvas id="paymentChart" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Products -->
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th width="10%">Rank</th>
                    <th width="40%">Product</th>
                    <th width="20%">Quantity Sold</th>
                    <th width="20%">Revenue</th>
                    <th width="10%">% of Total</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($topProducts as $index => $item)
                    <tr>
                      <td>
                        <div class="rank-badge rank-{{ $index + 1 }}">
                          {{ $index + 1 }}
                        </div>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="product-avatar me-3">
                            <div class="avatar-circle bg-primary text-white">
                              {{ strtoupper(substr($item->product->name, 0, 1)) }}
                            </div>
                          </div>
                          <div>
                            <div class="fw-bold text-gray-800">{{ $item->product->name }}</div>
                            <div class="small text-muted">SKU: {{ $item->product->sku }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="fw-bold text-gray-800">{{ number_format($item->total_quantity) }}</span>
                      </td>
                      <td>
                        <span class="fw-bold text-success">${{ number_format($item->total_revenue, 2) }}</span>
                      </td>
                      <td>
                        <span class="badge bg-primary">
                          {{ number_format(($item->total_revenue / $totalSales) * 100, 1) }}%
                        </span>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">No products found for this period</td>
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

  <!-- Styles -->
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

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
  <script>
    // Sales Trend Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: [
          @foreach ($salesData as $data)
            '{{ $data->period }}',
          @endforeach
        ],
        datasets: [{
          label: 'Sales ($)',
          data: [
            @foreach ($salesData as $data)
              {{ $data->total_sales }},
            @endforeach
          ],
          borderColor: '#4e73df',
          backgroundColor: 'rgba(78, 115, 223, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4
        }, {
          label: 'Transactions',
          data: [
            @foreach ($salesData as $data)
              {{ $data->transaction_count }},
            @endforeach
          ],
          borderColor: '#1cc88a',
          backgroundColor: 'rgba(28, 200, 138, 0.1)',
          borderWidth: 2,
          fill: true,
          tension: 0.4,
          yAxisID: 'y1'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            type: 'linear',
            display: true,
            position: 'left',
            title: {
              display: true,
              text: 'Sales ($)'
            }
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            title: {
              display: true,
              text: 'Transactions'
            },
            grid: {
              drawOnChartArea: false
            }
          }
        },
        plugins: {
          legend: {
            display: true,
            position: 'top'
          }
        }
      }
    });

    // Payment Methods Chart
    const paymentCtx = document.getElementById('paymentChart').getContext('2d');
    const paymentChart = new Chart(paymentCtx, {
      type: 'doughnut',
      data: {
        labels: [
          @foreach ($paymentMethods as $method)
            '{{ ucfirst($method->payment_method) }}',
          @endforeach
        ],
        datasets: [{
          data: [
            @foreach ($paymentMethods as $method)
              {{ $method->total }},
            @endforeach
          ],
          backgroundColor: [
            '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1'
          ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  </script>
@endsection
