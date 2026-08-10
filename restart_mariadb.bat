@echo off
echo Restarting MariaDB service...
net stop MariaDB
net start MariaDB
echo Done! Please refresh phpMyAdmin or your Laravel browser tab.
pause
