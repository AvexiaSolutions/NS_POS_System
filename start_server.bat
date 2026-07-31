@echo off
setlocal enabledelayedexpansion

:: =========================================================
:: NS POS System - Portable Server Launcher
:: =========================================================

:: 1. Auto-detect PHP: Check if PHP is available globally.
::    If not found, automatically add local PHP folder to PATH.
where php >nul 2>&1
if %errorlevel% neq 0 (
    if exist "%~dp0php-8.5.8\php.exe" (
        set "PATH=%~dp0php-8.5.8;%PATH%"
    ) else if exist "%~dp0php\php.exe" (
        set "PATH=%~dp0php;%PATH%"
    )
)

:: Verify PHP is now accessible
where php >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] PHP was not found on this computer and local PHP folder could not be detected.
    echo Please ensure the php-8.5.8 folder exists inside %~dp0
    pause
    exit /b 1
)

:: 2. Start MySQL Server on Port 3307
set "MYSQL_BIN="
if exist "%~dp0MySql\bin\mysqld.exe" (
    set "MYSQL_BIN=%~dp0MySql\bin"
) else if exist "%~dp0MySql\bin\mysqld.exe" (
    set "MYSQL_BIN=%~dp0MySql\bin"
)

if defined MYSQL_BIN (
    echo Starting MySQL Server on port 3307...
    cd /d "!MYSQL_BIN!"
    start /b "" mysqld.exe --port=3307
) else (
    echo [WARNING] Local mysqld.exe not found in MySql directory. Assuming external MySQL is running.
)

:: Wait 3 seconds for MySQL to initialize using ping to avoid input redirection errors
ping -n 4 127.0.0.1 >nul

:: 3. Start Laravel Reverb WebSocket Server on Port 8080
echo Starting Reverb WebSocket Server...
cd /d "%~dp0"
start /b "" php artisan reverb:start --host=0.0.0.0 --port=8080

:: 4. Start Laravel Web Server on Port 8000
echo Starting Laravel Server...
start /b "" php artisan serve --host=0.0.0.0 --port=8000

echo =========================================================================
echo POS Server is running at: http://localhost:8000 (USE http:// NOT https://)
echo Reverb WebSocket is listening on port 8080
echo =========================================================================
echo.
echo Launcher will automatically close in 10 seconds (servers stay running in background)...
ping -n 11 127.0.0.1 >nul
