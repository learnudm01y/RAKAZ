@echo off
echo ========================================
echo   Viewing Laravel Logs (Last 100 lines)
echo ========================================
echo.
powershell -Command "Get-Content 'storage\logs\laravel.log' -Tail 100 -Wait"
