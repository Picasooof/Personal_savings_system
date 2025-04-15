# Personal Savings Application

A web-based personal finance management system built with Laravel that helps users track their savings, set financial goals, and manage their transactions.

## Features

- **User Authentication**
  - Secure login and registration
  - Password reset functionality
  - Email verification
  - Admin and user roles

- **Dashboard**
  - Overview of total savings
  - Active savings goals
  - Recent transactions
  - Monthly spending summary
  - Category-wise expense breakdown

- **Savings Goals**
  - Create and manage savings targets
  - Track progress towards goals
  - Set deadlines
  - Visual progress indicators

- **Transaction Management**
  - Record income and expenses
  - Categorize transactions
  - Track transaction history
  - Generate spending reports

- **Admin Panel**
  - User management
  - System monitoring
  - Transaction oversight
  - Analytics and reporting

## Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM
- Laravel 10.x

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

4. Configure environment variables:
```bash
cp .env.example .env
php artisan key:generate
```

5. Update `.env` file with your database credentials and other configuration settings.

6. Run database migrations and seeders:
```bash
php artisan migrate
php artisan db:seed
```

7. Create storage link:
```bash
php artisan storage:link
```

8. Start the development server:
```bash
php artisan serve
```

## Usage

1. Register a new account or login with existing credentials
2. Set up your savings goals
3. Start tracking your income and expenses
4. Monitor your progress through the dashboard
5. Generate reports to analyze your spending patterns

## Security

- CSRF protection enabled
- SQL injection prevention
- XSS protection
- Secure password hashing
- Rate limiting on authentication attempts

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please email [support-email] or open an issue in the repository.

## Acknowledgments

- Laravel Framework
- Bootstrap
- Font Awesome
- All contributors who have helped shape this project

## Project Status

Currently in active development. 




