# DMusic Backend

## 🌐 Despliegue

- Librería músical en producción: https://dmusic-front.vercel.app/

## Descripción general

DMusic es una API construida sobre Laravel 12, con autenticación por tokens usando Sanctum y un modelo de datos orientado a artistas, canciones y usuarios con biblioteca personal. La estructura actual incluye rutas públicas para consultar catálogo y rutas autenticadas para gestionar favoritos y reproducción.

## Stack tecnológico

- PHP 8.2
- Laravel 12
- Laravel Sanctum
- SQLite por defecto en local
- PostgreSQL en Docker
- Composer
- PHPUnit para pruebas

## Funcionalidades principales

- Registro e inicio de sesión de usuarios
- Generación de tokens de acceso con Sanctum
- Consulta de canciones disponibles
- Consulta de artistas
- Búsqueda parcial por título de canción
- Reproducción de una canción o del repertorio de un artista
- Gestión de canciones favoritas por usuario
- Biblioteca personal asociada al usuario autenticado

## Estructura del proyecto

```text
app/
├── Http/
│   └── Controllers/
│       ├── AuthController.php
│       ├── ArtistController.php
│       ├── SongController.php
│       └── Controller.php
├── Models/
│   ├── Artist.php
│   ├── Song.php
│   ├── User.php
│   └── Playlist.php
config/
database/
├── migrations/
├── seeders/
public/
routes/
├── api.php
├── web.php
└── console.php
tests/
```

## Modelo de datos

El backend usa relaciones Eloquent para modelar la lógica musical:

- `User`: usuario autenticado, tiene acceso a tokens y a su biblioteca de canciones.
- `Artist`: artista con varias canciones.
- `Song`: canción vinculada a un artista mediante `id_artist`.
- `users_songs`: tabla pivote para guardar canciones favoritas del usuario.
- `Playlist`: modelo presente en la estructura, pero todavía no tiene endpoints ni lógica completa implementada.

## Rutas API

Todas las rutas de la API se exponen bajo el prefijo `/api`.

### Públicas

| Método | Ruta | Descripción |
|---|---|---|
| `POST` | `/api/dmusic/register` | Registra un usuario nuevo |
| `POST` | `/api/dmusic/login` | Inicia sesión y devuelve un token |
| `GET` | `/api/dmusic/get-songs` | Obtiene todas las canciones |
| `GET` | `/api/dmusic/get-artists` | Obtiene todos los artistas |
| `POST` | `/api/dmusic/search-song/{id}` | Busca canciones por coincidencia parcial en el título |

### Protegidas con autenticación

Se requiere enviar el header:

```http
Authorization: Bearer <token>
```

| Método | Ruta | Descripción |
|---|---|---|
| `POST` | `/api/dmusic/play-song/{id}` | Reproduce la canción seleccionada y el resto del listado |
| `POST` | `/api/dmusic/play-artist/{id}` | Obtiene las canciones de un artista |
| `POST` | `/api/dmusic/play-library/{id}` | Reproduce la librería del usuario con una canción destacada primero |
| `POST` | `/api/dmusic/get-favorite-songs` | Devuelve las canciones favoritas del usuario |
| `POST` | `/api/dmusic/add-favorite-song/{id}` | Añade una canción a favoritos |
| `DELETE` | `/api/dmusic/delete-favorite-song/{id}` | Elimina una canción de favoritos |

## Ejemplos de uso

### Registro

```bash
curl -X POST http://localhost:8000/api/dmusic/register \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Juan Pérez",
    "email": "juan@ejemplo.com",
    "password": "12345678"
  }'
```

### Login

```bash
curl -X POST http://localhost:8000/api/dmusic/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@ejemplo.com",
    "password": "12345678"
  }'
```

Respuesta esperada:

```json
{
  "success": true,
  "message": "Usuario autenticado correctamente",
  "access_token": "<token>",
  "token_type": "Bearer",
  "initial_name": "J"
}
```

### Obtener canciones

```bash
curl http://localhost:8000/api/dmusic/get-songs
```

### Añadir a favoritos

