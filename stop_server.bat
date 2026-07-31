@echo off
setlocal enabledelayedexpansion

echo =========================================================
echo NS POS System - Stopping All Server Processes...
echo =========================================================

echo.
echo Stopping MySQL Server...
if exist "%~dp0MySql\bin\mysqladmin.exe" (
    "%~dp0MySql\bin\mysqladmin.exe" -u root --port=3307 shutdown >nul 2>&1
) else if exist "%~dp0MySql\mysql-9.7.1-winx64\bin\mysqladmin.exe" (
    "%~dp0MySql\mysql-9.7.1-winx64\bin\mysqladmin.exe" -u root --port=3307 shutdown >nul 2>&1
)
taskkill /F /IM mysqld.exe /T >nul 2>&1

echo Stopping Laravel Web Server and Reverb WebSocket Server...
taskkill /F /IM php.exe /T >nul 2>&1

echo.
echo =========================================================
echo POS Server has been successfully stopped!
echo =========================================================
echo.
echo Closing window in 5 seconds...
ping -n 6 127.0.0.1 >nul
