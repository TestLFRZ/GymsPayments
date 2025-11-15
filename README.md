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

### Nuevos módulos multi-tenant

- **Tenancy y roles**: middleware `tenant` resuelve el gimnasio vía subdominio o cabecera `X-Tenant-Key`. Los modelos (`members`, `plans`, `payments`, etc.) usan `TenantScoped` para aislar datos.
- **API REST**: rutas protegidas en `routes/api.php` (`/members`, `/plans`, `/payments`, `/alerts`, `/audit-logs`) bajo `auth:sanctum` + `tenant`.
- **Pagos y alertas**: controladores base (`PaymentController`, `AlertController`) listos para integrar Stripe/manual, broadcasting y auditoría.
- **Frontend SPA**: se añadieron stores de Pinia (`resources/js/stores/*.ts`) y utilidades Axios (`resources/js/lib/http.ts`) para consumir la API multi-tenant, además de la preparación para tiempo real con Laravel Echo.
- **Seeds y pruebas**: `TenantSeeder` genera un tenant demo; Pest incluye `tests/Feature/Api/TenantApiTest.php` para validar el middleware multi-tenant.

### Panel SPA (Vue 3 + Pinia)

- Vista principal con métricas (Panel) y módulos dedicados: **Miembros**, **Planes**, **Pagos** (con formulario manual) y **Alertas** en tiempo real.
- Los componentes usan los stores Pinia para leer/escribir contra `/api/*` y comparten breadcrumbs, navegación y estado del tenant.
- Las vistas ya están localizadas en español latino y listas para extender con formularios modales, tablas paginadas o integraciones Stripe.

> Después de `composer install` recuerda ejecutar `php artisan migrate --seed` y `npm install` para disponer de toda la infraestructura recién agregada.
