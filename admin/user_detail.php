<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Details</title>

  <link rel="stylesheet" href="../assets/css/admin.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <!-- Sidebar -->

    <aside class="sidebar">

      <div class="logo">
        Futsal<span>Hub</span>
      </div>

      <nav class="menu">

        <a href="#">Dashboard</a>
        <a href="#">Manage Futsals</a>
        <a href="#" class="active">Manage Users</a>
        <a href="#">Manage Bookings</a>
        <a href="#">Reports</a>
        <a href="#">Profile</a>

      </nav>

      <div class="logout">
        <a href="#">Logout</a>
      </div>

    </aside>

    <!-- Main -->

    <main class="main">

      <!-- Header -->

      <div class="header">

        <div>

          <h1>User Details</h1>

          <p>View complete information about this user.</p>

        </div>

        <a href="manage_users.php" class="btn">
          ← Back
        </a>

      </div>

      <!-- Top Section -->

      <div class="profile-layout">

        <!-- Left Card -->

        <div class="profile-card">

          <div class="profile-avatar">
            P
          </div>

          <h2>Prashan</h2>

          <p>Owner</p>

          <div class="profile-status active-status">
            Active
          </div>

        </div>

        <!-- Right Card -->

        <div class="details-card">

          <h2>Personal Information</h2>

          <div class="info-grid">

            <div class="info-box">

              <label>Full Name</label>

              <p>Prashan</p>

            </div>

            <div class="info-box">

              <label>Email</label>

              <p>prashan@gmail.com</p>

            </div>

            <div class="info-box">

              <label>Phone</label>

              <p>9812345678</p>

            </div>

            <div class="info-box">

              <label>Role</label>

              <p>Owner</p>

            </div>

            <div class="info-box">

              <label>Joined On</label>

              <p>2 July 2026</p>

            </div>

            <div class="info-box">

              <label>Total Registered Courts</label>

              <p>3</p>

            </div>

          </div>

        </div>

      </div>

      <!-- Owner Courts -->

      <div class="panel">

        <div class="panel-header">

          <h2>Registered Futsals</h2>

        </div>

        <table>

          <tr>

            <th>Image</th>

            <th>Futsal Name</th>

            <th>Location</th>

            <th>Price</th>

            <th>Status</th>

          </tr>

          <tr>

            <td>
              <img src="../assets/images/futsal.jpg" width="90">
            </td>

            <td>Elite Arena</td>

            <td>Kathmandu</td>

            <td>Rs.1500/hr</td>

            <td>
              <span class="status approved">
                Approved
              </span>
            </td>

          </tr>

          <tr>

            <td>
              <img src="../assets/images/futsal.jpg" width="90">
            </td>

            <td>Goal Arena</td>

            <td>Lalitpur</td>

            <td>Rs.1800/hr</td>

            <td>
              <span class="status pending">
                Pending
              </span>
            </td>

          </tr>

        </table>

      </div>

      <!-- Statistics -->

      <div class="cards">

        <div class="card">

          <h4>Total Futsals</h4>

          <h2>3</h2>

        </div>

        <div class="card">

          <h4>Approved</h4>

          <h2>2</h2>

        </div>

        <div class="card">

          <h4>Pending</h4>

          <h2>1</h2>

        </div>

        <div class="card">

          <h4>Rejected</h4>

          <h2>0</h2>

        </div>

      </div>

      <!-- Action Buttons -->

      <div class="action-buttons">

        <a href="#" class="btn edit-btn">
          Edit User
        </a>

        <a href="#" class="btn delete-btn">
          Delete User
        </a>

      </div>

    </main>

  </div>

</body>

</html>