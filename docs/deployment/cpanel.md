# Ascend Systems on cPanel Shared Hosting

This deployment profile is intended for cPanel hosting without Redis or a permanently running queue worker. It uses MySQL-backed sessions, cache, and queues, plus cPanel Cron Jobs.

## 1. Hosting requirements

- PHP 8.3 or newer
- MySQL/MariaDB
- PHP extensions: `ctype`, `curl`, `fileinfo`, `filter`, `hash`, `mbstring`, `openssl`, `pdo`, `pdo_mysql`, `tokenizer`, `xml`
- SSH access and Composer are strongly recommended
- Document root pointed to the application's `public` directory
- HTTPS enabled

Keep the application source, `.env`, `storage`, `bootstrap/cache`, and `vendor` outside `public_html` where possible. Only the contents of `public` should be web-accessible.

## 2. Upload and install

From the project directory on the server:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.example .env
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan optimize
```

Set the real values in `.env` before running migrations:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.example
APP_INTERNAL_WORKSPACE=true
APP_INSTALLED=true

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=ascend_systems
DB_USERNAME=ascend_user
DB_PASSWORD=your-database-password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public

MAIL_MAILER=smtp
MAIL_HOST=your-mail-host
MAIL_PORT=587
MAIL_USERNAME=your-mailbox
MAIL_PASSWORD=your-mailbox-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=notifications@your-domain.example
MAIL_FROM_NAME="Ascend Systems"

RESEND_API_KEY=
RESEND_FROM_EMAIL=notifications@your-domain.example
RESEND_FROM_NAME="Ascend Systems"
```

The installer does not require a license key. For a first-time installation, leave `APP_INSTALLED=false`, open the site, complete the installer, then confirm that it writes the database and workspace settings. Do not commit or upload a shared `.env.example` as `.env` with real credentials.

## 3. Frontend assets

Build assets on a local machine or a server with Node.js, then upload the generated `public/build` directory:

```bash
npm ci
npm run build
```

If Node.js is unavailable in cPanel, do not run the development server there. Upload the completed `public/build` directory instead.

## 4. cPanel Cron Jobs

Add these jobs to run every minute. Replace the paths with the absolute paths shown by your cPanel account:

```cron
* * * * * cd /home/CPANEL_USER/ascend && /usr/local/bin/php artisan schedule:run >> /home/CPANEL_USER/ascend/storage/logs/scheduler.log 2>&1
* * * * * cd /home/CPANEL_USER/ascend && /usr/local/bin/php artisan ascend:shared-queue >> /home/CPANEL_USER/ascend/storage/logs/queue.log 2>&1
```

If cPanel requires one command per Cron Job, create two separate entries. The queue command processes a bounded batch and exits; it does not depend on Redis or a daemon. Keep `CPANEL_QUEUE_MAX_TIME` below the host's maximum cron execution time.

## 5. Permissions and checks

The web server must be able to write to:

- `storage`
- `bootstrap/cache`
- `.env` during the installer only

Run these checks after deployment:

```bash
php artisan about
php artisan migrate:status
php artisan schedule:list
php artisan ascend:shared-queue
php artisan optimize:clear
php artisan optimize
```

Check `storage/logs/laravel.log`, `storage/logs/scheduler.log`, and `storage/logs/queue.log` after sending a test email, scheduling a social post, receiving a webhook, and creating an AI task.

## Shared-hosting limits

Web requests, webhook normalization, database work, and low-volume email/social jobs can operate on cPanel. High-volume campaigns, continuous AI autoresponders, large media imports, and near-real-time publishing are constrained by cron frequency, CPU limits, outbound HTTP limits, and process termination. Move the queue and scheduler to a VPS when these workloads become business-critical.
