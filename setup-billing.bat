@echo off
echo Setting up billing system...

echo Running migrations...
php artisan migrate --path=database/migrations/2024_01_01_000001_create_services_table.php
php artisan migrate --path=database/migrations/2024_01_01_000002_create_invoices_table.php
php artisan migrate --path=database/migrations/2024_01_01_000003_create_invoice_items_table.php

echo Seeding services...
php artisan db:seed --class=ServicesSeeder

echo Billing system setup complete!
pause
