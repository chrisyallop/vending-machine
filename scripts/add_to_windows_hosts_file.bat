@echo off

SET NEWLINE=^& echo.

FIND /C /I "vending-machine" %WINDIR%\system32\drivers\etc\hosts
IF %ERRORLEVEL% NEQ 0 ECHO %NEWLINE%^192.168.10.10 vending-machine>>%WINDIR%\System32\drivers\etc\hosts
