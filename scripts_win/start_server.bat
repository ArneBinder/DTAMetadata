@echo off
:: %windir%\system32\runas.exe /user:postgres "pg_ctl start" | sanur.exe postgres
start_postgres_server_sanur_pp.exe
cd ..
php app/console server:run