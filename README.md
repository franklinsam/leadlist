# Laravel Livewire Leads Management System

A comprehensive leads management system built with Laravel, Livewire, Bootstrap, and Font Awesome.

## Features

- **Advanced DataTable** with sorting, filtering, and pagination
- **CRUD Operations** for leads management
- **Bulk Actions** for efficient lead processing
- **Export Functionality** for data extraction
- **Responsive Design** using Bootstrap 5
- **Status Badges** for visual status representation
- **Column Customization** for personalized views
- **Date Range Filtering** for time-based analysis

## Local Development Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy `.env.example` to `.env` and configure your database
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```
6. Start the development server:
   ```bash
   php artisan serve
   ```

## Deploying to Vercel

### Prerequisites

1. A GitHub, GitLab, or Bitbucket account
2. A Vercel account
3. A MySQL database service (PlanetScale, Supabase, Railway, etc.)

### Deployment Steps

1. Push your code to a Git repository
2. Log in to your Vercel account
3. Click "New Project" and import your repository
4. Configure the following environment variables:
   - `APP_KEY`: Your Laravel application key (base64 encoded)
   - `APP_ENV`: production
   - `APP_DEBUG`: false
   - `DB_CONNECTION`: mysql
   - `DB_HOST`: Your database host
   - `DB_PORT`: Your database port
   - `DB_DATABASE`: Your database name
   - `DB_USERNAME`: Your database username
   - `DB_PASSWORD`: Your database password
5. Deploy the project

### Post-Deployment

After deployment, you need to run migrations and seeders. You can do this by:

1. Setting up a CI/CD pipeline, or
2. Using Vercel's CLI to run commands:
   ```bash
   vercel env pull
   php artisan migrate --seed
   ```

## External Database Services

For production, you'll need to use an external database service. Some options include:

- **PlanetScale**: MySQL-compatible serverless database
- **Supabase**: PostgreSQL database with additional features
- **Railway**: Platform for provisioning databases
- **AWS RDS**: Managed relational database service

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
