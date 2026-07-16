# 🚨 Incident Management System (IMS) PROJECT DATE: May 2025

A web-based platform designed to bridge the gap between citizens and emergency/municipal services. The system allows citizens to report local incidents (such as traffic accidents, fire breakouts, security concerns, or municipal maintenance issues) and routes them directly to the appropriate authorities (Police, Fire Brigade, Municipal, Traffic Police) for rapid action, verification, and resolution.

---

<img width="1913" height="987" alt="image" src="https://github.com/user-attachments/assets/60783ff6-65d1-43e4-8b6f-350a039c26b1" />


## 🌟 Core Features

### 👤 Citizen Portal
* **Registration & Login**: Secure registration using CNIC, name, and contact details.
* **Report an Incident**: Report issues with details such as type (Fire, Traffic, Police, Municipal), description, date, time, and precise area location.
* **My Reports**: A dashboard tracking all personal reports and their real-time resolution status.
* **Area Risk Search**: Search a specific area to see active/historic incidents and discover local safety/risk levels (Low, Medium, High).

### 🚒 Authority Portal
* **Department-Specific View**: Role-based access for different departments (Police, Fire Brigade, Municipal, Traffic Police). Authorities only view incidents routed to their department.
* **Incident Lifecycle Management**:
  * **Unverified**: Newly reported incidents awaiting initial inspection.
  * **Unresponded**: Verified incidents that need immediate assignment/response.
  * **Responded**: Incidents currently in progress or resolved.
* **Official Response Submissions**: Submit progress reports, solutions, and mark incidents as "In Progress" or "Resolved".
* **Approval Process**: Newly registered authority accounts require manual review and activation by an Admin before they can access the dashboards.

### 🔑 Admin Portal
* **Admin Dashboard**: Accessible with seed credentials.
* **Authority Approvals**: Review pending registrations from various departments and approve/reject them to maintain system security.

---

## 🛠️ Tech Stack
* **Frontend**: HTML5, Vanilla CSS3 (Custom responsive layouts, Montserrat & Playfair Google fonts, FontAwesome icons)
* **Backend**: Plain PHP (No external framework dependencies)
* **Database**: MySQL / MariaDB (via `mysqli` extension)

---

## 📁 Repository Structure

```
├── .gitignore                   # Ignores local database binaries/logs
├── README.md                    # Project documentation
├── RUNNING.md                   # Quick start configuration guide
├── db.php                       # Database connection configuration (127.0.0.1:3307)
├── init-db.php                  # Database schema installer script
├── incident_db.sql              # Database export containing tables & relations
├── setup-and-run.bat            # Auto-setup and run script (MySQL + PHP server)
├── start.bat                    # Script to start the PHP server if MySQL is already running
├── php.ini                      # Local PHP server configuration overrides
│
├── index.php                    # Public landing page
├── login.php / logout.php       # Common authentication gateway
├── reg.php                      # Multi-role registration router
├── citizenreg.php               # Citizen details registration form
├── authorityreg.php             # Authority details registration form
│
├── citizen_dashboard.php        # Citizen main dashboard
├── citizen_my_reports.php       # View citizen's own submitted reports
├── citizen_search_area.php      # Search area and check risk level
├── incidentreport.php           # File incident reports
│
├── authority_dashboard.php      # Base dashboard for department workers
├── unverified.php               # Queue for unverified incidents
├── unresponded.php              # Queue for unresponded incidents
├── responded.php                # Queue for ongoing/resolved incidents
├── response.php                 # Details & action forms for responding to incidents
│
├── admin_login.php              # Secure portal for admin
├── admin.php                    # Admin dashboard
└── view_authority.php           # Admin's view for reviewing authority requests
```

---

## 🚀 Getting Started (Windows Setup)

You do **not** need XAMPP to run this project. You can run it directly using the built-in scripts.

### 📋 Prerequisites
1. **PHP 8.3** installed (and/or added to your system environment variables).
2. **MySQL Server / MariaDB** installed.

### ⚡ Quick Start
1. Double-click or run **`setup-and-run.bat`** in the project root directory.
   * This script automatically initializes a local database in the `mysql-data` directory on port `3307`.
   * It runs the `init-db.php` script to import the tables from `incident_db.sql`.
   * It launches the PHP built-in web server and opens the application in your browser.
2. Open **[http://127.0.0.1:8000](http://127.0.0.1:8000)** to view the app.

---

## 🔒 Default Credentials (For Testing)

* **Admin Username**: `admin`
* **Admin Password**: `admin123`

---

## 📐 Database Schema

The database `incident_db` relies on the following relational structure:
* `users` / `admin` (Stores logins, passwords, roles)
* `Citizen` / `Authority` (Extended profile details tied to user accounts)
* `Incident` (Stores reported events, categories, and severity)
* `Location` (Linked to incidents to track location details: city, area, province)
* `Reports` (Intermediate relation connecting citizen reports to incidents)
* `Response` (Stores resolution details submitted by authorities)
