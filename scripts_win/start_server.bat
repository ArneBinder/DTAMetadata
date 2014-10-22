@echo off
start_postgres_server_all_pp.exe
cd ..
php app/console server:run