# Zero Imports

A Laravel + Inertia + Vue + Vite app for a small product catalog with an admin dashboard.

## Tech stack

- **Backend:** Laravel 12
- **Frontend:** Vue 3 + Inertia.js
- **Build tool:** Vite
- **UI/Icons:** Tailwind-based UI components + lucide
- **Uploads:** Stored on the `public` disk (`storage/app/public`)

## Requirements

- PHP 8.2+ (recommended)
- Composer
- Node.js 18+ (recommended) + npm
- A database (MySQL/MariaDB recommended) **or** SQLite (for local/dev)

## Local setup

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Environment

Copy `.env.example` to `.env` and generate the app key:

```bash
php artisan key:generate
```

### 3) Database

#### Option A — MySQL / MariaDB

Set these in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zeroimports_db
DB_USERNAME=...
DB_PASSWORD=...
```

Then run:

```bash
php artisan migrate
```

#### Option B — SQLite (simple local dev)

Set these in `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Make sure the SQLite file exists (it’s already in this repo), then run:

```bash
php artisan migrate
```

> Windows note: if you see `could not find driver`, enable/install the PHP SQLite extensions (`pdo_sqlite` and `sqlite3`).

### 4) Sessions / cache / queue (recommended for development)

To avoid storing sessions/cache/queue data in the database during local development, set:

```env
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Then clear cached configuration:

```bash
php artisan config:clear
php artisan cache:clear
```

## Running the app

```bash
composer run dev
```

Open the URL `http://127.0.0.1:8000/`.

## Docker (MySQL)

This repo includes a `docker-compose.yml` with a MySQL 8.0 service.

```bash
docker compose up -d
```

Then configure your `.env` to point to MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zeroimports_db
DB_USERNAME=pedro
DB_PASSWORD=YOUR_PASSWORD
```

Finally:

```bash
php artisan migrate
```

> Tip: if you run Laravel **inside** a container in the future, `DB_HOST` should be the service name `db` instead of `127.0.0.1`.

## Admin dashboard

The admin dashboard route is protected:

- `GET /dashboard` (requires `auth` + `verified`)

Product endpoints:

- `POST /produtos`
- `PUT /produtos/{product}`
- `DELETE /produtos/{product}`

## Product images

- The admin supports **multiple images** per product.
- Files are stored on the `public` disk under `products/`.

### Image formats

Uploads are validated as images.

Modern devices/browsers may produce **AVIF/HEIC** images. If you upload files named `.jpg/.jpeg` but your server detects them as `image/avif`, make sure your backend validation allows `image/avif`.

### Storage symlink

If you don’t see images publicly, ensure the storage link exists:

```bash
php artisan storage:link
```

## Tests

```bash
php artisan test
```

## Troubleshooting

### “Each file must be an image”

- Make sure the upload field is sent as `images[]` (array) and the request is `multipart/form-data`.
- Check the detected MIME type in `storage/logs/laravel.log`.
- Allow modern formats (AVIF/HEIC) or convert server-side.

### MySQL connection refused (SQLSTATE[HY000] [2002])

- Ensure MySQL is running (service or Docker).
- Verify `.env` `DB_HOST`, `DB_PORT`, username/password.
- For development without DB-backed sessions/cache, use:

```env
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### SQLite “could not find driver”

- Enable `pdo_sqlite` and `sqlite3` extensions in your `php.ini`.

---

## License

Private/internal project (update as needed).
