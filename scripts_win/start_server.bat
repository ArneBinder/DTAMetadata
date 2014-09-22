@echo off
%windir%\system32\runas.exe /user:postgres "pg_ctl start"
cd ..
php app/console server:run