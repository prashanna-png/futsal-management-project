<?php
session_start();
require_once '../config/auth.php';
require_login();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Futsal</title>

  <link rel="stylesheet" href="../assets/css/owner.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

  <div class="dashboard">

    <?php include 'includes/sidebar.php'; ?>

    <main class="main">

      <div class="header">

        <div>
          <h1>Register New Futsal</h1>
          <p>Fill in the details below to register your futsal.</p>
        </div>

      </div>

      <div class="form-container">

        <form action="" method="POST" enctype="multipart/form-data">

          <div class="form-group">

            <label>Futsal Name</label>
            <input
              type="text"
              name="name"
              placeholder="Enter futsal name"
              required>

          </div>

          <div class="form-group">

            <label>Location</label>
            <input
              type="text"
              name="location"
              placeholder="Enter city/location"
              required>

          </div>

          <div class="form-group">

            <label>Address</label>
            <textarea
              name="address"
              placeholder="Enter complete address"></textarea>

          </div>

          <div class="form-group">

            <label>Description</label>

            <textarea
              name="description"
              placeholder="Describe your futsal"></textarea>

          </div>

          <div class="row">

            <div class="form-group">

              <label>Price Per Hour (Rs.)</label>

              <input
                type="number"
                name="price_per_hour"
                placeholder="1500"
                required>

            </div>

            <div class="form-group">

              <label>Contact Number</label>

              <input
                type="text"
                name="contact_number"
                placeholder="98XXXXXXXX"
                required>

            </div>

          </div>

          <div class="row">

            <div class="form-group">

              <label>Opening Time</label>

              <input
                type="time"
                name="opening_time"
                required>

            </div>

            <div class="form-group">

              <label>Closing Time</label>

              <input
                type="time"
                name="closing_time"
                required>

            </div>

          </div>

          <div class="form-group">

            <label>Upload Image</label>

            <input
              type="file"
              name="image"
              accept="image/*">

          </div>

          <div class="form-group">

            <label>Facilities</label>

            <div class="facility-grid">

              <label>
                <input type="checkbox" name="facility[]" value="Parking">
                Parking
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="WiFi">
                WiFi
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Shower">
                Shower
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Locker Room">
                Locker Room
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Cafeteria">
                Cafeteria
              </label>

            </div>

          </div>

          <button
            type="submit"
            class="btn">

            Register Futsal

          </button>

        </form>

      </div>

    </main>

  </div>

</body>

</html>