# LapkingHub Laravel Foundation

This repository contains a fresh Laravel 12 foundation configured for PHP 8.3, Vite, and Bootstrap 5.

No ecommerce functionality has been added.

## Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```
