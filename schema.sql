CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL DEFAULT ''
);

CREATE TABLE buses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE classes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) UNIQUE NOT NULL,
  name VARCHAR(150) NOT NULL,
  class_id INT NOT NULL,
  bus_id INT NULL,
  FOREIGN KEY (class_id) REFERENCES classes(id),
  FOREIGN KEY (bus_id) REFERENCES buses(id)
);

CREATE TABLE attendance (
  attendance_date DATE NOT NULL,
  student_id INT NOT NULL,
  status ENUM('attending','absent','attending without bus') NOT NULL,
  PRIMARY KEY (attendance_date, student_id),
  FOREIGN KEY (student_id) REFERENCES students(id)
);

-- default admin/password: admin123
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$3Qx1ewfLh17A9ghEfPnWJem9wLJBTSPotI8m5J1Yzx8ViN9bn6A5.');
