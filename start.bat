@echo off
setlocal

cd /d "%~dp0"

set "PHP_EXE="

for /f "delims=" %%I in ('where php 2^>nul') do (
    set "PHP_EXE=%%I"
    goto :php_found
)

if exist "%ProgramFiles%\PHP\8.3\php.exe" set "PHP_EXE=%ProgramFiles%\PHP\8.3\php.exe"
if not defined PHP_EXE if exist "%ProgramFiles%\PHP\php.exe" set "PHP_EXE=%ProgramFiles%\PHP\php.exe"
if not defined PHP_EXE if exist "%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe" set "PHP_EXE=%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe\php.exe"
if not defined PHP_EXE if exist "%LOCALAPPDATA%\Microsoft\WindowsApps\php.exe" set "PHP_EXE=%LOCALAPPDATA%\Microsoft\WindowsApps\php.exe"

:php_found
if not defined PHP_EXE (
    echo PHP was not found.
    echo Install PHP 8.3 and reopen this terminal, or add php.exe to PATH.
    pause
    exit /b 1
)

echo Using PHP: %PHP_EXE%
echo Starting local server at http://127.0.0.1:8000
echo Press Ctrl+C to stop the server.

start "" "http://127.0.0.1:8000"
"%PHP_EXE%" -c "%~dp0php.ini" -S 127.0.0.1:8000 -t "%~dp0"

endlocal