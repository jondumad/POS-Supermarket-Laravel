<div id="layoutSidenav_nav">
  <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
      <div class="nav">
        <div class="sb-sidenav-menu-heading">Core</div>
        <!-- Dashboard -->
        <a class="nav-link" href="/dashboard">
          <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
          Dashboard
        </a>

        <!-- Management Tables -->
        <div class="sb-sidenav-menu-heading">Management</div>

        <a class="nav-link" href="/users">
          <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
          Users
        </a>

        <a class="nav-link" href="/customers">
          <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
          Customers
        </a>

        <a class="nav-link" href="/suppliers">
          <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
          Suppliers
        </a>

        <!-- Inventory -->
        <div class="sb-sidenav-menu-heading">Inventory</div>

        <a class="nav-link" href="/products">
          <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
          Products
        </a>

        <a class="nav-link" href="/categories">
          <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
          Categories
        </a>

        <!-- Transactions -->
        <div class="sb-sidenav-menu-heading">Transactions</div>

        <a class="nav-link" href="/purchase-orders">
          <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
          Orders
        </a>
        <a class="nav-link" href="/sales">
          <div class="sb-nav-link-icon"><i class="fas fa-receipt"></i></div>
          Sales
        </a>
      </div>
    </div>
    <div class="sb-sidenav-footer">
      <div class="small">Logged in as:</div>
      {{ Auth::user()->name ?? 'Guest' }}
    </div>

  </nav>
</div>
