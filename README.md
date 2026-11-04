# VueJS Template

Laravel application with a Vue 3 and Inertia frontend. The project provides a reusable Laravel starter structure with Fortify authentication, Tailwind CSS, and Vite-based frontend tooling.

## Requirements

- PHP 8.3+
- Composer
- Node.js and npm

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

## Development

```bash
composer run dev
```

## Quality checks

```bash
composer run lint:check
composer run types:check
```
