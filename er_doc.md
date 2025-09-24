# Simplified ER Diagram - MediSched

## Entities
- **User**(UserID, Name, Email, Password, Role)
- **Doctor**(DoctorID, Specialty, ExperienceYears)
- **Patient**(PatientID, DateOfBirth, ContactNumber, MedicalHistory)
- **TimeSlot**(SlotID, DoctorID, StartTime, EndTime, AvailabilityStatus)
- **Appointment**(AppointmentID, PatientID, DoctorID, SlotID, AppointmentDate, AppointmentTime, Status, AdminID)
- **Prescription**(PrescriptionID, DoctorID, PatientID, AppointmentID, Medication, Dosage, Notes, DateIssued)

## ISA Hierarchies
- User → Patient, Doctor, Admin (identified by Role field)

## Relationships
- A **Patient** can book many **Appointments** (1:N).
- A **Doctor** is assigned to many **Appointments** (1:N).
- An **Admin** manages many **Appointments** (1:N).
- A **Doctor** has many **TimeSlots** (1:N).
- A **Doctor** generates many **Prescriptions** (1:N).
- Each **Appointment** occupies exactly one **TimeSlot** (1:1).