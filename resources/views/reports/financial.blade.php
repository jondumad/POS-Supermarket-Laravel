@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
      <div>
        <h1 class="h3 text-gray-800">Financial Report</h1>
        <p class="text-muted mb-0">Revenue, costs, and profit analysis</p>
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

    <!-- Date Filter -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <form method="GET" action="{{ route('reports.financial') }}" class="row g-3 align-items-end">
              <div class="col-md-4">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                  value="{{ request('start_date', $startDate) }}">
              </div>
              <div class="col-md-4">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                  value="{{ request('end_date', $endDate) }}">
              </div>
              <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                  <i class="fas fa-filter me-2"></i>Apply Filter
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Revenue</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($salesData->total_revenue, 2) }}
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
        <div class="card border-left-danger shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cost of Goods</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($cogs, 2) }}
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-box fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Gross Profit</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($grossProfit, 2) }}
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
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Profit Margin</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  {{ number_format($profitMargin, 1) }}%
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-percentage fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <!-- Revenue Breakdown -->
      <div class="col-xl-6 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Revenue Breakdown</h6>
          </div>
          <div class="card-body">
            <div class="financial-item">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <div class="financial-icon bg-success text-white me-3">
                    <i class="fas fa-plus"></i>
                  </div>
                  <span class="fw-bold">Gross Revenue</span>
                </div>
                <span class="fw-bold text-success">
                  ${{ number_format($salesData->total_revenue, 2) }}
                </span>
              </div>
            </div>

            <div class="financial-item">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <div class="financial-icon bg-warning text-white me-3">
                    <i class="fas fa-minus"></i>
                  </div>
                  <span class="fw-bold">Total Discounts</span>
                </div>
                <span class="fw-bold text-warning">
                  -${{ number_format($salesData->total_discounts, 2) }}
                </span>
              </div>
            </div>

            <div class="financial-item">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                  <div class="financial-icon bg-info text-white me-3">
                    <i class="fas fa-receipt"></i>
                  </div>
                  <span class="fw-bold">Tax Collected</span>
                </div>
                <span class="fw-bold text-info">
                  ${{ number_format($salesData->total_tax, 2) }}
                </span>
              </div>
            </div>

            <hr>

            <div class="financial-item">
              <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="financial-icon bg-primary text-white me-3">
                    <i class="fas fa-equals"></i>
                  </div>
                  <span class="fw-bold">Net Revenue</span>
                </div>
                <span class="fw-bold text-primary fs-5">
                  ${{ number_format($salesData->total_revenue - $salesData->total_discounts, 2) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Profit Analysis -->
      <div class="col-xl-6 col-lg-6">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Profit Analysis</h6>
          </div>
          <div class="card-body">
            <canvas id="profitChart" height="250"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Daily Revenue Chart -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daily Revenue Trend</h6>
          </div>
          <div class="card-body">
            <canvas id="revenueChart" height="300"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Financial Metrics Table -->
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Financial Metrics</h6>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th width="30%">Metric</th>
                    <th width="25%">Value</th>
                    <th width="25%">Percentage</th>
                    <th width="20%">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        <span class="fw-bold">Gross Profit Margin</span>
                      </div>
                    </td>
                    <td>
                      <span class="fw-bold text-success">
                        ${{ number_format($grossProfit, 2) }}
                      </span>
                    </td>
                    <td>
                      <span class="fw-bold">{{ number_format($profitMargin, 1) }}%</span>
                    </td>
                    <td>
                      <span
                        class="badge {{ $profitMargin >= 30 ? 'bg-success' : ($profitMargin >= 15 ? 'bg-warning' : 'bg-danger') }}">
                        {{ $profitMargin >= 30 ? 'Excellent' : ($profitMargin >= 15 ? 'Good' : 'Needs Improvement') }}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <i class="fas fa-percentage text-info me-2"></i>
                        <span class="fw-bold">Discount Rate</span>
                      </div>
                    </td>
                    <td>
                      <span class="fw-bold text-warning">
                        ${{ number_format($salesData->total_discounts, 2) }}
                      </span>
                    </td>
                    <td>
                      <span class="fw-bold">
                        {{ $salesData->total_revenue > 0 ? number_format(($salesData->total_discounts / $salesData->total_revenue) * 100, 1) : 0 }}%
                      </span>
                    </td>
                    <td>
                      @php
                        $discountRate =
                            $salesData->total_revenue > 0
                                ? ($salesData->total_discounts / $salesData->total_revenue) * 100
                                : 0;
                      @endphp
                      <span
                        class="badge {{ $discountRate <= 5 ? 'bg-success' : ($discountRate <= 10 ? 'bg-warning' : 'bg-danger') }}">
                        {{ $discountRate <= 5 ? 'Low' : ($discountRate <= 10 ? 'Moderate' : 'High') }}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <i class="fas fa-calculator text-primary me-2"></i>
                        <span class="fw-bold">Average Transaction</span>
                      </div>
                    </td>
                    <td>
                      <span class="fw-bold text-primary">
                        ${{ number_format($salesData->total_transactions > 0 ? $salesData->total_revenue / $salesData->total_transactions : 0, 2) }}
                      </span>
                    </td>
                    <td>
                      <span class="text-muted">Per Transaction</span>
                    </td>
                    <td>
                      <span class="badge bg-info">{{ number_format($salesData->total_transactions) }}
                        Transactions</span>
                    </td>
                  </tr>
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

    .border-left-danger {
      border-left: 4px solid #e74a3b !important;
    }

    .financial-icon {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Prepare data for charts
    const profitData = {
      labels: ['Gross Profit', 'COGS'],
      datasets: [{
        data: [parseFloat('{{ $grossProfit }}'), parseFloat('{{ $cogs }}')],
        backgroundColor: ['#4e73df', '#e74a3b'],
        borderWidth: 1
      }]
    };

    // Prepare revenue data
    const revenueDates = [];
    const revenueValues = [];
    @foreach ($dailyRevenue as $day)
      revenueDates.push('{{ $day->date }}');
      revenueValues.push(parseFloat('{{ $day->revenue }}'));
    @endforeach

    const revenueData = {
      labels: revenueDates,
      datasets: [{
        label: 'Revenue',
        data: revenueValues,
        borderColor: '#1cc88a',
        backgroundColor: 'rgba(28,200,138,0.1)',
        fill: true,
        tension: 0.4
      }]
    };

    // Initialize charts
    const profitCtx = document.getElementById('profitChart').getContext('2d');
    new Chart(profitCtx, {
      type: 'doughnut',
      data: profitData,
      options: {
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });

    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: revenueData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Daily Revenue Trend'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
