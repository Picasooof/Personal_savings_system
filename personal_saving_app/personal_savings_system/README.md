# Personal Savings Application

A web-based personal finance management system built with Laravel that helps users track their savings, set financial goals, and manage transactions.

## Features

### User Features
- **Dashboard Overview**
  - View total savings, active goals, and recent transactions
  - Quick access to add transactions and savings
  - Visual progress tracking for savings goals

- **Transaction Management**
  - Track income, expenses, and savings
  - Categorize transactions
  - View transaction history with filtering options

- **Savings Goals**
  - Create and manage savings goals
  - Set target amounts and deadlines
  - Track progress with visual indicators
  - Contribute to specific savings goals

### Admin Features
- **Dedicated Admin Login**
  - Secure admin-only access at `/admin/login`
  - Separate authentication system from regular users
  - Protected admin routes

- **Administrative Dashboard**
  - Overview of system statistics
  - Monitor user activity
  - Track overall platform usage

- **User Management**
  - View and manage user accounts
  - Monitor user transactions
  - Track individual user progress

- **Transaction Monitoring**
  - View all system transactions
  - Filter and search capabilities
  - Transaction analytics

## Technical Requirements

- PHP 8.1 or higher
- Laravel 10.x
- MySQL 5.7 or higher
- Composer
- Node.js & NPM
- Web server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd personal_savings_system
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install and compile frontend assets:
```bash
npm install
npm run dev
```

4. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=personal_savings
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations:
```bash
php artisan migrate
```

7. Create an admin user:
```bash
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@gmail.com', 'password' => Hash::make('123'), 'is_admin' => true]);
```

## Usage

### Regular User Access
1. Register a new account at `/register`
2. Login at `/login`
3. Access features:
   - View and manage personal dashboard
   - Create and track savings goals
   - Record income and expenses
   - Monitor savings progress

### Admin Access
1. Access admin login at `/admin/login`
2. Use admin credentials:
   - Email: `admin@gmail.com`
   - Password: `123`
3. Admin features:
   - Access comprehensive dashboard
   - View all user accounts
   - Monitor all transactions
   - Track system-wide savings goals

## Security Features

- Separate admin authentication system
- Protected admin routes with middleware
- CSRF protection
- Secure password hashing
- Session management
- Form validation and sanitization

## Troubleshooting

### Common Issues
1. If you encounter database errors:
   ```bash
   php artisan migrate:fresh
   ```

2. If admin access isn't working:
   - Verify the `is_admin` column exists in users table
   - Check admin user credentials
   - Clear cache:
     ```bash
     php artisan cache:clear
     php artisan config:clear
     ```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support:
- Create an issue in the repository
- Contact the development team
- Check the troubleshooting section
