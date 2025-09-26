-- Doctor availability time slots
CREATE TABLE IF NOT EXISTS doctor_time_slots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  starts_at DATETIME NOT NULL,
  ends_at DATETIME NOT NULL,
  is_booked TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
  INDEX (doctor_id, starts_at),
  INDEX (is_booked)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

