@echo off
:: This batch file has to be in a subfolder directly under the project directory.
:: Needed parameters:
::   %1 current file relative to project directory
::   %2 project name
:: The enviroment variable IDEA_BACKUP_PATH has to be set.
:: set IDEA_BACKUP_PATH=H:\IDEA_BACKUPS

IF "%IDEA_BACKUP_PATH%"=="" GOTO CANCEL
cd %~dp0
cd ..\..
:: start C:\test.bat "%CD%\%2\%1" "%IDEA_BACKUP_PATH%\%2\%1*"
xcopy "%CD%\%2\%1" "%IDEA_BACKUP_PATH%\%2\%1*" /Y
:CANCEL
