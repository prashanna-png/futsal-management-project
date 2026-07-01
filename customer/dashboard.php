<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>
  <link rel="stylesheet" href="/assets/css/dashboard.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <aside class="sidebar">
      <div class="logo">
        ⚽ FutsalHub
      </div>

      <nav class="menu">
        <a href="#" class="active">Dashboard</a>
        <a href="#">Browse Futsals</a>
        <a href="#">My Bookings</a>
        <a href="#">Favorites</a>
        <a href="#">Profile</a>
        <a href="#">Notifications</a>
        <a href="#">Support</a>
      </nav>
      <div class="logout menu">
        <a href="#">Logout</a>
      </div>
    </aside>

    <main class="main">
      <div class="header">
        <div>
          <h1>Welcome Back, Prashanna 👋</h1>
          <p>Here's what's happening with your account today.</p>
        </div>
        <div class="user">
          <div class="avatar">P</div>
          <div>
            <strong>Prashanna</strong><br>
            Customer
          </div>
        </div>
      </div>
      <div class="cards">
        <div class="card">
          <h4>Total Bookings</h4>
          <h2>08</h2>
        </div>
        <div class="card">
          <h4>Upcoming</h4>
          <h2>02</h2>
        </div>
        <div class="card">
          <h4>Favorites</h4>
          <h2>05</h2>
        </div>
        <div class="card">
          <h4>Total Spent</h4>
          <h2>Rs.12450</h2>
        </div>
      </div>
      <div class="middle">
        <div class="booking">
          <h3>Upcoming Booking</h3>
          <div class="booking-info">
            <strong>Goal Arena Futsal</strong><br>
            Date : 25 May 2026<br>
            Time : 6:00 PM - 7:00 PM<br>
            Status : Confirmed
          </div>
        </div>
        <div class="actions">
          <h3>Quick Actions</h3>
          <div class="action-grid">
            <div class="action">
              Browse
            </div>
            <div class="action">
              Book
            </div>
            <div class="action">
              Bookings
            </div>
            <div class="action">
              Profile
            </div>
          </div>
        </div>
      </div>
      <div class="bottom">
        <div class="table">
          <h3>Recent Bookings</h3>
          <br>
          <table>
            <tr>
              <th>Futsal</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
            <tr>
              <td>KickOff Futsal</td>
              <td>20 May</td>
              <td><span class="status">Completed</span></td>
            </tr>
            <tr>
              <td>Goal Arena</td>
              <td>23 May</td>
              <td><span class="status">Confirmed</span></td>
            </tr>
            <tr>
              <td>Futsal City</td>
              <td>28 May</td>
              <td><span class="status">Pending</span></td>
            </tr>
          </table>
        </div>
        <div class="notice">
          <h3>Announcements</h3>
          <div class="notice-item">
            <strong>Weekend Offer</strong>
            <p>Get 20% off on weekend bookings.</p>
          </div>
          <div class="notice-item">
            <strong>New Futsal Added</strong>
            <p>Elite Arena is now available.</p>
          </div>
          <div class="notice-item">
            <strong>Maintenance</strong>
            <p>Court 2 will remain closed tomorrow.</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>

</html>