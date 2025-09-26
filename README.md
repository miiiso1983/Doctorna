## Tabeebna - PHP Doctor Appointment Booking System

### Overview
Tabeebna is a PHP 8 MVC web application for booking doctor appointments with three dashboards: Super Admin, Doctor, and Patient. It uses Bootstrap 5 and MySQL.

### Requirements
- PHP 8.1+
- MySQL 5.7+/MariaDB 10.3+
- Apache or Nginx (Apache + .htaccess included)

### Local Setup (XAMPP/Laragon)
1. Clone or copy this folder into your web root (e.g., htdocs/doctorna or Laragon/www/doctorna)
2. Create a MySQL database named `doctorna`
3. Import database/schema.sql then database/seed.sql
4. Update config/config.php with your DB credentials
5. Point your web server document root to the `public/` directory
6. Visit http://localhost/doctorna (or the domain you configured)

Admin credentials after seed:
- Email: admin@doctorna.local
- Password: admin123

### Folder Structure
- public/ index.php front controller, .htaccess
- src/Core MVC core (Router, Request, Response, DB, Controller, helpers)
- src/Controllers Web and API controllers
- routes/ web.php + api.php route definitions
- views/ Blade-like simple PHP templates
- database/ schema.sql and seed.sql
- config/ app configuration

### Features Implemented (MVP)
- Auth: register/login/logout with roles (super_admin, doctor, patient)
- Dashboards stubs for all roles
- API endpoints: GET /api/specializations, GET /api/doctors/nearby
- Basic location search (Haversine SQL)

### Next Steps (to implement)
- CRUD for doctors/patients by admin
- Appointment booking, acceptance/rejection flows
- Patient profile: symptoms entry, medical history
- Doctor profile management: working hours, specialization
- AI specialization recommendation (rule-based now, AI-ready API later)
- Google Maps or Leaflet integration on patient dashboard
- Role-based permissions and admin reports
- RESTful API resources for mobile apps
- Email/WhatsApp notification hooks

### API (early draft)
- GET /api/specializations -> list
- GET /api/doctors/nearby?lat=..&lng=..&radius_km=25 -> nearby doctors

### Testing
- You can add PHPUnit and feature tests. For now, manually verify:
  - Register as patient/doctor and login
  - Access dashboards per role

### Notes
- Ensure Apache rewrite is enabled for .htaccess
- For Nginx, route all requests to public/index.php

# Doctorna
