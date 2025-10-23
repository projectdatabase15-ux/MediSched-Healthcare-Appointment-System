
DROP TABLE IF EXISTS prescriptions;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS timeslots;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS users;

CREATE TABLE users(
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  passhash CHAR(64) NOT NULL
);

CREATE TABLE doctors(
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  specialization VARCHAR(120) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE patients(
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  dob DATE NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE timeslots(
  id INT AUTO_INCREMENT PRIMARY KEY,
  start_ts DATETIME NOT NULL,
  end_ts DATETIME NOT NULL,
  CONSTRAINT chk_time CHECK (end_ts > start_ts)
);

CREATE TABLE appointments(
  id INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id INT NOT NULL,
  timeslot_id INT NOT NULL,
  reason VARCHAR(255),
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
  FOREIGN KEY (doctor_id)  REFERENCES doctors(id)  ON DELETE RESTRICT,
  FOREIGN KEY (timeslot_id) REFERENCES timeslots(id) ON DELETE RESTRICT,
  CONSTRAINT uniq_doctor_slot UNIQUE (doctor_id, timeslot_id)
);

CREATE TABLE prescriptions(
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT NOT NULL UNIQUE,
  medication VARCHAR(160) NOT NULL,
  dosage VARCHAR(160) NOT NULL,
  notes TEXT,
  FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);
