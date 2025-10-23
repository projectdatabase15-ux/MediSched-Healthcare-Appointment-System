
INSERT INTO users(full_name,email,passhash) VALUES
('Dr. Alice König','alice.koenig@example.com', REPEAT('a',64)),
('Dr. Bob Müller','bob.mueller@example.com', REPEAT('b',64)),
('Carla Schmidt','carla.schmidt@example.com', REPEAT('c',64)),
('Dieter Braun','dieter.braun@example.com', REPEAT('d',64));

INSERT INTO doctors(user_id, specialization) VALUES
(1,'Cardiology'),
(2,'Pediatrics');

INSERT INTO patients(user_id, dob) VALUES
(3,'1993-05-12'),
(4,'1988-11-02');

INSERT INTO timeslots(start_ts, end_ts) VALUES
('2025-10-24 09:00:00','2025-10-24 09:30:00'),
('2025-10-24 09:30:00','2025-10-24 10:00:00');
