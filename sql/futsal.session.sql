USE futsal_management_system;
CREATE TABLE users (
  userid INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  phone VARCHAR(15) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer', 'owner', 'staff', 'admin') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from users;

CREATE TABLE futsal (
  futsalid INT AUTO_INCREMENT PRIMARY KEY,
  ownerid INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  location VARCHAR(100) NOT NULL,
  description TEXT,
  price_per_hour DECIMAL(10, 2) NOT NULL,
  status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (ownerid) REFERENCES users(userid) ON DELETE CASCADE
);
CREATE TABLE timeslot (
  slotid INT AUTO_INCREMENT PRIMARY KEY,
  futsalid INT NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  is_booked BOOLEAN DEFAULT FALSE,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE
);
CREATE TABLE booking (
  bookingid INT AUTO_INCREMENT PRIMARY KEY,
  playerid INT NOT NULL,
  futsalid INT NOT NULL,
  staffid INT,
  booking_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (playerid) REFERENCES users(userid) ON DELETE CASCADE,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE,
  FOREIGN KEY (staffid) REFERENCES users(userid) ON DELETE
  SET NULL
);
CREATE TABLE payment (
  paymentid INT AUTO_INCREMENT PRIMARY KEY,
  bookingid INT NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  method ENUM('cash', 'esewa', 'khalti', 'card') DEFAULT 'cash',
  status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (bookingid) REFERENCES booking(bookingid) ON DELETE CASCADE
);
CREATE TABLE review (
  reviewid INT AUTO_INCREMENT PRIMARY KEY,
  playerid INT NOT NULL,
  futsalid INT NOT NULL,
  rating INT CHECK (
    rating BETWEEN 1 AND 5
  ),
  comment VARCHAR(255),
  review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (playerid) REFERENCES users(userid) ON DELETE CASCADE,
  FOREIGN KEY (futsalid) REFERENCES futsal(futsalid) ON DELETE CASCADE
);