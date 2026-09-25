@echo off
title La Buona Salsa - Servidor Local
echo ====================================================
echo   Iniciando La Buona Salsa (Modo Windows sin VM)
echo ====================================================
echo.

:: Comprobar si MySQL esta corriendo en el puerto 3306
netstat -ano | findstr :3306 >nul
if %errorlevel% neq 0 (
    echo [1/3] Iniciando motor de base de datos MySQL...
    start "" /B "D:\wamp64\bin\mysql\mysql8.4.7\bin\mysqld.exe" --defaults-file="D:\wamp64\bin\mysql\mysql8.4.7\my_local.ini"
    timeout /t 3 /nobreak >nul
) else (
    echo [1/3] Base de datos MySQL ya esta en ejecucion.
)

:: Abrir el navegador
echo [2/3] Abriendo navegador...
start http://localhost:8080

:: Iniciar servidor CodeIgniter
echo [3/3] Iniciando servidor web CodeIgniter en http://localhost:8080 ...
echo (Presiona Ctrl + C para detener el servidor)
echo ====================================================
"D:\wamp64\bin\php\php8.3.28\php.exe" "D:\wamp64\www\la-buona-salsa\spark" serve --port 8080