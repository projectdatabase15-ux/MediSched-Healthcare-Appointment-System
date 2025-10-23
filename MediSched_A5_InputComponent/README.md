# MediSched — Assignment 5 (Input Component)
Aligned with Assignment 4 corporate design (warm beige #fdf6e3, brown accent #8b4513, Georgia/Arial).

## What you get
- PHP + MySQL input pages for 6 entity/relationship sets:
  - Users, Doctors, Patients, TimeSlots, Appointments (Patient–Doctor–TimeSlot), Prescriptions (per Appointment)
- Maintenance page with links to all inputs and Imprint link in navbar (1‑click from landing page).

## Setup
1. Create DB and run SQL:
   ```sql
   CREATE DATABASE medisched CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   USE medisched;
   SOURCE sql/schema.sql;
   SOURCE sql/sample_data.sql;
   ```
2. Deploy `public/` (and root `index.html` + `imprint.html`) to your web directory, e.g. `~/public_html/`:
   - `index.html` → landing page
   - `imprint.html` → imprint (linked in navbar)
   - `/public/*` → PHP app
3. Update DB credentials in `public/config.php` or via env (`MEDISCHED_DB_HOST`, `MEDISCHED_DB_NAME`, `MEDISCHED_DB_USER`, `MEDISCHED_DB_PASS`).

## Notes
- IDs are generated server‑side (`AUTO_INCREMENT`).
- Referential integrity preserved using `<select>` which read the referenced tables and display meaningful labels.
- Double‑booking is prevented (`UNIQUE (doctor_id, timeslot_id)` + check in handler).
- Styling follows A4: card‑based layout, Georgia/Arial, warm palette, no inline styles.
