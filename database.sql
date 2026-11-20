CREATE DATABASE IF NOT EXISTS school_db CHARACTER SET utf8mb4;
USE school_db;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);
-- login: admin / password
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE students (
  student_id VARCHAR(10) PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  class TINYINT NOT NULL,
  section CHAR(1) NOT NULL,
  gender ENUM('Male','Female','Other') NOT NULL,
  attendance DECIMAL(5,2) DEFAULT 0,
  marks INT DEFAULT 0
);
INSERT INTO students VALUES
('S001','Rahul Sharma',10,'A','Male',92,85),
('S002','Priya Singh',9,'A','Female',95,91),
('S003','Aman Verma',10,'B','Male',88,78),
('S004','Anjali Gupta',8,'B','Female',75,89),
('S005','Riya Patel',11,'C','Female',80,82);

CREATE TABLE teachers (
  teacher_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  subject VARCHAR(60) NOT NULL,
  phone VARCHAR(15),
  email VARCHAR(100)
);
INSERT INTO teachers (name, subject, phone, email) VALUES
('Sunita Mehra','Mathematics','9876500001','sunita@school.com'),
('Vikram Joshi','Science','9876500002','vikram@school.com'),
('Neha Kapoor','English','9876500003','neha@school.com');

CREATE TABLE attendance_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(10) NOT NULL,
  att_date DATE NOT NULL,
  status ENUM('Present','Absent') NOT NULL,
  UNIQUE KEY uq (student_id, att_date),
  FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

CREATE TABLE exams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(10) NOT NULL,
  exam_name VARCHAR(60) NOT NULL,
  subject VARCHAR(60) NOT NULL,
  marks_obtained INT NOT NULL,
  max_marks INT NOT NULL DEFAULT 100,
  FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);
INSERT INTO exams (student_id, exam_name, subject, marks_obtained, max_marks) VALUES
('S001','Mid Term','Mathematics',88,100),
('S002','Mid Term','Science',93,100),
('S003','Mid Term','English',74,100);

CREATE TABLE fees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id VARCHAR(10) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  due_date DATE NOT NULL,
  status ENUM('Paid','Pending') NOT NULL DEFAULT 'Pending',
  FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);
INSERT INTO fees (student_id, amount, due_date, status) VALUES
('S001',5000,'2026-12-10','Paid'),
('S002',5000,'2026-12-10','Paid'),
('S003',5000,'2026-12-10','Pending'),
('S004',4500,'2026-12-10','Pending'),
('S005',5500,'2026-12-10','Paid');
