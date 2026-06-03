# MediSched – Healthcare Appointment System

A university database module project. The system models a healthcare platform where doctors, patients, and administrators manage appointments, schedules, and prescriptions.

## What this system does

- Patients can search for doctors, book/reschedule/cancel appointments, and view prescriptions
- Doctors manage availability slots and issue prescriptions after consultations
- Administrators manage users and oversee the system
- The schema enforces that no two patients can book the same slot with the same doctor

## Repository structure

| Folder / File | Description |
| `medisched/` | **Assignment 6 — Main search component.** Full LAMP PHP app with three search flows, admin panel, and complete database schema. This is the primary deliverable. |
| `MediSched_A5_InputComponent/` | Assignment 5 — Input forms for creating doctors, patients, appointments, prescriptions, and timeslots |
| `public_web/` | Assignment 4 — Static HTML website for the MediSched concept |
| `MediSched.sql` | Original database schema (superseded by `medisched/sql/schema.sql`) |
| `MediSched_queries.sql` | Query development notes |
| `er_doc.md` | Entity-relationship documentation |
| `Mapping Approach.pdf` | Schema mapping document |

## Getting started

The latest and most complete code is in the **`medisched/`** folder. It includes a full setup guide in its own README.
