# 📚 Proyecto de Trivia con Laravel y MongoDB

Este proyecto es una API REST para gestionar un sistema de trivias, preguntas y respuestas usando Laravel 12.09 y MongoDB 7.0.15. La autenticación se realiza mediante tokens personales usando Laravel Sanctum.

---

## 🚀 Funcionalidades Principales

- Inicio de sesión con email y contraseña
- Creación y listado de trivias
- Registro y consulta de preguntas asociadas
- Envío y consulta de respuestas
- Seguridad con autenticación por token (Sanctum)
- Documentación Swagger disponible

---

## 🧪 Tecnologías Utilizadas

| Tecnología     | Versión     |
|----------------|-------------|
| PHP            | 8.4.7       |
| Laravel        | 12.09       |
| MongoDB        | 7.0.15      |
| Laravel Sanctum| Integrado   |
| Swagger        | L5 Swagger  |
| Docker         | Compatible  |

---

## 📦 Instalación del Proyecto

1. **Clonar el repositorio:**
   ```bash
   git clone <repositorio>
   cd <nombre-del-proyecto>
   ```

2. **Levantar los contenedores:**

   - Para la API web (desde la raíz):
     ```bash
     docker compose up -d
     ```

   - Para la base de datos MongoDB (desde `/docker-db`):
     ```bash
     cd docker-db
     docker compose up -d
     ```

3. **Acceder al contenedor de Laravel e instalar dependencias:**
   ```bash
   docker exec -it <nombre-contenedor-laravel> bash
   composer update
   ```

4. **Configurar entorno:**
   Copiar `.env.example` a `.env` y configurar conexión a MongoDB y Sanctum si es necesario.
   Ya que no es un proyecto de prueba se incluye el .env

5. **Generar clave de la app:**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones y seeders:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

---

## ⚙️ Contenido de los docker-compose

### Web (Laravel con Sail)
- Basado en imagen `sail-8.4/app`
- Exposición de puertos: `80` y `5173`
- Volumen principal montado: `.:/var/www/html`
- Variables para debug y entorno Laravel
- Red: `bridge`

### Base de datos MongoDB
- Imagen: `mongo:7.0.15`
- Autenticación activada con usuario `root`
- Puerto expuesto: `27117 -> 27017`
- Volumen local: `~/docker-db/mongo-70/data:/data/db`

---

## 🔐 Seguridad

- **Autenticación:** Laravel Sanctum
- **Protección de rutas:** Middleware `auth:sanctum`
- **Tokens personales:** Generados con `createToken()` al iniciar sesión

---

## 🧪 Uso en Postman

1. Hacer un **POST** a `/api/login` con:
   ```json
   {
     "email": "test@email.com",
     "password": "tu_password"
   }
   ```

2. Copiar el token de respuesta.

3. En todas las demás peticiones **protegidas**, usar el token en el encabezado:

   ```
   Authorization: Bearer TU_TOKEN
   ```

---

## 📄 Endpoints

### 🔓 Autenticación

| Método | Ruta     | Descripción      |
|--------|----------|------------------|
| POST   | /login   | Inicia sesión y devuelve un token |

### 🧠 Trivias

| Método | Ruta              | Descripción             |
|--------|-------------------|-------------------------|
| POST   | /trivias/crear    | Crear una trivia        |
| GET    | /trivias/listar   | Listar trivias          |

### ❓ Preguntas

| Método | Ruta               | Descripción               |
|--------|--------------------|---------------------------|
| POST   | /preguntas/crear   | Crear una o varias preguntas |
| GET    | /preguntas/listar  | Listar preguntas          |

### ✅ Respuestas

| Método | Ruta                | Descripción               |
|--------|---------------------|---------------------------|
| POST   | /respuestas/crear   | Enviar respuestas          |
| GET    | /respuestas/listar  | Listar respuestas enviadas |

> ⚠️ Todos los endpoints excepto `/login` requieren autenticación por token.

---

## 📑 Swagger (Documentación API)

- Disponible en: [http://localhost:8000/documentation](http://localhost:8000/documentation)

---

## 🌐 Acceso al Proyecto

- API: [http://localhost:8000](http://localhost:8000)
- Swagger: [http://localhost:8000/documentation](http://localhost:8000/documentation)

---

## ✅ Notas Finales

- Asegúrate de tener configurado correctamente MongoDB en `.env`
- Si usas Docker, asegúrate de que los contenedores estén corriendo correctamente