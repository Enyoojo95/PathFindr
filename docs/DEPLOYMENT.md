# Production Deployment

PathFindr is a Laravel application. Serve the `public/` directory as the web root; do not expose the repository root, `.env`, `vendor/`, or `storage/` directly.

## Requirements

- PHP 8.3 or newer with `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `ctype`, `json`, `tokenizer`, `xml`, and `curl` enabled.
- MySQL or a compatible MariaDB server.
- Composer 2 and Node.js/npm for the release build.
- HTTPS and a web server configured to route non-file requests through `public/index.php`.

## Production environment

Create the production `.env` outside source control. Set at least:

```dotenv
APP_NAME=PathFindr
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
APP_KEY=base64:generated-once-with-artisan

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pathfindr
DB_USERNAME=pathfindr_app
DB_PASSWORD=use-a-unique-secret

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_SECURE_COOKIE=true

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-user
MAIL_PASSWORD=your-smtp-password
MAIL_SCHEME=tls
MAIL_FROM_ADDRESS=noreply@your-domain.example
MAIL_FROM_NAME=PathFindr

SUPER_ADMIN_SETUP_CODE=use-a-different-one-time-secret
```

Generate `APP_KEY` once with `php artisan key:generate --show` and keep it stable. Do not commit the generated key or any database, mail, DeepSeek, or super-admin secrets. Configure the one-time setup code before creating the first super-admin; clear `SUPER_ADMIN_SETUP_CODE` afterward. The setup page is unavailable once a super-admin exists. Master accounts sign in using their email and password; login attempts are rate-limited.

Use real SMTP credentials so email verification and password-reset messages can be delivered. DeepSeek credentials are optional; career guidance falls back to local rules when the API is unavailable.

## Release steps

Run from the release directory after configuring `.env`:

```sh
composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan db:seed --class=QuestionSeeder --force
php artisan storage:link
php artisan optimize
```

The question seeder only populates an empty bank and does not delete existing questions or student answers. Never run `migrate:fresh` or `migrate:refresh` against production data. Back up the database before migrations and retain a tested restore path.

Ensure `storage/` and `bootstrap/cache/` are writable by the application user. Keep the web server document root on `public/`. Run a supervised `php artisan queue:work` process if the deployment uses queued work, and configure monitoring for failed jobs, application logs, disk space, and database backups.

Before opening traffic, check `php artisan about`, confirm debug mode is off, verify email delivery, run the test suite in an environment with SQLite PDO enabled, and smoke-test registration, verification, exam submission, result/PDF download, and admin permissions over HTTPS.