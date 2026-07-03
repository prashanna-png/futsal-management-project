<aside class="sidebar">

  <div class="logo">
    Futsal<span>Hub</span>
  </div>

  <nav class="menu">

    <a href="dashboard.php"
      class="<?= ($currentPage == 'dashboard') ? 'active' : '' ?>">
      Dashboard
    </a>

    <a href="manage_futsals.php"
      class="<?= ($currentPage == 'manageFutsals') ? 'active' : '' ?>">
      Manage Futsals
    </a>

    <a href="manage_users.php"
      class="<?= ($currentPage == 'manageUsers') ? 'active' : '' ?>">
      Manage Users
    </a>

    <a href="manage_bookings.php"
      class="<?= ($currentPage == 'manageBookings') ? 'active' : '' ?>">
      Manage Bookings
    </a>

    <a href="reports.php"
      class="<?= ($currentPage == 'reports') ? 'active' : '' ?>">
      Reports
    </a>

    <a href="profile.php"
      class="<?= ($currentPage == 'profile') ? 'active' : '' ?>">
      My Profile
    </a>

  </nav>

  <div class="logout">

    <a href="../logout.php">
      Logout
    </a>

  </div>

</aside>