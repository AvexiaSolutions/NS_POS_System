@echo off
setlocal enabledelayedexpansion

:: Auto-detect PHP and add local PHP folder to PATH if PHP is not globally available
where php >nul 2>&1
if %errorlevel% neq 0 (
    if exist "%~dp0php-8.5.8\php.exe" (
        set "PATH=%~dp0php-8.5.8;%PATH%"
    ) else if exist "%~dp0php\php.exe" (
        set "PATH=%~dp0php;%PATH%"
    )
)

echo ==========================================
echo Fix Storage Link and Clear Cache for POS
echo ==========================================
cd /d "%~dp0"

echo.
echo Removing broken storage link...
if exist "public\storage" rmdir /s /q "public\storage"

echo.
echo Creating new storage link...
php artisan storage:link

echo.
echo Clearing application cache...
php artisan optimize:clear

echo.
echo ==========================================
echo DONE! You can now start the POS system.
echo ==========================================
pause
