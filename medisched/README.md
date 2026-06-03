# MediSched – Search Component (Assignment 6)

This package provides a LAMP-ready search component for **MediSched – Healthcare Appointment System**.

It implements **three queries** (team size N=3) with:
- a **search form**,
- a **results list**, and
- a **detail page** for a single record.

## Queries implemented
1) **Doctor Search**
   - Inputs: specialty (required), name (optional), min experience years (optional)
   - Results: doctor list with name, specialty, experience
   - Detail: doctor profile, next available timeslots, recent appointments, recent prescriptions

2) **Appointment Search**
   - Inputs: date range (required), doctor name (optional), status (optional)
   - Results: appointments with patient & doctor names, date/time, status
   - Detail: full appointment info with clickable doctor link and related prescriptions

3) **Prescription Search**
   - Inputs: patient name (optional), medication text (optional), date range (optional)
   - Results: prescriptions with patient, doctor, date, medication
   - Detail: full prescription record with clickable doctor link

## Folder structure
```
medisched/
  public/
    index.php
    login.php
    logout.php
    admin.php
    create_admin.php   ← run once, then delete
    404.php
    search/
      doctor_search.php
      doctor_results.php
      doctor_detail.php
      appointment_search.php
      appointment_results.php
      appointment_detail.php
      prescription_search.php
      prescription_results.php
      prescription_detail.php
  includes/
    config.php
    helpers.php
    db.php
    header.php
    footer.php
  assets/
    styles.css
  sql/
    schema.sql         ← executable CREATE TABLE + sample data + indexes
```

## Quick start (Ubuntu/Debian LAMP)
```bash
sudo apt update
sudo apt install -y apache2 mariadb-server php php-mysql
sudo systemctl enable --now apache2 mariadb

# Secure MariaDB (follow prompts)
sudo mysql_secure_installation

# Create database and user
sudo mysql -uroot -p
# In the MariaDB shell:
CREATE DATABASE medisched CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'medisched_user'@'localhost' IDENTIFIED BY 'change_me_strong_pwd';
GRANT ALL PRIVILEGES ON medisched.* TO 'medisched_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Load the schema (creates all tables, indexes, and sample data)
mysql -u medisched_user -p medisched < medisched/sql/schema.sql

# Deploy files
sudo mkdir -p /var/www/html/medisched
sudo cp -r medisched/* /var/www/html/medisched/
sudo chown -R www-data:www-data /var/www/html/medisched
sudo find /var/www/html/medisched -type d -exec chmod 755 {} \;
sudo find /var/www/html/medisched -type f -exec chmod 644 {} \;

# Configure credentials
sudo nano /var/www/html/medisched/includes/config.php
# set DB_HOST, DB_NAME, DB_USER, DB_PASS, APP_BASE

# Create admin account (visit once in browser, then delete the file)
http://localhost/medisched/public/create_admin.php

# Open the app
http://localhost/medisched/public/
```

## Database schema
All tables, foreign keys, indexes, and sample data are in `sql/schema.sql`. Run it once to get a working database. Sample credentials for all seeded users: `password123`.

| Table | Key columns |
|---|---|
| `users` | UserID, Name, Email, Password (bcrypt), Role (admin/doctor/patient) |
| `doctors` | DoctorID, UserID→users, Specialty, ExperienceYears |
| `patients` | PatientID, UserID→users, DateOfBirth, ContactNumber, MedicalHistory |
| `timeslots` | SlotID, DoctorID→doctors, StartTime, EndTime, AvailabilityStatus |
| `appointments` | AppointmentID, PatientID, DoctorID, TimeSlotID, AppointmentDate, AppointmentTime, Status |
| `prescriptions` | PrescriptionID, PatientID, DoctorID, Medication, Dosage, Notes, DateIssued |

## Security & notes
- All SQL uses PDO prepared statements — no raw interpolation.
- Passwords stored as bcrypt hashes.
- Session is regenerated on login; unset and destroyed on logout.
- Admin panel enforces `Role = 'admin'` check, not just session presence.
- Basic server-side validation and error messaging on all forms.
- Pagination (10 per page) on all result pages.
- Minimal CSS; no external dependencies.
- For production: disable `display_errors` in `php.ini` and use HTTPS.
