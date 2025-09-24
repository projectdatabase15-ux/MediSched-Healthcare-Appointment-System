-- MediSched – Healthcare Appointment System


DROP TABLE IF EXISTS Prescription;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS TimeSlot;
DROP TABLE IF EXISTS Patient;
DROP TABLE IF EXISTS Doctor;
DROP TABLE IF EXISTS User;

CREATE TABLE User (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Role ENUM('Patient', 'Doctor', 'Admin') NOT NULL
);

CREATE TABLE Doctor (
    DoctorID INT PRIMARY KEY,
    Specialty VARCHAR(100) NOT NULL,
    ExperienceYears INT,
    FOREIGN KEY (DoctorID) REFERENCES User(UserID)
);

CREATE TABLE Patient (
    PatientID INT PRIMARY KEY,
    DateOfBirth DATE NOT NULL,
    ContactNumber VARCHAR(15),
    MedicalHistory TEXT,
    FOREIGN KEY (PatientID) REFERENCES User(UserID)
);

CREATE TABLE TimeSlot (
    SlotID INT AUTO_INCREMENT PRIMARY KEY,
    DoctorID INT NOT NULL,
    StartTime DATETIME NOT NULL,
    EndTime DATETIME NOT NULL,
    AvailabilityStatus ENUM('Available', 'Booked') NOT NULL,
    FOREIGN KEY (DoctorID) REFERENCES Doctor(DoctorID)
);

CREATE TABLE Appointment (
    AppointmentID INT AUTO_INCREMENT PRIMARY KEY,
    PatientID INT NOT NULL,
    DoctorID INT NOT NULL,
    SlotID INT NOT NULL UNIQUE,
    AppointmentDate DATE NOT NULL,
    AppointmentTime TIME NOT NULL,
    Status ENUM('Scheduled', 'Rescheduled', 'Cancelled', 'Completed') NOT NULL,
    AdminID INT NOT NULL,
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID),
    FOREIGN KEY (DoctorID) REFERENCES Doctor(DoctorID),
    FOREIGN KEY (SlotID) REFERENCES TimeSlot(SlotID),
    FOREIGN KEY (AdminID) REFERENCES User(UserID)
);

CREATE TABLE Prescription (
    PrescriptionID INT AUTO_INCREMENT PRIMARY KEY,
    DoctorID INT NOT NULL,
    PatientID INT NOT NULL,
    AppointmentID INT NOT NULL,
    Medication VARCHAR(255) NOT NULL,
    Dosage VARCHAR(100) NOT NULL,
    Notes TEXT,
    DateIssued DATE NOT NULL,
    FOREIGN KEY (DoctorID) REFERENCES Doctor(DoctorID),
    FOREIGN KEY (PatientID) REFERENCES Patient(PatientID),
    FOREIGN KEY (AppointmentID) REFERENCES Appointment(AppointmentID)
);