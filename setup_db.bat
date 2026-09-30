@echo off
echo ==============================================
echo   VAA THE - CAI DAT DATABASE TU DONG (XAMPP)
echo ==============================================

REM Duong dan mac dinh den MySQL cua XAMPP
set MYSQL_PATH=C:\xampp\mysql\bin\mysql.exe

IF NOT EXIST "%MYSQL_PATH%" (
    echo Lanh "mysql" khong co san, kiem tra duong dan XAMPP cua ban...
    echo Neu XAMPP cai o o dia khac (VD: D:\xampp), hay chuot phai vao file nay,
    echo chon Edit de sua lai bien MYSQL_PATH.
    pause
    exit /b
)

echo.
echo [*] Dang khoi tao Database 'vaa_the'...
"%MYSQL_PATH%" -u root -e "CREATE DATABASE IF NOT EXISTS vaa_the DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo [*] Dang nap du lieu tu file vaa_the.sql (Se mat vai giay)...
"%MYSQL_PATH%" -u root vaa_the < vaa_the.sql

echo.
echo ==============================================
echo IMPORT THANH CONG! DU AN DA SAN SANG.
echo ==============================================
pause
