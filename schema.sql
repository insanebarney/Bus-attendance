CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL DEFAULT ''
);
CREATE TABLE settings (
  setting_key VARCHAR(80) PRIMARY KEY,
  setting_value TEXT NOT NULL
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
  route VARCHAR(50) NOT NULL DEFAULT 'arriving',
  status VARCHAR(120) NOT NULL DEFAULT 'attending',
  PRIMARY KEY (attendance_date, student_id, route),
  FOREIGN KEY (student_id) REFERENCES students(id)
);
INSERT INTO settings(setting_key,setting_value) VALUES
('routes','arriving,departure'),
('attendance_statuses','attending,absent,attending without bus');
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$3Qx1ewfLh17A9ghEfPnWJem9wLJBTSPotI8m5J1Yzx8ViN9bn6A5.');
