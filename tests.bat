@echo off
date 2015-09-13
time 03:30:50
@mode con: cols=160 lines=1200
@php C:\bin\phpunit.phar --bootstrap framework/autoload.php tests
@pause

