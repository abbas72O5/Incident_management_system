@echo off
setlocal

cd /d "%~dp0"

set "PHP_EXE="
set "MYSQL_BASEDIR=C:\Program Files\MySQL\MySQL Server 9.6"
set "MYSQLD_EXE=%MYSQL_BASEDIR%\bin\mysqld.exe"
set "MYSQLADMIN_EXE=%MYSQL_BASEDIR%\bin\mysqladmin.exe"
set "MYSQL_DATADIR=%~dp0mysql-data"

for /f "delims=" %%I in ('where php 2^>nul') do (
    set "PHP_EXE=%%I"
    goto :php_found
)

if exist "%ProgramFiles%\PHP\8.3\php.exe" set "PHP_EXE=%ProgramFiles%\PHP\8.3\php.exe"
if not defined PHP_EXE if exist "%ProgramFiles%\PHP\php.exe" set "PHP_EXE=%ProgramFiles%\PHP\php.exe"
if not defined PHP_EXE if exist "%ProgramFiles%\PHP 8.3\php.exe" set "PHP_EXE=%ProgramFiles%\PHP 8.3\php.exe"
if not defined PHP_EXE if exist "%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" set "PHP_EXE=%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
if not defined PHP_EXE if exist "%LOCALAPPDATA%\Microsoft\WindowsApps\php.exe" set "PHP_EXE=%LOCALAPPDATA%\Microsoft\WindowsApps\php.exe"

:php_found
if not defined PHP_EXE (
    echo PHP was not found.
    echo Install PHP 8.3 and reopen this terminal.
    pause
    exit /b 1
)

if not exist "%MYSQL_DATADIR%" mkdir "%MYSQL_DATADIR%"

if not exist "%MYSQL_DATADIR%\mysql" (
    echo Initializing local MySQL data directory...
    "%MYSQLD_EXE%" --initialize-insecure --basedir="%MYSQL_BASEDIR%" --datadir="%MYSQL_DATADIR%"
    if errorlevel 1 (
        echo Local MySQL initialization failed.
        pause
        exit /b 1
    )
)

echo Starting local MySQL server on port 3307...
start "" /B "%MYSQLD_EXE%" --basedir="%MYSQL_BASEDIR%" --datadir="%MYSQL_DATADIR%" --port=3307 --bind-address=127.0.0.1 --console

set "DB_READY="
for /L %%I in (1,1,30) do (
    "%MYSQLADMIN_EXE%" --protocol=tcp -h127.0.0.1 -P3307 -uroot ping >nul 2>nul
    if not errorlevel 1 (
        set "DB_READY=1"
        goto :db_ready
    )
    timeout /t 1 /nobreak >nul
)

if not defined DB_READY (
    echo Local MySQL server did not start.
    pause
    exit /b 1
)

:db_ready

echo Importing database schema into incident_db...
"%PHP_EXE%" -c "%~dp0php.ini" init-db.php
if errorlevel 1 (
    echo Database import failed.
    pause
    exit /b 1
)

echo Starting local server at http://127.0.0.1:8000
start "" "http://127.0.0.1:8000"
"%PHP_EXE%" -c "%~dp0php.ini" -S 127.0.0.1:8000 -t "%~dp0"

endlocal
