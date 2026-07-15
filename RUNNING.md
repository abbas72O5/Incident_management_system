# Running This Project

This is a plain PHP + MySQL app. It does not use Composer or a PHP framework.

## Requirements

- PHP with the `mysqli` extension enabled
- MySQL or MariaDB
- A browser

## Best Option On Windows

You do **not** need XAMPP for local development.

The simplest no-XAMPP setup is:

- install PHP separately
- install MariaDB separately
- run the project with PHP's built-in web server

If you want a more full-featured local stack, Laragon is also a good option on Windows, but it is optional.

## Database

The app expects a database named `incident_db`.

I did not find an `.sql` dump in this folder, so you will need either:

- the original schema export, or
- to recreate the tables manually.

The PHP code references tables such as:

- `users`
- `admin`
- `Citizen`
- `Authority`
- `Incident`
- `Location`
- `Reports`
- `Response`
- `Police`
- `firebrigade`
- `municipal`
- `trafficpolice`

## Quick Setup Without XAMPP

1. Install PHP 8.3 using Winget.
2. Install MariaDB using Winget.
3. Open a new terminal after install so PATH refreshes.
4. Double-click or run `setup-and-run.bat` in this project folder.
5. The script initializes a local MySQL data directory in `mysql-data`, starts a local server on port `3307`, imports `incident_db.sql`, and launches the local PHP server.
6. Open `http://127.0.0.1:8000` in your browser.

## Notes

- `db.php` uses `localhost`, `root`, and an empty password by default, so you may need to update it if your MariaDB install uses a different username or password.
- I updated `db.php` so sessions start automatically when the shared bootstrap is included.
- If your MySQL username, password, or database name differs, update `db.php`.
- The seed admin login is `admin` / `admin123`.
- `php.ini` in this folder loads the bundled `mysqli` and `pdo_mysql` extensions from the PHP package cache.
- The app talks to the local MySQL instance on port `3307`, not the system MySQL service.