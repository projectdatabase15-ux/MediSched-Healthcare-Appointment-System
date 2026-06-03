-- MediSched Database Schema
-- Run with: mysql -u root -p < schema.sql

CREATE DATABASE IF NOT EXISTS medisched CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medisched;

-- ============================================================
-- TABLES
-- ============================================================

CREATE TABLE IF NOT EXISTS users (
  UserID       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  Name         VARCHAR(100)  NOT NULL,
  Email        VARCHAR(150)  NOT NULL UNIQUE,
  Password     VARCHAR(255)  NOT NULL,          -- bcrypt hash
  Role         ENUM('admin','doctor','patient') NOT NULL DEFAULT 'patient',
  CreatedAt    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS doctors (
  DoctorID        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  UserID          INT UNSIGNED NOT NULL,
  Specialty       VARCHAR(100) NOT NULL,
  ExperienceYears SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  CONSTRAINT fk_doctors_user FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS patients (
  PatientID      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  UserID         INT UNSIGNED NOT NULL,
  DateOfBirth    DATE,
  ContactNumber  VARCHAR(20),
  MedicalHistory TEXT,
  CONSTRAINT fk_patients_user FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS timeslots (
  SlotID             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  DoctorID           INT UNSIGNED NOT NULL,
  StartTime          DATETIME     NOT NULL,
  EndTime            DATETIME     NOT NULL,
  AvailabilityStatus ENUM('available','booked','cancelled') NOT NULL DEFAULT 'available',
  CONSTRAINT fk_timeslots_doctor FOREIGN KEY (DoctorID) REFERENCES doctors(DoctorID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS appointments (
  AppointmentID   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  PatientID       INT UNSIGNED NOT NULL,
  DoctorID        INT UNSIGNED NOT NULL,
  TimeSlotID      INT UNSIGNED,
  AppointmentDate DATE         NOT NULL,
  AppointmentTime TIME         NOT NULL,
  Status          ENUM('Scheduled','Rescheduled','Cancelled','Completed') NOT NULL DEFAULT 'Scheduled',
  CONSTRAINT fk_appt_patient   FOREIGN KEY (PatientID)  REFERENCES patients(PatientID)  ON DELETE CASCADE,
  CONSTRAINT fk_appt_doctor    FOREIGN KEY (DoctorID)   REFERENCES doctors(DoctorID)    ON DELETE CASCADE,
  CONSTRAINT fk_appt_timeslot  FOREIGN KEY (TimeSlotID) REFERENCES timeslots(SlotID)    ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS prescriptions (
  PrescriptionID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  PatientID      INT UNSIGNED NOT NULL,
  DoctorID       INT UNSIGNED NOT NULL,
  Medication     VARCHAR(200) NOT NULL,
  Dosage         VARCHAR(100) NOT NULL,
  Notes          TEXT,
  DateIssued     DATE         NOT NULL,
  CONSTRAINT fk_rx_patient FOREIGN KEY (PatientID) REFERENCES patients(PatientID) ON DELETE CASCADE,
  CONSTRAINT fk_rx_doctor  FOREIGN KEY (DoctorID)  REFERENCES doctors(DoctorID)  ON DELETE CASCADE
);

-- ============================================================
-- INDEXES
-- ============================================================

CREATE INDEX idx_users_name          ON users(Name);
CREATE INDEX idx_doctors_specialty   ON doctors(Specialty);
CREATE INDEX idx_doctors_userid      ON doctors(UserID);
CREATE INDEX idx_patients_userid     ON patients(UserID);
CREATE INDEX idx_timeslots_doctor    ON timeslots(DoctorID, AvailabilityStatus, StartTime);
CREATE INDEX idx_appointments_date   ON appointments(AppointmentDate, AppointmentTime, Status);
CREATE INDEX idx_appointments_doctor ON appointments(DoctorID);
CREATE INDEX idx_appointments_patient ON appointments(PatientID);
CREATE INDEX idx_prescriptions_date  ON prescriptions(DateIssued);
CREATE INDEX idx_prescriptions_patient ON prescriptions(PatientID);

-- ============================================================
-- SAMPLE DATA
-- ============================================================

-- Passwords are bcrypt of 'password123' for all sample users
-- To regenerate: php -r "echo password_hash('password123', PASSWORD_BCRYPT);"

INSERT INTO users (Name, Email, Password, Role) VALUES
  ('Admin User',       'admin@medisched.local',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
  ('Dr. Sarah Chen',   'sarah.chen@med.local',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor'),
  ('Dr. James Okafor', 'james.okafor@med.local',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor'),
  ('Dr. Priya Sharma', 'priya.sharma@med.local',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor'),
  ('Alice Johnson',    'alice@patients.local',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient'),
  ('Bob Williams',     'bob@patients.local',      '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient'),
  ('Carol Martinez',   'carol@patients.local',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'patient');

INSERT INTO doctors (UserID, Specialty, ExperienceYears) VALUES
  (2, 'Cardiology',   12),
  (3, 'Neurology',     8),
  (4, 'Dermatology',   5);

INSERT INTO patients (UserID, DateOfBirth, ContactNumber, MedicalHistory) VALUES
  (5, '1985-03-14', '555-0101', 'Hypertension, managed with medication'),
  (6, '1992-07-22', '555-0102', 'No significant history'),
  (7, '1978-11-30', '555-0103', 'Type 2 diabetes, diet-controlled');

INSERT INTO timeslots (DoctorID, StartTime, EndTime, AvailabilityStatus) VALUES
  (1, '2026-06-10 09:00:00', '2026-06-10 09:30:00', 'available'),
  (1, '2026-06-10 09:30:00', '2026-06-10 10:00:00', 'booked'),
  (1, '2026-06-11 10:00:00', '2026-06-11 10:30:00', 'available'),
  (2, '2026-06-10 14:00:00', '2026-06-10 14:30:00', 'available'),
  (2, '2026-06-12 11:00:00', '2026-06-12 11:30:00', 'booked'),
  (3, '2026-06-13 09:00:00', '2026-06-13 09:30:00', 'available');

INSERT INTO appointments (PatientID, DoctorID, TimeSlotID, AppointmentDate, AppointmentTime, Status) VALUES
  (1, 1, 2, '2026-06-10', '09:30:00', 'Scheduled'),
  (2, 2, 5, '2026-06-12', '11:00:00', 'Completed'),
  (3, 1, NULL, '2026-05-20', '10:00:00', 'Completed'),
  (1, 3, NULL, '2026-04-15', '09:00:00', 'Cancelled'),
  (2, 1, NULL, '2026-03-05', '14:00:00', 'Completed');

INSERT INTO prescriptions (PatientID, DoctorID, Medication, Dosage, Notes, DateIssued) VALUES
  (1, 1, 'Lisinopril',   '10mg once daily',   'Take in the morning. Monitor blood pressure weekly.', '2026-05-20'),
  (2, 2, 'Sumatriptan',  '50mg as needed',    'Use at onset of migraine. Max 2 doses per 24h.',      '2026-06-12'),
  (3, 1, 'Metformin',    '500mg twice daily', 'Take with meals. Review HbA1c in 3 months.',          '2026-04-10'),
  (1, 3, 'Hydrocortisone cream', '1% apply twice daily', 'Apply thin layer to affected area.',       '2026-04-15'),
  (2, 1, 'Atorvastatin', '20mg once daily',   'Take at night. Avoid grapefruit juice.',              '2026-03-05');
