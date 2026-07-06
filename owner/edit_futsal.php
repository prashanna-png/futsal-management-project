<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets//css//owner.css">
  <title>Edit futsal</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
  <div class="dashboard">
    <?php
    include 'includes/sidebar.php';
    ?>

    <main class="main">

      <div class="header">

        <div>
          <a href="my_futsal.php" class="back-link">
            ← Back to My Futsals
          </a>

          <h1>Edit Futsal</h1>
          <p>Update your futsal details.</p>
        </div>

      </div>

      <div class="form-container">

        <form action="" method="POST" enctype="multipart/form-data">

          <div class="row">

            <div class="form-group">
              <label>Futsal Name</label>
              <input type="text" name="name" value="Yala Futsal">
            </div>

            <div class="form-group">
              <label>Price Per Hour (Rs.)</label>
              <input type="number" name="price_per_hour" value="900">
            </div>

          </div>

          <div class="row">

            <div class="form-group">
              <label>Location</label>
              <input type="text" name="location" value="Lalitpur">
            </div>

            <div class="form-group">
              <label>Contact Number</label>
              <input type="text" name="contact_number" value="98XXXXXXXX">
            </div>

          </div>

          <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" value="Sanepa, Lalitpur, Nepal">
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="5">Good quality turf, spacious ground and parking available.</textarea>
          </div>

          <div class="row">

            <div class="form-group">
              <label>Opening Time</label>
              <input type="time" name="opening_time" value="09:00">
            </div>

            <div class="form-group">
              <label>Closing Time</label>
              <input type="time" name="closing_time" value="22:00">
            </div>

          </div>

          <div class="form-group">

            <label>Upload Image</label>

            <div class="image-upload">

              <img src="../assets/uploads/sample.jpg" alt="Current Image" class="preview-image">

              <input type="file" name="image" accept="image/*">

            </div>

          </div>

          <div class="form-group">

            <label>Facilities</label>

            <div class="facility-grid">

              <label>
                <input type="checkbox" name="facility[]" value="Parking" checked>
                Parking
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Locker Room" checked>
                Locker Room
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="WiFi" checked>
                WiFi
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Cafeteria" checked>
                Cafeteria
              </label>

              <label>
                <input type="checkbox" name="facility[]" value="Shower">
                Shower
              </label>

            </div>

          </div>

          <div class="status-note approved">
            <strong>Current Status:</strong> Approved
            <br>
            Editing this futsal may require administrator approval again.
          </div>

          <div class="form-actions">

            <a href="my_futsal.php" class="cancel-btn">
              Cancel
            </a>

            <button type="submit" class="btn">
              Save Changes
            </button>

          </div>

        </form>

      </div>

    </main>
  </div>


</body>

</html>