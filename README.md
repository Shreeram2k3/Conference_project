# Conference Management System

A web application built with Laravel, MySQL, Tailwind CSS, and Alpine.js for managing academic conferences.  
The system handles event creation, registrations, committee management, timelines, slot segregation, and PDF exports with clear separation of roles for Admin and Organizers.

---

## Core Functionalities

### Public Module
- View all National and International conferences.
- Register for events without logging in.
- Upload abstract files in Word format during registration.

### Admin Module
- Create, edit, and manage conferences.
- Add and update event timelines.
- Add and manage committee members for each event.
- Upload sample papers for events.
- View all registrations in a compact table with a detailed information view.
- Segregate registrants into slots based on a chosen batch size (example: 5 users per slot).
- Filter registrants by Online/Offline mode before exporting.
- Export slot-wise registrant data as a downloadable PDF.

### Organizer Module
- Dashboard limited to events assigned to the organizer.
- View and manage only the relevant event details and registrations.

---

## Technology Used
- Laravel (Backend)
- MySQL (Database)
- Tailwind CSS + Alpine.js (Frontend)
- DOMPDF / FPDI (PDF generation)
- Blade Templating (Views)

---

## Setup Instructions

```bash
git clone https://github.com/Shreeram2k3/Conference_project
cd conference-management-system

Install backend and frontend dependencies:

composer install
npm install
npm run build


Copy the environment file and generate the application key:

cp .env.example .env
php artisan key:generate


Configure the .env file with your database credentials:

DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password


Run the database migrations:

php artisan migrate


(Optional) Seed the database:

php artisan db:seed


Start the development server:

php artisan serve


The application will be available at:

http://localhost:8000
