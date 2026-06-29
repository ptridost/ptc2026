# Overview
## Philippine Textile Congress (PTC) Website
Custom PHP + MySQL web application for the Philippine Textile Congress. The project includes public event pages (year-based landing pages, programme, speakers, announcements) and a participant/admin system for registration, attendance, feedback, analytics, and certificate generation.

# Local Development Setup (XAMPP)

### Requirements
- Apache + PHP (XAMPP)
- MySQL / MariaDB

### Project location
Place/clone the project at:
- `C:\xampp\htdocs\PTC`

### Homepage
The root `.htaccess` currently sets:
- `DirectoryIndex index2026.php`

So your local homepage should be:
- `http://localhost/PTC/`

### Database
Main DB connections are defined in:
- `dbcon.php` (primary app DB)
- `dbConfig.php` (used by some AJAX/calendar features)
- `dbcon_world.php` (used for country/region/city dropdown data)

Database names referenced in code include:
- `sampledb` (main app data)
- `world` (country/region/city reference data)

A sample SQL file exists:
- `sampledb.sql`  

## Key URLs

### Public
- Home: `/` (served by `index2025.php` via `.htaccess`)
- Registration: `/registration.php`
- Login: `/Loginn.php`

### Participant
- Dashboard: `/User_Dashboard/dashboard.php`

### Admin
- Admin dashboard: `/admin/admindashboard.php`

## Notes
- Project is made in pure PHP.
- The codebase contains multiple database connection files; use `dbcon.php` as the main app connection.

# Main Modules

### Public site
- Year-based landing pages, currently having the 2026 PTC/GTC teaser (e.g. `index2025.php`)
- Programme pages (e.g. `programme2023.php`, `programme2024.php`, `programme2025.php`)
- Speaker pages (e.g. `SpeakersPage2024.php`, `SpeakersPage2025.php`)

### Participant system

- Registration: `registration.php`
- Login: `Loginn.php` (posts to `process.php`)
- Participant dashboard: `User_Dashboard/dashboard.php`
  - Attendance and feedback forms
  - Certificate generation buttons (eligibility-based)

### Admin system
- Admin dashboard: `admin/admindashboard.php`
- Participant management, attendance tracking (incl. QR features), analytics and reports
- Certificate templates and PDF generation under `admin/certificate/`

### Certificate Generation
Certificates are generated as PDFs using Dompdf:
- Generator: `admin/certificate/generate_certificate.php`
- Year-based assets: `admin/certificate/assets/<year>/`
  - Background: `certificate<year>bg.jpg`
  - Other assets: `ptcbanner.png`, `ptclogowithrc.png`, `esigdirector.png`

### Test/preview certificate layout
You can preview the certificate format locally using `TEST_MODE=true` (bypasses attendance eligibility checks):

- Local (XAMPP):
  - `http://localhost/PTC/admin/certificate/generate_certificate.php?id=<participant_id>&year=2025&TEST_MODE=true`

Replace `<participant_id>` with an existing `participant.id` in your database.
