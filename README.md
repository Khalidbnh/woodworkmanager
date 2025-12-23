# Woodwork Management System

Internal management application for a woodwork business in Morocco.

## Features Completed

### ✅ Day 1 (Dec 21, 2024)
- [x] Project setup (Laravel + Filament v4)
- [x] Database configuration (MySQL)
- [x] **Client Management**
    - Create, read, update, delete clients
    - Search and filter clients
    - Client information: name, phone, email, address

## Features In Progress

### 🚧 Day 2 (Dec 22, 2024)
- [ ] Project Management (linked to clients)
- [ ] Employee Management
- [ ] Employee-Project assignments

## Tech Stack

- **Backend:** Laravel 11
- **Admin Panel:** FilamentPHP v4
- **Database:** MySQL (port 3306)
- **Server:** Hostinger

## Installation
```bash
# Clone repository
git clone https://github.com/Khalidbnh/woodworkmanager.git
cd woodwork-management

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=woodwork_db
DB_USERNAME=root
DB_PASSWORD=

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Create admin user
php artisan make:filament-user

# Start server
php artisan serve
```

Visit: http://localhost:8000/admin

## Database Schema

### Current Tables:
- **clients** - Customer information

### Planned Tables:
- projects (linked to clients)
- employees
- employee_project (pivot)
- suppliers
- materials
- project_material (pivot with payment tracking)
- invoices (Devis & Factures)

## Screenshots

*(Add screenshots tomorrow)*

## Author

Built as a KhaliDev portfolio project - December 2024
