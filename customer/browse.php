<?php
require_once '../config/auth.php';
require_once '../config/db.php';
require_login();
$currentPage = 'browse';

$sql = "SELECT * FROM futsal WHERE status='approved'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Dashboard</title>

  <link rel="stylesheet" href="../assets/css/customer.css">

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
          <h1>Browse Futsals</h1>
          <p>Find and book the perfect futsal for your next match.</p>
        </div>
      </div>

      <div class="search-box">
        <input type="text" placeholder="Search futsal...">
      </div>

      <div class="futsal-grid">
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $futsalid = $row['futsalid'];
            $facilitySQL = "SELECT * FROM facility WHERE futsalid='$futsalid'";
            $facilityresult = mysqli_query($conn, $facilitySQL);

        ?>
            <div class="futsal-card">
              <img src="../assets/uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Futsal">
              <h3>
                <?php
                echo htmlentities($row['name']);
                ?>
              </h3>
              <p>📍
                <?php
                echo htmlentities($row['location']);
                ?>
              </p>
              <p>⭐⭐⭐⭐☆ (4.8)</p>
              <p>Rs. <?php
                      echo htmlentities($row['price_per_hour']);
                      ?> / hour</p>

              <div class="facilities">
                <?php
                while ($facility = mysqli_fetch_assoc($facilityresult)) {
                ?>
                  <span>
                    <?php echo htmlspecialchars($facility['facility_name']); ?>
                  </span>
                <?php
                }
                ?>
              </div>

              <button>View Details</button>
            </div>
        <?php
          }
        }
        ?>
      </div>
    </main>

  </div>

</body>

</html>