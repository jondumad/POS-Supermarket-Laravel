@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
      <div>
        <h1 class="h3 text-gray-800">Reports & Analytics</h1>
        <p class="text-muted mb-0">Comprehensive business insights and data analysis</p>
      </div>
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
          <i class="fas fa-download me-2"></i>Export Reports
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="{{ route('reports.export.sales') }}">
              <i class="fas fa-chart-line me-2"></i>Sales Report
            </a></li>
          <li><a class="dropdown-item" href="{{ route('reports.export.inventory') }}">
              <i class="fas fa-boxes me-2"></i>Inventory Report
            </a></li>
        </ul>
      </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Today's Sales</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($todaySales ?? 0, 2) }}
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
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monthly Revenue</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">
                  ${{ number_format($monthlyRevenue ?? 0, 2) }}
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
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Low Stock Items</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Customers</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeCustomers ?? 0 }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Report Categories -->
    <div class="row">
      <!-- Sales Reports -->
      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex align-items-center">
              <i class="fas fa-chart-bar fa-lg me-3"></i>
              <h6 class="m-0 font-weight-bold">Sales Reports</h6>
            </div>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Analyze sales performance, trends, and revenue metrics</p>
            <div class="report-links">
              <a href="{{ route('reports.sales') }}" class="report-link-item">
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded bg-light hover-effect">
                  <div class="d-flex align-items-center">
                    <div class="report-icon bg-primary text-white me-3">
                      <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-gray-800">Sales Analytics</div>
                      <div class="small text-muted">Revenue trends and transaction analysis</div>
                    </div>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
                </div>
              </a>
              <a href="{{ route('reports.financial') }}" class="report-link-item">
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded bg-light hover-effect">
                  <div class="d-flex align-items-center">
                    <div class="report-icon bg-success text-white me-3">
                      <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-gray-800">Financial Summary</div>
                      <div class="small text-muted">Profit margins and cost analysis</div>
                    </div>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
                </div>
              </a>
              <a href="{{ route('reports.product-performance') }}" class="report-link-item">
                <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light hover-effect">
                  <div class="d-flex align-items-center">
                    <div class="report-icon bg-info text-white me-3">
                      <i class="fas fa-box"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-gray-800">Product Performance</div>
                      <div class="small text-muted">Best sellers and stock turnover</div>
                    </div>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Inventory Reports -->
      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-gradient-success text-white py-3">
            <div class="d-flex align-items-center">
              <i class="fas fa-warehouse fa-lg me-3"></i>
              <h6 class="m-0 font-weight-bold">Inventory Reports</h6>
            </div>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Monitor stock levels, valuation, and inventory health</p>
            <div class="report-links">
              <a href="{{ route('reports.inventory') }}" class="report-link-item">
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded bg-light hover-effect">
                  <div class="d-flex align-items-center">
                    <div class="report-icon bg-warning text-white me-3">
                      <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-gray-800">Stock Overview</div>
                      <div class="small text-muted">Current stock levels and alerts</div>
                    </div>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
                </div>
              </a>
              <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded bg-light">
                <div class="d-flex align-items-center">
                  <div class="report-icon bg-secondary text-white me-3">
                    <i class="fas fa-chart-pie"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-gray-800">Stock Valuation</div>
                    <div class="small text-muted">Inventory value by category</div>
                  </div>
                </div>
                <span class="badge bg-secondary">Included</span>
              </div>
              <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light">
                <div class="d-flex align-items-center">
                  <div class="report-icon bg-danger text-white me-3">
                    <i class="fas fa-exclamation-circle"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-gray-800">Stock Alerts</div>
                    <div class="small text-muted">Low stock and reorder notifications</div>
                  </div>
                </div>
                <span class="badge bg-secondary">Included</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Customer Reports -->
      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-gradient-info text-white py-3">
            <div class="d-flex align-items-center">
              <i class="fas fa-users fa-lg me-3"></i>
              <h6 class="m-0 font-weight-bold">Customer Reports</h6>
            </div>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Customer behavior, loyalty, and purchase patterns</p>
            <div class="report-links">
              <a href="{{ route('reports.customers') }}" class="report-link-item">
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded bg-light hover-effect">
                  <div class="d-flex align-items-center">
                    <div class="report-icon bg-primary text-white me-3">
                      <i class="fas fa-user-friends"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-gray-800">Customer Analytics</div>
                      <div class="small text-muted">Top customers and purchase history</div>
                    </div>
                  </div>
                  <i class="fas fa-chevron-right text-muted"></i>
                </div>
              </a>
              <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded bg-light">
                <div class="d-flex align-items-center">
                  <div class="report-icon bg-success text-white me-3">
                    <i class="fas fa-user-plus"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-gray-800">New Customers</div>
                    <div class="small text-muted">Customer acquisition trends</div>
                  </div>
                </div>
                <span class="badge bg-secondary">Included</span>
              </div>
              <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light">
                <div class="d-flex align-items-center">
                  <div class="report-icon bg-info text-white me-3">
                    <i class="fas fa-shopping-cart"></i>
                  </div>
                  <div>
                    <div class="fw-bold text-gray-800">Purchase Behavior</div>
                    <div class="small text-muted">Average order value and frequency</div>
                  </div>
                </div>
                <span class="badge bg-secondary">Included</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-gradient-secondary text-white py-3">
            <div class="d-flex align-items-center">
              <i class="fas fa-tools fa-lg me-3"></i>
              <h6 class="m-0 font-weight-bold">Quick Actions</h6>
            </div>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">Export data and generate custom reports</p>
            <div class="quick-actions">
              <div class="row">
                <div class="col-6 mb-3">
                  <a href="{{ route('reports.export.sales') }}"
                    class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                    <i class="fas fa-file-csv fa-2x mb-2"></i>
                    <span class="small">Export Sales</span>
                  </a>
                </div>
                <div class="col-6 mb-3">
                  <a href="{{ route('reports.export.inventory') }}"
                    class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                    <i class="fas fa-file-excel fa-2x mb-2"></i>
                    <span class="small">Export Inventory</span>
                  </a>
                </div>
                <div class="col-6">
                  <button class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3"
                    onclick="printReports()">
                    <i class="fas fa-print fa-2x mb-2"></i>
                    <span class="small">Print Reports</span>
                  </button>
                </div>
                <div class="col-6">
                  <button class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3"
                    onclick="scheduleReport()">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <span class="small">Schedule Report</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">Recent Report Activity</h6>
              </div>
              <div class="col-auto">
                <div class="dropdown no-arrow">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow">
                    <div class="dropdown-header">Report Actions:</div>
                    <a class="dropdown-item" href="#">View All Activity</a>
                    <a class="dropdown-item" href="#">Clear History</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="timeline">
              <div class="timeline-item">
                <div class="timeline-marker bg-primary"></div>
                <div class="timeline-content">
                  <h6 class="timeline-title">Sales Report Generated</h6>
                  <p class="timeline-text text-muted">Monthly sales report for {{ date('F Y') }} was generated
                    successfully</p>
                  <small class="timeline-time text-muted">{{ now()->diffForHumans() }}</small>
                </div>
              </div>
              <div class="timeline-item">
                <div class="timeline-marker bg-success"></div>
                <div class="timeline-content">
                  <h6 class="timeline-title">Inventory Report Exported</h6>
                  <p class="timeline-text text-muted">Inventory data exported to CSV format</p>
                  <small class="timeline-time text-muted">{{ now()->subHours(2)->diffForHumans() }}</small>
                </div>
              </div>
              <div class="timeline-item">
                <div class="timeline-marker bg-info"></div>
                <div class="timeline-content">
                  <h6 class="timeline-title">Customer Analytics Updated</h6>
                  <p class="timeline-text text-muted">Customer performance metrics refreshed</p>
                  <small class="timeline-time text-muted">{{ now()->subHours(5)->diffForHumans() }}</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Custom Styles -->
  <style>
    /* Border styles for stats cards */
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

    /* Card styling */
    .card {
      border-radius: 12px;
      border: none !important;
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
      border-radius: 12px 12px 0 0 !important;
      border-bottom: 1px solid #e3e6f0;
    }

    /* Gradient backgrounds */
    .bg-gradient-primary {
      background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    .bg-gradient-success {
      background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }

    .bg-gradient-info {
      background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }

    .bg-gradient-secondary {
      background: linear-gradient(135deg, #858796 0%, #60616f 100%);
    }

    /* Report links */
    .report-link-item {
      text-decoration: none;
      color: inherit;
    }

    .report-link-item:hover .hover-effect {
      background-color: rgba(78, 115, 223, 0.1) !important;
      transform: translateX(5px);
    }

    .hover-effect {
      transition: all 0.2s ease-in-out;
    }

    .report-icon {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
    }

    /* Quick actions */
    .quick-actions .btn {
      transition: all 0.2s ease-in-out;
      height: 100px;
    }

    .quick-actions .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Timeline */
    .timeline {
      position: relative;
      padding-left: 30px;
    }

    .timeline::before {
      content: '';
      position: absolute;
      left: 15px;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #e3e6f0;
    }

    .timeline-item {
      position: relative;
      margin-bottom: 2rem;
    }

    .timeline-marker {
      position: absolute;
      left: -23px;
      top: 5px;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      border: 3px solid #fff;
      box-shadow: 0 0 0 2px #e3e6f0;
    }

    .timeline-content {
      background: #f8f9fc;
      padding: 1rem;
      border-radius: 8px;
      border-left: 3px solid #4e73df;
    }

    .timeline-title {
      margin-bottom: 0.5rem;
      color: #5a5c69;
    }

    .timeline-text {
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .timeline-time {
      font-size: 0.8rem;
    }

    /* Color scheme */
    .text-gray-800 {
      color: #5a5c69 !important;
    }

    .text-gray-300 {
      color: #dddfeb !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .report-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
      }

      .quick-actions .btn {
        height: 80px;
        font-size: 0.85rem;
      }

      .timeline {
        padding-left: 20px;
      }

      .timeline-marker {
        left: -18px;
        width: 12px;
        height: 12px;
      }
    }
  </style>

  <!-- Scripts -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Bootstrap tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });

    function printReports() {
      alert('Print functionality would be implemented here');
    }

    function scheduleReport() {
      alert('Schedule report functionality would be implemented here');
    }
  </script>
@endsection
