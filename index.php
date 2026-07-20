<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FutZo - Futsal Management System</title>

  <link rel="stylesheet" href="./assets/css/landing-page.css">
</head>

<body>

  <nav class="nav-bar">

    <div class="left-section">
      <img src="./assets/logo/main-logo.png" alt="FutZo Logo">
      <span>FutZo</span>
    </div>

    <div class="center-section">
      <a href="#home">Home</a>
      <a href="#features">Features</a>
      <a href="#how-it-works">How It Works</a>
      <a href="#pricing">Pricing</a>
      <a href="#faq">FAQ</a>
    </div>

    <div class="right-section">
      <button class="btn signin-btn" onclick="location.href='register.php'">
        Sign Up
      </button>

      <button class="btn login-btn" onclick="location.href='login.php'">
        Login
      </button>
    </div>

  </nav>

  <main>
    <section class="hero" id="home">

      <div class="left-side">
        <h1>
          Run Your Futsal Arena Like <br>
          a <span>Champion.</span>
        </h1>

        <p>
          Simplify bookings, manage courts, monitor schedules,
          and deliver a seamless experience for your players —
          all from one modern platform.
        </p>

        <div class="hero-buttons">

          <button class="btn login-btn">
            Get Started
          </button>

          <button class="btn signin-btn">
            Learn More
          </button>

        </div>

        <div class="hero-stats">

          <div class="stat">
            <h3>500+</h3>
            <p>Bookings</p>
          </div>

          <div class="stat">
            <h3>100+</h3>
            <p>Customers</p>
          </div>

          <div class="stat">
            <h3>24/7</h3>
            <p>Availability</p>
          </div>

        </div>

      </div>

      <div class="right-side">

        <img src="./assets/images/futsal-players.png"
          alt="Futsal Players">

      </div>

    </section>

    <section class="stats">
      <div>
        <h1>50+</h1>
        <p>Registered Arenas</p>
      </div>
      <div>
        <h1>1,200+</h1>
        <p>Bookings Managed</p>
      </div>
      <div>
        <h1>300+</h1>
        <p>Happy Players</p>
      </div>
      <div>
        <h1>99%</h1>
        <p>Uptime</p>
      </div>
    </section>

    <section class="features" id="features">
      <p class="heading">EVERY THING IN ONE PLACE</p>
      <span>The Complete Toolkit</span>
      <span>For Futsal Operator</span>
      <p class="desc" style="margin-top: 30px;">From the first bookingto the championship final, PitchPro handles</p>
      <p class="desc">the operations so your arena runs at full capacity</p>

      <div class="cards">

        <div class="card">
          <h1>Smart Court Management</h1>
          <p>Manage multiple futsal courts, update pricing, facilities, opening hours, and court information from one dashboard.</p>
        </div>

        <div class="card">
          <h1>Online Booking</h1>
          <p>Customers can browse available courts, choose time slots, and confirm bookings instantly with a few clicks.</p>
        </div>

        <div class="card">
          <h1>Time Slot Scheduling</h1>
          <p>Create and manage available time slots, avoid double bookings, and keep your schedule organized.</p>
        </div>

        <div class="card">
          <h1>Customer Management</h1>
          <p>View customer details, booking history, and manage booking requests efficiently.</p>
        </div>

        <div class="card">
          <h1>Analytics Dashboard</h1>
          <p>Monitor bookings, revenue, pending requests, and court performance through an easy-to-read dashboard.</p>
        </div>

        <div class="card">
          <h1>ASecure Role-Based System</h1>
          <p>Separate dashboards and permissions for Customers, Owners, Staff, and Administrators to keep your system organized and secure.</p>
        </div>
      </div>
    </section>

  </main>

</body>

</html>