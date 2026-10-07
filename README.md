# DMusic Backend

## 🌐 Despliegue

- API en producción: https://dmusic-front.vercel.app/

API REST desarrollado con Laravel para un sistema de música con autenticación de usuarios, catálogo de canciones, artistas y favoritos. El proyecto está pensado para servir como base para una app tipo streaming o reproductor musical, con endpoints públicos y rutas protegidas por token con Sanctum.

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

### Requisitos

- PHP 8.2+
- Composer
- Node.js y npm (por el frontend Vite y recursos frontend del proyecto)
- Base de datos SQLite para desarrollo local o PostgreSQL para Docker

### 1. Clonar el proyecto

```bash
git clone <url-del-repositorio>
cd dmusic-back
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

Copia el ejemplo:

```bash
cp .env.example .env
```

Si trabajas en local con SQLite, la configuración por defecto del proyecto ya está preparada para ello. Si no se genera la clave de la app, haz lo siguiente:

```bash
php artisan key:generate
```

### 4. Ejecutar migraciones

```bash
php artisan migrate
```

### 5. Iniciar el servidor

```bash
php artisan serve
```

La API quedará disponible normalmente en:

```text
http://localhost:8000
```

## Uso con Docker

El proyecto incluye un `docker-compose.yml` con un contenedor para la app y otro para PostgreSQL.

```bash
docker compose up --build
```

Esto levanta la API y una base de datos PostgreSQL en el puerto `5432`.

## Pruebas

El proyecto incluye PHPUnit y Laravel Testbench-style setup. Para ejecutar la suite:

```bash
php artisan test
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

## Licencia

Este proyecto se distribuye bajo la licencia MIT.
