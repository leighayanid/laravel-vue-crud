# LaraNote

LaraNote is a simple note-taking app built with Laravel, Inertia.js, Vue, and TypeScript. This project was created as part of a hiring process and started as a CRUD notes app. It now includes authentication, a cleaner landing page, a dashboard summary, markdown preview, and production deployment on Render.

Live site: https://laravel-vue-crud.onrender.com

Note: the app is hosted on Render's free tier, so the first visit may take a little while to load while the service wakes up.

## Features

- User registration and login
- Email verification and two-factor authentication support
- Create, update, and delete notes
- Pin and color-code notes
- Markdown preview for note content
- Dashboard with total notes count
- Profile, password, and security settings
- Responsive Vue/Inertia pages
- Feature tests for dashboard and notes behavior

## Tech Stack

- Laravel 13
- Inertia.js
- Vue 3
- TypeScript
- Tailwind CSS
- SQLite for local development
- PostgreSQL on Render
- Docker for deployment

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
composer run dev
```

The local app runs at:

```txt
http://127.0.0.1:8000
```

## Tests

```bash
php artisan test
npm run types:check
```

## Deployment

The app is deployed to Render using Docker and Render PostgreSQL.

Important production environment values:

```txt
APP_ENV=production
APP_DEBUG=false
APP_URL=https://laravel-vue-crud.onrender.com
ASSET_URL=https://laravel-vue-crud.onrender.com
DB_CONNECTION=pgsql
DATABASE_URL=<Render Internal Database URL>
AUTORUN_ENABLED=true
```

Render start command:

```bash
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
```

## Project Notes

- `app/Http/Controllers/NoteController.php` handles note CRUD.
- `resources/js/pages` contains the Inertia/Vue pages.
- `resources/js/components` contains shared UI components.
- `routes/web.php` defines the main web routes.
- `tests/Feature` contains the Laravel feature tests.
