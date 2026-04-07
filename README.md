# Pahiran Website

A multi-vendor e-commerce platform built with Laravel 12.0, featuring a complete marketplace system for sellers, customers, and administrators.

## Features

### Storefront
- Product browsing and search
- Category-based navigation
- Shopping cart (session-based)
- Wishlist functionality
- Customer authentication
- Secure checkout with Khalti payment integration

### Seller Dashboard
- Seller registration and authentication
- Product management (CRUD operations)
- Order processing and status management
- Sales analytics and reporting
- Commission-based system

### Admin Panel
- Complete administrative control
- User management (customers, sellers)
- Product oversight and approval
- Order management across all sellers
- Seller commission configuration
- System analytics

### Technical Features
- Multi-authentication system (Customer, Seller, Admin)
- Role-based access control
- Database-driven session management
- Queue system for background processing
- Comprehensive API structure
- Responsive frontend design
- RESTful API endpoints

## Installation

### Prerequisites
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js & NPM
- Git

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Pahiran-website
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   - Update your `.env` file with database credentials
   - Create the database: `pahiran_website`

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start development server**
   ```bash
   php artisan serve
   ```

### Quick Setup Script
Run the automated setup script:
```bash
composer run setup
```

This will install dependencies, set up the environment, run migrations, and build assets.

## Project Structure

```
Pahiran-website/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Seller/         # Seller dashboard controllers
│   │   └── ...             # Other controllers
│   ├── Models/             # Eloquent models
│   └── ...                 # Other app files
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   └── js/                # Frontend JavaScript
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes
└── public/                # Public assets
```

## Authentication System

The application uses a multi-authentication system with three distinct user types:

1. **Customers** - Browse products, manage cart, place orders
2. **Sellers** - Manage products, process orders, view analytics
3. **Administrators** - Oversee entire platform, manage users

## Payment Integration

Integrated with **Khalti** payment gateway for secure online transactions.

## Development

### Running Tests
```bash
php artisan test
```

### Code Formatting
```bash
./vendor/bin/pint
```

### Development Server
Start all development services:
```bash
composer run dev
```

This starts:
- Laravel development server
- Queue worker
- Vite development server

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support and questions, please open an issue in the repository.