```bash
curl -X POST http://localhost:8000/api/dmusic/add-favorite-song/1 \
  -H "Authorization: Bearer <token>"
```

## Instalación y configuración

### 1. Requisitos del sistema

- PHP 8.1 o superior
- Node.js 18 o superior
- PostgreSQL 14 o superior
- Composer
- npm (incluido con Node.js)

### 2. Preparación del proyecto

1. Descarga el archivo ZIP del proyecto.
2. Extrae su contenido en la carpeta donde quieras instalarlo.
3. Abre una terminal y navega hasta la carpeta del proyecto:

```bash
cd /ruta/donde/extraiste/el/proyecto
```

### 3. Instalación del Backend (Laravel)

#### 3.1 Acceder al backend

```bash
cd dmusic-back
```

#### 3.2 Instalar dependencias

```bash
composer install
```

#### 3.3 Crear archivo .env

```bash
cp .env.example .env
```

#### 3.4 Configurar el archivo .env

Abre el archivo `.env` y asegúrate de que los valores principales estén así:

```env
APP_NAME=DMusic
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=dmusic_db
DB_USERNAME=postgres
DB_PASSWORD=

CORS_ALLOWED_ORIGINS=http://localhost:5173
```

Notas importantes:

- Si tu PostgreSQL tiene contraseña, colócala en `DB_PASSWORD`.
- No es necesario modificar otras variables del `.env.example` salvo que tengas requisitos especiales.
- El valor `CORS_ALLOWED_ORIGINS=http://localhost:5173` es correcto para el frontend.

#### 3.5 Generar clave de aplicación

```bash
php artisan key:generate
```

#### 3.6 Crear la base de datos

Antes de ejecutar las migraciones, asegúrate de que la base de datos `dmusic_db` exista en PostgreSQL.

Si no la tienes creada, crea la base desde pgAdmin o desde la línea de comandos con tu cliente PostgreSQL.

#### 3.7 Ejecutar migraciones y seeds

```bash
php artisan migrate --seed
```

#### 3.8 Crear enlace simbólico de almacenamiento

```bash
php artisan storage:link
```

#### 3.9 Iniciar el servidor del backend

```bash
php artisan serve
```

La API quedará disponible normalmente en:

```text
http://localhost:8000
```

### 4. Importación de datos (Canciones y Artistas)

Este script es obligatorio para que la aplicación muestre datos.

1. Abre PostgreSQL o pgAdmin (o tu cliente de base de datos preferido).
2. Conéctate usando los datos del `.env`:
   - Host: `127.0.0.1`
   - Puerto: `5432`
   - Usuario: `postgres`
   - Contraseña: la que corresponda
3. Abre el archivo `dmusic-back/script.sql`.
4. Copia todo su contenido y pégalo en una consulta nueva.
5. Ejecuta el script.
6. Verifica que las tablas `artists` y `songs` tengan registros.

## Uso con Docker

El proyecto incluye un `docker-compose.yml` con un contenedor para la app y otro para PostgreSQL.

```bash
docker compose up --build
```

Esto levanta la API y la base de datos PostgreSQL en los puertos `8000` y `5432` respectivamente.

Si es la primera vez, puede que necesites ejecutar:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

## Notas de desarrollo

- Las rutas de autenticación están protegidas con middleware `guest` para registro y login.
- Las rutas de catálogo (`songs` y `artists`) son públicas.
- La gestión de favoritos está protegida con `auth:sanctum`.
- El método `searchSong` busca por coincidencia parcial sobre el campo `title` del modelo `Song`.
- La funcionalidad de recuperación de contraseña quedó comentada y no está activa.
- El modelo `Playlist` existe, pero aún no tiene implementación de endpoints reales.

## Recomendaciones futuras

- Completar endpoints de playlists y gestión de listas de reproducción.
- Añadir validación y normalización más estricta de datos.
- Crear seeders reales de artistas y canciones.
- Ampliar la cobertura de pruebas para auth, canciones y favoritos.
- Separar flujo de reproducción y favoritos con servicios más mantenibles.

## Autor

Donato Marino
