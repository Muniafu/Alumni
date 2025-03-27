# Alumni Networking Platform

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)

## Project Overview

A comprehensive platform connecting alumni with employers through digital portfolios, job matching, and networking features.

## Features
- User authentication system
- Alumni portfolio management
- Job postings board
- Event management
- Professional networking capabilities
- Skills endorsements system
- Advanced search functionality

## Technology Stack
- **Backend**: Laravel 10
- **Frontend**: Blade Templates, Bootstrap 5
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Search**: Laravel Scout

## Installation

1. Clone repository:
```bash
git clone https://github.com/yourusername/alumni-system.git
cd alumni-system

## Install dependencie
composer install
npm install

## Configure environment:

cp .env.example .env
php artisan key:generate

## Setup database:

php artisan migrate --seed

## Compile assets:

npm run dev

## Start server:

php artisan serve
