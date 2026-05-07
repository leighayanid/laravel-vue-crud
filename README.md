# Laravel Markdown Editor

This project was created as part of a hiring process for a Laravel + Vue.js developer position. It began as a simple note-taking application and is intended to grow into a basic markdown editor while revisiting Laravel, Inertia.js, and Vue fundamentals.

## Features

- User registration and authentication
- Email verification and two-factor authentication support
- Create, view, edit, and delete notes
- Attach tags to notes
- Profile, password, and security settings

## Learning Goals

- Revisit Laravel routing, controllers, models, migrations, policies, and validation.
- Practice building full-stack pages with Inertia.js.
- Build Vue components for forms, previews, layout, and editor interactions.
- Turn the note editor into a markdown writing experience with preview support.

## Assessment Focus

- Demonstrate basic Laravel application structure and conventions.
- Use Inertia.js to connect Laravel routes and controllers with Vue pages.
- Build maintainable Vue components with TypeScript.
- Show familiarity with authentication, authorization, forms, validation, and CRUD flows.

## Markdown Editor Roadmap

- Add markdown input for note content.
- Render markdown previews when viewing notes.
- Add a split editor and preview screen.
- Add autosave or draft handling.
- Add basic formatting shortcuts for headings, bold, italic, links, and lists.
- Improve note organization with tags and search.

## Tech Stack

- Laravel 13
- Inertia.js
- Vue 3
- TypeScript
- Tailwind CSS
- SQLite by default

## Requirements

- PHP 8.3 or newer
- Composer
- Node.js and npm

## Setup

Install PHP and JavaScript dependencies:

```bash
composer install
npm install
```

Create the environment file and application key:

```bash
cp .env.example .env
php artisan key:generate
```

Create the SQLite database file and run migrations:

```bash
touch database/database.sqlite
php artisan migrate
```

Start the development server:

```bash
composer run dev
```

The app will be available at `http://127.0.0.1:8000`.

## Common Commands

```bash
npm run dev          # Start the Vite dev server only
npm run build        # Build frontend assets
npm run lint:check   # Check JavaScript and Vue linting
npm run types:check  # Check TypeScript types
composer test        # Run formatting checks and PHP tests
```

## Project Structure

- `app/Http/Controllers/NoteController.php` handles note CRUD actions.
- `app/Models` contains the `Note`, `Tag`, and `User` models.
- `resources/js/pages` contains Inertia/Vue pages.
- `resources/js/components` contains shared Vue components.
- `routes/web.php` defines the main web routes.
- `database/migrations` contains database schema changes.
