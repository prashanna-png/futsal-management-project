<aside class="sidebar">

  <?php $currentPage = $currentPage ?? ''; ?>
  <div class="logo">
    ⚽ FutsalHub
  </div>

  <nav class="menu">

    <a href="dashboard.php"
      class="<?= ($currentPage == 'dashboard') ? 'active' : '' ?>">Dashboard</a>

    <a href="register_futsal.php"
      class="<?= ($currentPage == 'addFutsal') ? 'active' : '' ?>">Add Futsal</a>

    <a href="my_futsal.php"
      class="<?= ($currentPage == 'myFutsal') ? 'active' : '' ?>">My Futsals</a>

    <a href="manage_bookings.php"
      class="<?= ($currentPage == 'manageBooking') ? 'active' : '' ?>">Bookings</a>

    <a href="profile.php"
      class="<?= ($currentPage == 'profile') ? 'active' : '' ?>">Profile</a>

  </nav>

  <div class="logout menu">
    <a href="../logout.php">Logout</a>
  </div>

</aside>