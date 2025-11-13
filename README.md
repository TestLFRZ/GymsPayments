## Puesta en marcha

Guía rápida para instalar y ejecutar la aplicación Laravel/Inertia de pagos de gimnasios.

### Requisitos previos

- PHP 8.2+, Composer 2
- Node.js 20+ con npm (o pnpm/yarn si prefieres)
- MySQL/PostgreSQL (o cualquier base soportada por Laravel) y Redis opcional
- Extensiones PHP típicas de Laravel (`pdo`, `mbstring`, `openssl`, etc.)

### Instalación

1. Instala dependencias backend: `composer install`.
2. Copia la configuración base: `cp .env.example .env`.
3. Ajusta variables en `.env` (DB, colas, mail, etc.).
4. Genera la clave de la app: `php artisan key:generate`.
5. Ejecuta migraciones (y seeders si los necesitas): `php artisan migrate`.
6. Instala dependencias frontend: `npm install` (o `pnpm install` si usas pnpm).

### Cómo correr la aplicación

- Opción rápida (todo en un solo comando): `composer run dev`.  
  Este script levanta `php artisan serve`, el listener de colas, los logs en vivo y `npm run dev` con Vite.

- Opción manual:
  - En una terminal: `php artisan serve`.
  - En otra: `npm run dev` (o `pnpm dev`).

Ambas rutas dejan la app disponible en `http://localhost:8000` con recarga en caliente del frontend.

### Build y pruebas

- Compilar assets para producción: `npm run build` (o `pnpm build`).
- Ejecutar pruebas: `php artisan test` (se limpia la caché de config automáticamente antes de correrlas).
