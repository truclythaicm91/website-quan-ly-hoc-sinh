CREATE DATABASE IF NOT EXISTS quan_ly_hoc_sinh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quan_ly_hoc_sinh;

CREATE TABLE IF NOT EXISTS users (
 id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(80) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL,
 full_name VARCHAR(150) NOT NULL, role VARCHAR(30) NOT NULL DEFAULT 'teacher', active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS teachers (
 id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(30) UNIQUE NOT NULL, full_name VARCHAR(150) NOT NULL,
 gender VARCHAR(10), dob DATE NULL, phone VARCHAR(30), email VARCHAR(120), subject VARCHAR(120), address VARCHAR(255), qualification VARCHAR(120), position_name VARCHAR(120), personnel_type VARCHAR(30) NOT NULL DEFAULT 'Giáo viên', active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS classes (
 id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(30) UNIQUE NOT NULL, name VARCHAR(100) NOT NULL, grade INT NOT NULL,
 school_year VARCHAR(30) NOT NULL, teacher_id INT NULL, room VARCHAR(50), session_name VARCHAR(50), capacity INT DEFAULT 35,
 FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS students (
 id INT AUTO_INCREMENT PRIMARY KEY, code VARCHAR(30) UNIQUE NOT NULL, full_name VARCHAR(150) NOT NULL,
 dob DATE NULL, gender VARCHAR(20), place_of_birth VARCHAR(150), ethnicity VARCHAR(80), religion VARCHAR(80), nationality VARCHAR(80) DEFAULT 'Việt Nam',
 citizen_id VARCHAR(30), health_insurance_no VARCHAR(50), social_insurance_no VARCHAR(50),
 permanent_address VARCHAR(255), current_address VARCHAR(255), hamlet VARCHAR(100), commune VARCHAR(100), district VARCHAR(100), province VARCHAR(100),
 father_name VARCHAR(150), father_dob YEAR NULL, father_job VARCHAR(150), father_phone VARCHAR(30), father_address VARCHAR(255),
 mother_name VARCHAR(150), mother_dob YEAR NULL, mother_job VARCHAR(150), mother_phone VARCHAR(30), mother_address VARCHAR(255),
 guardian_name VARCHAR(150), guardian_relation VARCHAR(80), guardian_phone VARCHAR(30), guardian_address VARCHAR(255),
 household_head VARCHAR(150), household_code VARCHAR(50), household_type VARCHAR(100),
 class_id INT NULL, school_year VARCHAR(30), admission_date DATE NULL, previous_school VARCHAR(255), enrollment_type VARCHAR(100),
 status VARCHAR(50) DEFAULT 'Đang học', transfer_date DATE NULL, transfer_destination VARCHAR(255),
 policy_category VARCHAR(150), poor_household TINYINT(1) DEFAULT 0, near_poor_household TINYINT(1) DEFAULT 0, disability TINYINT(1) DEFAULT 0, disability_type VARCHAR(150),
 boarding_status VARCHAR(100), priority_status VARCHAR(150), notes TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL,
 INDEX idx_student_name(full_name), INDEX idx_student_class(class_id), INDEX idx_student_status(status)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS student_documents (
 id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, document_type VARCHAR(100) NOT NULL, document_no VARCHAR(100), issue_date DATE NULL, issuer VARCHAR(200), note VARCHAR(255),
 FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS attendance (
 id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, date DATE NOT NULL, status VARCHAR(50) NOT NULL, note VARCHAR(255),
 UNIQUE KEY uq_attendance(student_id,date), FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS subjects (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) UNIQUE NOT NULL) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS assessments (
 id INT AUTO_INCREMENT PRIMARY KEY, student_id INT NOT NULL, subject_id INT NOT NULL, period VARCHAR(100) NOT NULL, result VARCHAR(100), comment TEXT,
 UNIQUE KEY uq_assessment(student_id,subject_id,period), FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE CASCADE, FOREIGN KEY(subject_id) REFERENCES subjects(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS announcements (
 id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at DATETIME NOT NULL, created_by INT NULL,
 FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;
