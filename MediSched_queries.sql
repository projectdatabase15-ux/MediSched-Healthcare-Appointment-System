-- MediSched Assignment 3: SELECT queries (MySQL 8.0+)
-- Assumes schema from MediSched_schema.sql and seed from MediSched_seed.sql are loaded.

SET @from_date := DATE_SUB(CURDATE(), INTERVAL 30 DAY);
SET @to_date   := DATE_ADD(CURDATE(), INTERVAL 1 DAY);
SET @doctor_email  := 'smith@example.com';
SET @patient_email := 'alice@example.com';

SET @doctor_id := (SELECT u.user_id FROM User u WHERE u.email = @doctor_email);
SET @patient_id := (SELECT u.user_id FROM User u WHERE u.email = @patient_email);

-- Q1) Upcoming appointments for a given patient
SELECT a.appointment_id, uD.name AS doctor_name, t.start_time, t.end_time, s.status_code
FROM Appointment a
JOIN Patient p  ON p.user_id = a.patient_id
JOIN Doctor d   ON d.user_id = a.doctor_id
JOIN User uD    ON uD.user_id = d.user_id
JOIN TimeSlot t ON t.slot_id = a.timeslot_id
JOIN Status s   ON s.status_code = a.status_code
WHERE a.patient_id = @patient_id
  AND t.start_time >= NOW()
ORDER BY t.start_time;

-- Q2) Doctor's daily schedule with counts per status
SELECT uD.name AS doctor_name, DATE(t.start_time) AS appt_date, s.status_code, COUNT(*) AS appt_count
FROM Appointment a
JOIN Doctor d   ON d.user_id = a.doctor_id
JOIN User uD    ON uD.user_id = d.user_id
JOIN TimeSlot t ON t.slot_id = a.timeslot_id
JOIN Status s   ON s.status_code = a.status_code
WHERE a.doctor_id = @doctor_id AND DATE(t.start_time) = CURDATE()
GROUP BY uD.name, DATE(t.start_time), s.status_code
ORDER BY s.status_code;

-- Q3) Top doctors by number of appointments in last 30 days
SELECT uD.name AS doctor_name, COUNT(*) AS appts_30d
FROM Appointment a
JOIN Doctor d ON d.user_id = a.doctor_id
JOIN User uD  ON uD.user_id = d.user_id
JOIN TimeSlot t ON t.slot_id = a.timeslot_id
WHERE t.start_time >= @from_date AND t.start_time < @to_date
GROUP BY uD.name
ORDER BY appts_30d DESC, uD.name ASC
LIMIT 5;

-- Q4) Patients who have had 2+ appointments with the same doctor
SELECT uP.name AS patient_name, uD.name AS doctor_name, COUNT(*) AS num_appointments
FROM Appointment a
JOIN Patient p ON p.user_id = a.patient_id
JOIN User uP   ON uP.user_id = p.user_id
JOIN Doctor d  ON d.user_id = a.doctor_id
JOIN User uD   ON uD.user_id = d.user_id
GROUP BY uP.name, uD.name
HAVING COUNT(*) >= 2
ORDER BY num_appointments DESC, uP.name;

-- Q5) Next available time slots for a doctor
SELECT t.slot_id, t.start_time, t.end_time, t.availability_status
FROM TimeSlot t
WHERE t.doctor_id = @doctor_id AND t.start_time >= NOW() AND t.availability_status = 'Available'
ORDER BY t.start_time
LIMIT 10;

-- Q6) Prescription history for a patient with prescribing doctor and appointment timestamp
SELECT pr.prescription_id, pr.medication, pr.dosage, pr.date_issued, uD.name AS doctor_name, t.start_time AS appointment_time
FROM Prescription pr
JOIN Patient p  ON p.user_id = pr.patient_id
JOIN Doctor d   ON d.user_id = pr.doctor_id
JOIN User uD    ON uD.user_id = d.user_id
LEFT JOIN Appointment a ON a.appointment_id = pr.appointment_id
LEFT JOIN TimeSlot t    ON t.slot_id = a.timeslot_id
WHERE pr.patient_id = @patient_id
ORDER BY pr.date_issued DESC, pr.prescription_id DESC;

-- Q7) Utilization rate per doctor
SELECT uD.name AS doctor_name,
       SUM(CASE WHEN t.availability_status = 'Occupied' THEN 1 ELSE 0 END) AS occupied_slots,
       COUNT(*) AS total_slots,
       ROUND(100.0 * SUM(CASE WHEN t.availability_status = 'Occupied' THEN 1 ELSE 0 END)/COUNT(*), 2) AS utilization_pct
FROM TimeSlot t
JOIN Doctor d ON d.user_id = t.doctor_id
JOIN User uD  ON uD.user_id = d.user_id
WHERE t.start_time >= @from_date AND t.start_time < @to_date
GROUP BY uD.name
ORDER BY utilization_pct DESC, uD.name ASC;

-- Q8) Cancellation rate per patient
SELECT uP.name AS patient_name,
       SUM(CASE WHEN a.status_code = 'Cancelled' THEN 1 ELSE 0 END) AS cancelled,
       COUNT(*) AS total_appts,
       ROUND(100.0 * SUM(CASE WHEN a.status_code = 'Cancelled' THEN 1 ELSE 0 END)/COUNT(*), 2) AS cancel_rate_pct
FROM Appointment a
JOIN Patient p ON p.user_id = a.patient_id
JOIN User uP   ON uP.user_id = p.user_id
GROUP BY uP.name
ORDER BY cancel_rate_pct DESC, total_appts DESC;

-- Q9) Follow-up chains (follow-up + parent details)
SELECT a2.appointment_id AS followup_id, uP.name AS patient_name, uD2.name AS followup_doctor, t2.start_time AS followup_time,
       fua.parent_appointment_id AS parent_id, uD1.name AS parent_doctor, t1.start_time AS parent_time
FROM FollowUpAppointment fua
JOIN Appointment a2 ON a2.appointment_id = fua.appointment_id
JOIN Appointment a1 ON a1.appointment_id = fua.parent_appointment_id
JOIN Patient p ON p.user_id = a2.patient_id
JOIN User uP   ON uP.user_id = p.user_id
JOIN Doctor d1 ON d1.user_id = a1.doctor_id
JOIN User uD1  ON uD1.user_id = d1.user_id
JOIN Doctor d2 ON d2.user_id = a2.doctor_id
JOIN User uD2  ON uD2.user_id = d2.user_id
JOIN TimeSlot t1 ON t1.slot_id = a1.timeslot_id
JOIN TimeSlot t2 ON t2.slot_id = a2.timeslot_id
ORDER BY uP.name, parent_time, followup_time;
