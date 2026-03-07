<div align="center">

# 🏛️ UP Management System

**A multi-portal web application for managing interns, startup incubatees, bookings, and administrative operations.**

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-7-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vite.dev)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Portals at a Glance](#-portals-at-a-glance)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Usage](#-usage)
- [Project Structure](#-project-structure)

---

## 🔍 Overview

The **UP Management System** is an all-in-one platform built to streamline operations across four distinct user groups:

| | What It Manages |
|---|---|
| 🎓 **Interns** | Attendance tracking, task assignments, document storage, and event visibility |
| 🚀 **Startup Incubatees** | MOA workflows, document submissions, payments, and room issue reports |
| 📅 **Facility Bookings** | Room/space reservation requests with admin approval |
| 👥 **Team Leaders** | Intern group oversight, task management, and periodic report submission |

---

## 🚪 Portals at a Glance

| Portal | URL | Access Method | Description |
|--------|-----|---------------|-------------|
| 🏠 **Home** | `/` | Public | Landing page |
| 🎓 **Intern** | `/intern` | Registration / Access Code | Time tracking, tasks & documents |
| 🚀 **Startup** | `/startup` | Email + Password / Code | Submissions, MOA, payments |
| 👥 **Team Leader** | `/team-leader` | Admin credentials (role) | Intern & task oversight |
| 🔧 **Admin** | `/admin` | Admin credentials | Full system management |

> 💡 **Maintenance Mode:** The Intern and Startup portals can be temporarily locked via Admin settings, displaying a maintenance page to the public while keeping the Admin panel accessible.

---

## ✨ Features

<details>
<summary><b>🔧 Admin Portal</b> — click to expand</summary>

<br>

**Dashboard & Analytics**
- 📊 Charts, recent activity feed, and system-wide statistics
- 📤 Data exports for interns, attendance, tasks, and bookings

**Intern & School Management**
- ✅ Intern approval/rejection with batch operations per school
- 🏫 School/institution management with intern quota controls
- 📝 Task assignment and profile editing

**Startup Incubatee Management**
- 🏢 Startup account creation, status toggling, and password resets
- 📄 Document, MOA request, payment, and room issue review
- 📆 Payment schedule and MOA expiry date management
- 🔔 Automated reminders for upcoming payment and MOA deadlines
- 📈 Project progress review with admin responses

**Facility & Event Management**
- 📅 Booking approval, rejection, and email notifications
- 🚫 Blocked dates management
- 🗓️ Event creation and management

**Team Leader Management**
- 👤 Create, update, and set granular module permissions
- ⬆️ Promote interns to Team Leader roles
- 📋 Review submitted Team Leader reports

**System**
- 🗂️ Digital records: folder & file CRUD with per-intern access controls
- ⚙️ System settings and old data cleanup tools
- 🔑 CSRF token refresh to prevent session expiry during active use

</details>

<details>
<summary><b>🎓 Intern Portal</b> — click to expand</summary>

<br>

- 📝 Self-registration with school selection
- 🔑 Code-based access (no password required by default)
- 🕐 Time-in / time-out with real-time attendance status
- ✅ Task management: view assigned tasks, update progress, complete with checklists
- 📁 Document management: upload files and organize into folders
- 🗓️ Event calendar view
- 🖼️ Profile and profile picture management

</details>

<details>
<summary><b>🚀 Startup Portal</b> — click to expand</summary>

<br>

- 🔐 Login with password or one-time verification code
- 📄 Document submission and history
- 📜 MOA request, template download, and signed MOA upload
- 💳 Payment submission
- 🔧 Room issue reporting
- 📈 Project progress updates
- 🔍 Track submissions by reference code
- 🔔 Notifications with read/unread state
- 🧾 Billing and payment history
- 📋 Activity log
- 👤 Profile and password management

</details>

<details>
<summary><b>👥 Team Leader Portal</b> — click to expand</summary>

<br>

- 📊 Live dashboard: intern stats, task overview, attendance summary
- 👀 Intern management (view-only)
- ✏️ Full task CRUD for assigned intern group (create, assign, track, delete)
- 🕐 Attendance viewing
- 📝 Weekly/periodic report creation and submission
- 🚫 Blocked dates management
- 🔄 Switch to Intern Portal view

</details>

---

## 🛠️ Tech Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| **Backend** | PHP + Laravel | 8.2+ / 12.x |
| **Frontend** | Tailwind CSS + Vite | 4.x / 7.x |
| **HTTP Client** | Axios | 1.x |
| **Database** | MySQL via Eloquent ORM | — |
| **Queue** | Laravel Queue | Built-in |
| **Testing** | PHPUnit | 11.x |
| **Dev Tools** | Laravel Pail, Pint, Sail | Latest |

---

## ✅ Prerequisites

Before getting started, ensure you have the following installed:

- ![PHP](https://img.shields.io/badge/-PHP%208.2%2B-777BB4?logo=php&logoColor=white&style=flat-square)
- ![Composer](https://img.shields.io/badge/-Composer-885630?logo=composer&logoColor=white&style=flat-square)
- ![Node.js](https://img.shields.io/badge/-Node.js%2018%2B-339933?logo=node.js&logoColor=white&style=flat-square)
- ![MySQL](https://img.shields.io/badge/-MySQL-4479A1?logo=mysql&logoColor=white&style=flat-square)

---

## 🚀 Installation

### ⚡ Quick Setup (Recommended)

```bash
git clone <repository-url>
cd up-management-system
composer run setup
```

This single command handles everything:

```
✔ composer install          — installs PHP dependencies
✔ .env setup                — copies .env.example to .env
✔ php artisan key:generate  — generates app encryption key
✔ php artisan migrate       — runs all database migrations
✔ npm install               — installs Node dependencies
✔ npm run build             — compiles frontend assets
```

### 🔧 Manual Setup

```bash
# 1. Install dependencies
composer install
npm install

# 2. Configure environment
cp .env.example .env
php artisan key:generate

# 3. Configure your database credentials in .env, then migrate
php artisan migrate

# 4. Seed the default admin account
php artisan db:seed --class=AdminSeeder

# 5. Create the public storage symlink
php artisan storage:link

# 6. Build frontend assets
npm run build
```

> ⚠️ **Important:** Make sure to update `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in your `.env` file before running migrations.

---

## 💻 Usage

### Development Server

Starts all services simultaneously — PHP server, queue worker, log viewer, and Vite dev server:

```bash
composer run dev
```

This runs the following processes concurrently:

| Process | Description |
|---------|-------------|
| `php artisan serve` | PHP development server |
| `php artisan queue:listen` | Background job processor |
| `php artisan pail` | Real-time log viewer |
| `npm run dev` | Vite HMR asset server |

### Running Tests

```bash
composer run test
```

---

## 📁 Project Structure

```
up-management-system/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/        # One controller per portal & feature
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── InternController.php
│   │   │   ├── StartupDashboardController.php
│   │   │   ├── TeamLeaderController.php
│   │   │   └── ...
│   │   └── Middleware/         # Auth guards: admin, startup.auth, team.leader, maintenance
│   ├── Models/                 # Eloquent models
│   │   ├── Intern.php
│   │   ├── Startup.php
│   │   ├── Attendance.php
│   │   ├── Task.php
│   │   ├── Booking.php
│   │   └── ...
│   └── Providers/
│
├── database/
│   ├── migrations/             # 40+ versioned schema migrations
│   ├── factories/
│   └── seeders/                # AdminSeeder
│
├── resources/
│   ├── views/                  # Blade templates (per-portal)
│   ├── css/
│   └── js/
│
├── routes/
│   └── web.php                 # All 100+ named routes, grouped by portal
│
├── public/
│   └── images/
│
└── storage/
    └── app/public/             # User-uploaded files (documents, profile photos)
```
