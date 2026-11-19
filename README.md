# DMusic Backend

## 🌐 Despliegue

- Librería musical en producción: https://dmusic-front.vercel.app/

## Descripción general

DMusic es una API construida sobre Laravel 12, con autenticación por tokens usando Sanctum y un modelo de datos orientado a artistas, canciones y usuarios con biblioteca personal. La estructura actual incluye rutas públicas para consultar catálogo y rutas autenticadas para gestionar favoritos y reproducción.

## Stack tecnológico

- PHP 8.2
- Laravel 12
- Laravel Sanctum
- PostgreSQL (configurado en `.env.example`)
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
│       ├── PlaylistController.php
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
| `GET` | `/api/dmusic/search-song/{query}` | Busca canciones por coincidencia parcial en el título |

### Protegidas con autenticación

Se requiere enviar el header:

```http
Authorization: Bearer <token>
```

| Método | Ruta | Descripción |
|---|---|---|
| `GET` | `/api/dmusic/play-song/{id}` | Obtiene el listado de canciones con la seleccionada primero |
| `GET` | `/api/dmusic/play-artist/{id}` | Obtiene las canciones de un artista |
| `GET` | `/api/dmusic/play-library/{id}` | Obtiene la librería con una canción destacada primero |
| `GET` | `/api/dmusic/get-favorite-songs` | Devuelve las canciones favoritas del usuario |
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

- PHP 8.2 o superior con la extensión `pdo_pgsql`
- PostgreSQL 14 o superior
- Composer
- Para compilar los recursos con Vite: Node.js 20.19+ o 22.12+ y npm

### 2. Preparación del proyecto

Descarga o clona el proyecto y abre una terminal en su carpeta:

```bash
cd /ruta/al/proyecto/dmusic-back
```

### 3. Instalación del Backend (Laravel)

#### 3.1 Instalar dependencias

```bash
composer install
```

#### 3.2 Crear archivo .env

Si todavía no existe `.env`, créalo a partir del ejemplo. No sobrescribas un archivo existente.

```bash
cp .env.example .env
```

#### 3.3 Configurar el archivo .env

Abre el archivo `.env` y asegúrate de que los valores principales estén así:

```env
APP_NAME=DMusic
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

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
- Ajusta `CORS_ALLOWED_ORIGINS` a la dirección desde la que se ejecuta el frontend.

#### 3.4 Generar clave de aplicación

```bash
php artisan key:generate
```

Genera la clave solo si todavía no tienes una; no la cambies en una instalación existente.

#### 3.5 Crear la base de datos

Antes de ejecutar las migraciones, asegúrate de que la base de datos `dmusic_db` exista en PostgreSQL.

Si no la tienes creada, crea la base desde pgAdmin o desde la línea de comandos con tu cliente PostgreSQL.

#### 3.6 Ejecutar migraciones

```bash
php artisan migrate
```

El seeder actual crea un usuario de prueba y no carga canciones ni artistas. No es necesario ejecutarlo para instalar el proyecto.

#### 3.7 Crear enlace simbólico de almacenamiento

```bash
php artisan storage:link
```

#### 3.8 Iniciar el servidor del backend

```bash
php artisan serve
```

La API quedará disponible normalmente en:

```text
http://localhost:8000
```

### 4. Importación de datos (Canciones y Artistas)

Para cargar el catálogo de ejemplo, ejecuta el script después de las migraciones.

1. Abre PostgreSQL o pgAdmin (o tu cliente de base de datos preferido).
2. Conéctate usando los datos del `.env`:
   - Host: `127.0.0.1`
   - Puerto: `5432`
   - Usuario: `postgres`
   - Contraseña: la que corresponda
3. Abre el archivo `script.sql` desde la carpeta del proyecto.
4. Copia todo su contenido y pégalo en una consulta nueva.
5. Asegúrate de que la consulta está conectada a la base `dmusic_db` y ejecuta el script una sola vez sobre una base recién creada. Las canciones usan IDs fijos de artistas; volver a ejecutar el script puede crear duplicados o asociaciones incorrectas.
6. Verifica que las tablas `artists` y `songs` tengan registros.

## Uso con Docker

El `Dockerfile` instala PHP, las extensiones de PostgreSQL y MySQL y las dependencias de Composer. El `docker-compose.yml` también define una base de datos PostgreSQL local.

La configuración de Compose necesita revisión antes de utilizarla: publica el puerto `80` del contenedor, mientras que la aplicación escucha en el `8000`. Además, el montaje del proyecto sobre `/var/www` puede ocultar las dependencias instaladas en la imagen. La contraseña del archivo Compose es solo de ejemplo y no debe usarse en producción.

Para un despliegue con Render se utiliza el `Dockerfile`; no es necesario usar Compose si PostgreSQL está alojado por separado. Configura en Render las variables de entorno de la aplicación y la conexión a la base de datos, y ejecuta las migraciones contra la base correcta. El servidor `php artisan serve` del Dockerfile está orientado al desarrollo; para producción conviene utilizar un servidor como Nginx con PHP-FPM.

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
