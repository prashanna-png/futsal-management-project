<aside class="sidebar">

  <?php $currentPage = $currentPage ?? ''; ?>

  <div class="logo">
    ⚽ FutsalHub
  </div>

  <nav class="menu">

    <a href="dashboard.php"
      class="<?= ($currentPage == 'dashboard') ? 'active' : '' ?>">
      Dashboard
    </a>

    <a href="browse.php"
      class="<?= ($currentPage == 'browse') ? 'active' : '' ?>">
      Browse Futsals
    </a>

    <a href="my_bookings.php"
      class="<?= ($currentPage == 'bookings') ? 'active' : '' ?>">
      My Bookings
    </a>

    <a href="favorites.php"
      class="<?= ($currentPage == 'favorites') ? 'active' : '' ?>">
      Favorites
    </a>

    <a href="profile.php"
      class="<?= ($currentPage == 'profile') ? 'active' : '' ?>">
      Profile
    </a>

    <a href="support.php"
      class="<?= ($currentPage == 'support') ? 'active' : '' ?>">
      Support
    </a>

  </nav>

  <div class="logout menu">
    <a href="../logout.php">Logout</a>
  </div>

</aside>