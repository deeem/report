# Speckombinat Report

## About
Приложение для сдачи отчётности в ВТВ. Замена ZVIT.

## Requirements

- Docker

## Install & Up

- `make install`
- `docker-compose up -d`

## Usage

ReportApp `http://127.0.0.1:8080`

phpMyAdmin `http://127.0.0.1:8081`

Использование Makefile:
- `make install` - composer устанавливает зависимости, указанные в _composer.json_ (аналог **composer install**)
- `make autoload` - аналог **composer dump-autoload**
- `make lint` - проверяет код на соответсвие стандарту _PSR-2_
- `make tests` - запускает тесты _phpunit_, с конфигурацией из _phpunit.xml.dist_
