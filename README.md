# UP Management System

A multi-portal web application built with Laravel 12 for managing interns, startup incubatees, bookings, and administrative operations.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Portals](#portals)
- [Project Structure](#project-structure)

---

## Overview

The UP Management System is an all-in-one platform designed to streamline the management of:

- **Interns** from partnered schools — tracking attendance, tasks, and documents
- **Startup incubatees** — handling MOA workflows, document submissions, payments, and room issue reports
- **Facility bookings** — managing room/space reservation requests
- **Team Leaders** — overseeing intern groups and submitting periodic reports

---

## Features

### Admin Portal
- Dashboard with charts, recent activity, and system statistics
- Intern management: approval, profile editing, task assignment
- School/institution management with intern quotas and batch approval
- Startup account management: create accounts, toggle status, reset passwords
- Startup submissions review: documents, MOA requests, payments, room issues
- Incubatee schedule management: payment schedules, MOA expiry dates, automated reminders
- Project progress review and responses
- Booking management: approve, reject, email notifications
- Blocked dates management
- Digital records management: folder & file CRUD with access controls
- Event creation and management
- Team Leader management: create, update, permissions, promote interns
- Team Leader report review
- Data exports: interns, attendance, tasks, bookings
- System settings and data cleanup tools

### Intern Portal
- Self-registration with school selection
- Code-based access (no password required by default)
- Time-in / time-out attendance tracking with real-time status
- Task management: view, update progress, complete tasks with checklists
- Document management: upload files, organize into folders
- Event calendar view
- Profile and profile picture management

### Startup Portal
- Account login with password or one-time verify code
- Document submission and tracking
- MOA request, template download, and signed document upload
- Payment submission
- Room issue reporting
- Project progress reporting
- Submission history and tracking by reference code
- Notifications (read/unread)
- Billing and payment history
- Activity log
- Profile and password management

### Team Leader Portal
- Dashboard with live intern, task, and attendance stats
- Intern oversight (view-only)
- Full task CRUD for assigned intern group
- Attendance viewing
- Weekly/periodic report creation and submission
- Blocked dates management
- Switch to Intern Portal view

---

## Tech Stack

| Layer        | Technology                   |
|--------------|------------------------------|
| Backend      | PHP 8.2+, Laravel 12         |
| Frontend     | Tailwind CSS 4, Vite 7       |
| HTTP Client  | Axios                        |
| Database     | MySQL (via Laravel Eloquent) |
| Queue        | Laravel Queue                |
| Testing      | PHPUnit 11                   |

---

## Prerequisites

- PHP >= 8.2
- Composer
- Node.js >= 18 & npm
- MySQL (or compatible database)

---

## Installation

### Quick Setup

```bash
git clone <repository-url>
cd up-management-system
composer run setup
```

This single command will:
1. Install PHP dependencies (`composer install`)
2. Copy `.env.example` to `.env`
3. Generate the application key
4. Run all database migrations
5. Install Node dependencies (`npm install`)
6. Build frontend assets (`npm run build`)

### Manual Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Set up the database in .env, then run migrations
php artisan migrate

# 4. Seed the admin account
php artisan db:seed --class=AdminSeeder

# 5. Set up storage symlink
php artisan storage:link

# 6. Build frontend assets
npm run build
```

---

## Usage

### Development Server

Run all services concurrently (PHP server, queue worker, log viewer, and Vite dev server):

```bash
composer run dev
```

### Run Tests

```bash
composer run test
```

---

## Portals

| Portal      | URL            | Access Method              |
|-------------|----------------|----------------------------|
| Home        | `/`            | Public                     |
| Intern      | `/intern`      | Registration / Access Code |
| Startup     | `/startup`     | Email + Password / Code    |
| Team Leader | `/team-leader` | Admin credentials (role)   |
| Admin       | `/admin`       | Admin credentials          |

> **Note:** The Intern and Startup portals support a **maintenance mode** that can be toggled from the Admin settings to temporarily restrict public access.

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/       # Route controllers for all portals
│   └── Middleware/        # Admin, Team Leader, Startup auth & maintenance guards
├── Models/                # Eloquent models (Intern, Startup, Attendance, Task, etc.)
└── Providers/

database/
├── migrations/            # All database schema migrations
├── factories/
└── seeders/               # AdminSeeder and others

resources/
├── views/                 # Blade templates for all portals
├── css/
└── js/

routes/
└── web.php                # All application routes

public/
└── images/                # Public static images
```
