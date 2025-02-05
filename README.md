# School Management Application - Laravel 11

## Overview

This project is a **School Management Application** built with **Laravel 11**. It allows **Admin** and **Teacher** users role to manage various school-related activities.

### Key Features:
- **Admin** can:
  - Login to the system.
  - Manage Teachers (Add, Edit, Delete).
  - Post announcements for Teachers.
  - View all Students, Parents, and Announcements from Teachers.

- **Teachers** can:
  - Login.
  - Manage Students and Parents.
  - Post announcements with email notifications targeted at Students and Parents.

## Prerequisites

- **PHP**: ^8.2
- **Composer**: For managing PHP dependencies.
- **Node.js**: For frontend development.
- **MySQL**: For the database.

## Dependencies

### PHP Dependencies (Composer)
```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^11.31",
    "laravel/tinker": "^2.9",
    "laravel/ui": "^4.6",
    "yajra/laravel-datatables-oracle": "^11.1"
},
"require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/pail": "^1.1",
    "laravel/pint": "^1.13",
    "laravel/sail": "^1.26",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.1",
    "phpunit/phpunit": "^11.0.1"
}
```

### JavaScript Dependencies (NPM)
```json
"devDependencies": {
    "@popperjs/core": "^2.11.6",
    "autoprefixer": "^10.4.20",
    "axios": "^1.7.4",
    "bootstrap": "^5.2.3",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^1.2.0",
    "postcss": "^8.4.47",
    "sass": "^1.56.1",
    "tailwindcss": "^3.4.13",
    "vite": "^6.0.11"
},
"dependencies": {
    "@fortawesome/fontawesome-free": "^6.7.2",
    "sweetalert2": "^11.15.10"
}
```

## Installation

### Step 1: Clone the Repository
```bash
git clone https://github.com/manishmakwana94/artisans-school-management.git
cd your-repository-directory
```

### Step 2: Install Composer Dependencies
```bash
composer install
```

### Step 3: Install Node Dependencies
```bash
npm install
```


### Step 4: Set Up Environment Variables
```bash
cp .env.example .env
```
Then, configure your `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_management
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_FROM_ADDRESS=admin@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 5: Generate Application Key
```bash
php artisan key:generate
```

### Step 6: Run Migrations and Seeders
```bash
php artisan migrate --seed

php artisan db:seed --class=CreateUsersSeeder

```
### This will also create a default Admin User:
```bash
Email : admin@gmail.com
Password : #Admin123
```

### Step 7: Build Frontend Assets
```bash
npm run dev
```

### Step 8: Queue Configuration for Email Jobs
Ensure your queue system is configured properly. Update the `.env` file:
```env
QUEUE_CONNECTION=database
```
Run the queue worker to process email jobs:
```bash
php artisan queue:work
```

## Running the Application

Start the Laravel development server:
```bash
composer run dev
```

## Testing
Run tests with:
```bash
php artisan test
```

## Project Structure
- **app/**: Application logic (controllers, models).
- **database/**: Migrations, seeders, and factories.
- **resources/**: Views and frontend assets.
- **routes/**: Web route definitions.
- **public/**: Public assets entry point.
- **jobs/**: Laravel jobs for sending emails.

## Contributing
1. Fork the repository.
2. Create a new branch for your feature.
3. Make changes and ensure tests pass.
4. Open a pull request.

## License
This project is licensed under the [MIT License](LICENSE).



