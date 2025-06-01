@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
      <div>
        <h1 class="h3  text-gray-800">Users</h1>
        <p class="text-muted mb-0">Manage user accounts and permissions</p>
      </div>
      <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg shadow-sm">
        <i class="fas fa-plus me-2"></i>Create User
      </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Users</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('is_active', 1)->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Admins</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('role', 'admin')->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user-shield fa-2x text-gray-300"></i>
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
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Regular Users</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->where('role', 'user')->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-user fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white py-3">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="m-0 font-weight-bold text-primary">User Directory</h6>
              </div>
              <div class="col-auto">
                <div class="dropdown no-arrow">
                  <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Actions:</div>
                    <a class="dropdown-item" href="#">Export Users</a>
                    <a class="dropdown-item" href="#">Bulk Actions</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th width="8%">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-hashtag me-2 text-muted"></i>ID
                      </div>
                    </th>
                    <th width="25%">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-user me-2 text-muted"></i>Name
                      </div>
                    </th>
                    <th width="25%">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-envelope me-2 text-muted"></i>Email
                      </div>
                    </th>
                    <th width="15%">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-user-tag me-2 text-muted"></i>Role
                      </div>
                    </th>
                    <th width="12%">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-toggle-on me-2 text-muted"></i>Status
                      </div>
                    </th>
                    <th width="15%">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-cogs me-2 text-muted"></i>Actions
                      </div>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($users as $user)
                    <tr class="user-row">
                      <td>
                        <div class="user-id-badge">
                          {{ $user->id }}
                        </div>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="user-avatar me-3">
                            <div class="avatar-circle bg-primary text-white">
                              {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                          </div>
                          <div>
                            <div class="fw-bold text-gray-800">{{ $user->name }}</div>
                            <div class="small text-muted">Member since {{ $user->created_at->format('M Y') }}</div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="text-gray-800">{{ $user->email }}</div>
                        <div class="small text-muted">
                          <i class="fas fa-circle {{ $user->email_verified_at ? 'text-success' : 'text-warning' }} me-1"
                            style="font-size: 0.5rem;"></i>
                          {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                        </div>
                      </td>
                      <td>
                        <span
                          class="role-badge badge 
                        {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'manager' ? 'bg-warning text-dark' : 'bg-info') }}">
                          <i
                            class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : ($user->role === 'manager' ? 'fa-user-tie' : 'fa-user') }} me-1"></i>
                          {{ ucfirst($user->role) }}
                        </span>
                      </td>
                      <td>
                        <div class="status-indicator">
                          <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                            <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                          </span>
                        </div>
                      </td>
                      <td>
                        <div class="action-buttons">
                          <div class="btn-group btn-group-sm" role="group">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-warning action-btn"
                              title="Edit User" data-bs-toggle="tooltip">
                              <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-outline-info action-btn" title="View Details"
                              data-bs-toggle="tooltip">
                              <i class="fas fa-eye"></i>
                            </button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                              style="display:inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this user?')">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-outline-danger action-btn" title="Delete User"
                                data-bs-toggle="tooltip">
                                <i class="fas fa-trash"></i>
                              </button>
                            </form>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                          <i class="fas fa-users fa-4x text-muted mb-3"></i>
                          <h4 class="text-muted">No Users Found</h4>
                          <p class="text-muted mb-4">You haven't added any users yet. Get started by creating your first
                            user account.</p>
                          <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Your First User
                          </a>
                        </div>
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

    /* Table styling */
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

    /* User row hover effect */
    .user-row {
      transition: all 0.2s ease-in-out;
    }

    .user-row:hover {
      background-color: rgba(78, 115, 223, 0.05) !important;
      transform: translateX(2px);
    }

    /* Avatar styling */
    .avatar-circle {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 1rem;
    }

    /* User ID badge */
    .user-id-badge {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 4px 8px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 0.85rem;
      display: inline-block;
      min-width: 35px;
      text-align: center;
    }

    /* Role badge styling */
    .role-badge {
      font-size: 0.8rem;
      padding: 6px 12px;
      border-radius: 20px;
      font-weight: 500;
    }

    /* Status indicator */
    .status-indicator .badge {
      font-size: 0.75rem;
      border-radius: 20px;
      font-weight: 500;
    }

    /* Action buttons */
    .action-buttons .btn-group .btn {
      border-radius: 6px !important;
      margin-right: 2px;
      transition: all 0.2s ease-in-out;
    }

    .action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Empty state */
    .empty-state {
      padding: 2rem;
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
      .stats-card {
        margin-bottom: 1rem;
      }

      .table-responsive {
        font-size: 0.9rem;
      }

      .avatar-circle {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
      }
    }
  </style>

  <!-- Initialize tooltips -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Bootstrap tooltips
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    });
  </script>
@endsection
