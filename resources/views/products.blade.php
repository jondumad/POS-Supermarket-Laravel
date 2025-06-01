@extends('theme.default')

@section('content')
  <div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
      <div>
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
        <p class="text-muted mb-0">Manage your product inventory</p>
      </div>
      <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg shadow-sm">
        <i class="fas fa-plus me-2"></i>Create Product
      </a>
    </div>

    <!-- Stats Cards (Optional - you can remove if not needed) -->
    <div class="row mb-4">
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Products</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-box fa-2x text-gray-300"></i>
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
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Products</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $products->where('is_active', 1)->count() }}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- View Toggle Buttons -->
    <div class="row mb-3">
      <div class="col-12">
        <div class="btn-group" role="group" aria-label="View Toggle">
          <button type="button" class="btn btn-outline-secondary active" id="cardView">
            <i class="fas fa-th-large me-1"></i>Card View
          </button>
          <button type="button" class="btn btn-outline-secondary" id="tableView">
            <i class="fas fa-table me-1"></i>Table View
          </button>
        </div>
      </div>
    </div>

    <!-- Card View (Default) -->
    <div id="cardViewContainer">
      @forelse($products as $product)
        @if ($loop->first || $loop->index % 3 == 0)
          <div class="row mb-4">
        @endif

        <div class="col-lg-4 col-md-6 mb-4">
          <div class="card shadow-sm border-0 h-100 product-card">
            <!-- Product Image -->
            <div class="card-img-container position-relative" style="height: 200px; overflow: hidden;">
              @if ($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top h-100 w-100"
                  style="object-fit: cover;" alt="{{ $product->name }}">
              @else
                <div class="d-flex align-items-center justify-content-center h-100 bg-light">
                  <i class="fas fa-image fa-3x text-muted"></i>
                </div>
              @endif
              <!-- Status Badge -->
              <span
                class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }} position-absolute top-0 end-0 m-2">
                {{ $product->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>

            <!-- Card Body -->
            <div class="card-body d-flex flex-column">
              <div class="mb-auto">
                <h5 class="card-title mb-2 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                <p class="card-text text-muted small mb-2">
                  <strong>SKU:</strong> {{ $product->sku }}<br>
                  <strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}
                </p>
              </div>

              <!-- Pricing -->
              <div class="pricing-section mb-3">
                <div class="row text-center">
                  <div class="col-6">
                    <small class="text-muted d-block">Purchase Price</small>
                    <span class="h6 text-info">${{ number_format($product->purchase_price, 2) }}</span>
                  </div>
                  <div class="col-6">
                    <small class="text-muted d-block">Selling Price</small>
                    <span class="h6 text-success">${{ number_format($product->selling_price, 2) }}</span>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="d-flex gap-2 mt-auto">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm flex-fill">
                  <i class="fas fa-edit me-1"></i>Edit
                </a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-fill"
                  onsubmit="return confirm('Are you sure you want to delete this product?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="fas fa-trash me-1"></i>Delete
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>

        @if ($loop->last || ($loop->index + 1) % 3 == 0)
    </div>
    @endif
  @empty
    <div class="row">
      <div class="col-12">
        <div class="card shadow-sm border-0">
          <div class="card-body text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No Products Found</h4>
            <p class="text-muted mb-4">You haven't added any products yet. Get started by creating your first product.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
              <i class="fas fa-plus me-2"></i>Create Your First Product
            </a>
          </div>
        </div>
      </div>
    </div>
    @endforelse
  </div>

  <!-- Table View (Initially Hidden) -->
  <div id="tableViewContainer" style="display: none;">
    <div class="card shadow-sm border-0">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
              <tr>
                <th width="5%">ID</th>
                <th width="10%">Image</th>
                <th width="20%">Name</th>
                <th width="10%">SKU</th>
                <th width="15%">Category</th>
                <th width="10%">Purchase Price</th>
                <th width="10%">Selling Price</th>
                <th width="8%">Status</th>
                <th width="12%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($products as $product)
                <tr>
                  <td>{{ $product->id }}</td>
                  <td>
                    @if ($product->image_path)
                      <img src="{{ asset('storage/' . $product->image_path) }}" alt="Product Image" class="rounded"
                        width="40" height="40" style="object-fit: cover;">
                    @else
                      <div class="bg-light rounded d-flex align-items-center justify-content-center"
                        style="width: 40px; height: 40px;">
                        <i class="fas fa-image text-muted"></i>
                      </div>
                    @endif
                  </td>
                  <td>
                    <div class="fw-bold">{{ $product->name }}</div>
                  </td>
                  <td>{{ $product->sku }}</td>
                  <td>{{ $product->category->name ?? 'N/A' }}</td>
                  <td class="text-info">${{ number_format($product->purchase_price, 2) }}</td>
                  <td class="text-success">${{ number_format($product->selling_price, 2) }}</td>
                  <td>
                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                      {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning"
                        title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                        style="display:inline-block;"
                        onsubmit="return confirm('Are you sure you want to delete this product?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                    <div class="text-muted">No products found.</div>
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

  <!-- Custom Styles -->
  <style>
    .product-card {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
      border-radius: 12px;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }

    .card-img-container {
      border-radius: 12px 12px 0 0;
    }

    .border-left-primary {
      border-left: 4px solid #4e73df !important;
    }

    .border-left-success {
      border-left: 4px solid #1cc88a !important;
    }

    .pricing-section {
      background: rgba(0, 0, 0, 0.02);
      border-radius: 8px;
      padding: 15px;
    }

    .btn-group .btn {
      border-radius: 6px !important;
      margin-right: 2px;
    }

    .table th {
      border-top: none;
      font-weight: 600;
      color: #5a5c69;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
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
  </style>

  <!-- JavaScript for View Toggle -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const cardViewBtn = document.getElementById('cardView');
      const tableViewBtn = document.getElementById('tableView');
      const cardViewContainer = document.getElementById('cardViewContainer');
      const tableViewContainer = document.getElementById('tableViewContainer');

      cardViewBtn.addEventListener('click', function() {
        cardViewBtn.classList.add('active');
        tableViewBtn.classList.remove('active');
        cardViewContainer.style.display = 'block';
        tableViewContainer.style.display = 'none';
      });

      tableViewBtn.addEventListener('click', function() {
        tableViewBtn.classList.add('active');
        cardViewBtn.classList.remove('active');
        cardViewContainer.style.display = 'none';
        tableViewContainer.style.display = 'block';
      });
    });
  </script>
@endsection
