# PathFindr

PathFindr is a Laravel-based career guidance and assessment platform for Nigerian students transitioning from JSS3 to senior secondary school. It helps students understand their academic strengths, choose a subject pathway, and explore relevant careers and Nigerian university courses.

## Overview

PathFindr is designed for students, schools, and administrators. It provides a structured approach to career guidance by combining psychometric assessment with AI-powered recommendations.

## Features

- Psychometric assessments covering numerical, verbal, spatial, and interest areas.
- Recommendations for Science, Commercial, or Arts pathways.
- Career and university-course suggestions for each student.
- Downloadable PDF assessment reports.
- Student, school administrator, and super-administrator workflows.
- School-level student management, exam monitoring, and result exports.
- Live landing-page and super-admin totals for registered students and represented schools.
- Optional DeepSeek integration for AI recommendations.
- Rule-based recommendations available when the AI service is unavailable.

## Technology

- PHP 8.3 or newer and Laravel 13.
- MySQL/MariaDB for deployment.
- SQLite configured by default for local development.
- Composer 2, Node.js, and npm.
- Vite, Tailwind CSS, and Alpine.js for frontend assets.
- Laravel Dompdf integration for PDF reports.

## Local Setup

Install PHP with the PDO driver for your chosen database, Composer, Node.js, and npm. The default local database is SQLite and requires `pdo_sqlite`.

From the project root, run:

```sh
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan key:generate
php artisan migrate
php artisan db:seed --class=QuestionSeeder
npm ci
npm run build
composer run dev
```

The development server is available at `http://localhost:8000`.

The `QuestionSeeder` fills an empty question bank and does not replace existing questions.

To use MySQL or MariaDB locally, configure the `DB_*` values in `.env` before running migrations.

For AI-powered recommendations, set `DEEPSEEK_API_KEY` in `.env`. `DEEPSEEK_BASE_URL` is optional and defaults to `https://api.deepseek.com`.

Email verification and password-reset messages require a configured mail transport. The default local mailer writes messages to the application log.

## Tests

Run the automated test suite with:

```sh
php artisan test
```

The configured test database uses in-memory SQLite, so PHP must have `pdo_sqlite` enabled.

## Production Deployment

See the [production deployment guide](docs/DEPLOYMENT.md) for server requirements, environment configuration, release steps, and operational safeguards.

Keep production secrets in the deployment environment and never commit `.env` credentials.
