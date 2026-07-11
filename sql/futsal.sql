USE futsal_system;
-- USERS
CREATE TABLE users (
  userid INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(15) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer', 'owner', 'staff', 'admin') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
select *
from futsal;

select *
from futsal WHERE ownerid = 2 AND futsalid = 14;
select *
from users;
-- FUTSAL COURTS
CREATE TABLE futsal (
  futsalid INT AUTO_INCREMENT PRIMARY KEY,
  ownerid INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(100) NOT NULL,
  address TEXT,
  description TEXT,
  price_per_hour DECIMAL(10, 2) NOT NULL,
  opening_time TIME NOT NULL,
  closing_time TIME NOT NULL,
  contact_number VARCHAR(15),
  image VARCHAR(255),
  status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (ownerid) REFERENCES users(userid) ON DELETE CASCADE
);
-- FACILITIES
CREATE TABLE facility (
  facilityid INT AUTO_INCREMENT PRIMARY KEY,
  futsalid INT NOT NULL,
  facility_name VARCHAR(50) NOT NULL,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE
);
-- TIMESLOTS
CREATE TABLE timeslot (
  slotid INT AUTO_INCREMENT PRIMARY KEY,
  futsalid INT NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE
);

select * FROM timeslot;

-- BOOKINGS
CREATE TABLE booking (
  bookingid INT AUTO_INCREMENT PRIMARY KEY,
  playerid INT NOT NULL,
  futsalid INT NOT NULL,
  staffid INT DEFAULT NULL,
  booking_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (playerid) REFERENCES users(userid) ON DELETE CASCADE,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE,
  FOREIGN KEY (staffid) REFERENCES users(userid) ON DELETE
  SET NULL
);
-- PAYMENTS
CREATE TABLE payment (
  paymentid INT AUTO_INCREMENT PRIMARY KEY,
  bookingid INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  method ENUM('cash', 'esewa', 'khalti', 'card') DEFAULT 'cash',
  transaction_id VARCHAR(100),
  status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (bookingid) REFERENCES booking(bookingid) ON DELETE CASCADE
);
-- REVIEWS
CREATE TABLE review (
  reviewid INT AUTO_INCREMENT PRIMARY KEY,
  playerid INT NOT NULL,
  futsalid INT NOT NULL,
  rating INT CHECK (
    rating BETWEEN 1 AND 5
  ),
  comment VARCHAR(255),
  review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(playerid, futsalid),
  FOREIGN KEY (playerid) REFERENCES users(userid) ON DELETE CASCADE,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE
);
-- DEFAULT ADMIN (email: admin@futsal.com | password: admin123)
INSERT INTO users (name, email, phone, password, role)
VALUES (
    'Admin',
    'admin@futsal.com',
    '9800000000',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin'
  );
select *
from users;
update users
set password = '$2y$10$N.wUeOnE4vbr2eY53EZC8eTCZQYNGNVJc53LhoDOt6psYhDzyeRlC'
where email = 'admin@futsal.com';