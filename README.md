# ProRed — Sitio web

Sitio institucional de **ProRed** (fibra óptica, Concepción — Junín, Perú): planes, empresas, cobertura, pagos y **Libro de Reclamaciones**.

## Stack

- **[Eleventy 3](https://www.11ty.dev/)** + Nunjucks → HTML estático en `dist/`
- **PHP 8.1+ / MySQL** → API y panel del Libro de Reclamaciones (hosting cPanel)
- CSS/JS vanilla (sin bundler)

## Requisitos

- Node.js 18+ (en Windows usar `npm.cmd`)
- PHP 8.1+ y MySQL solo para el backend del libro (no hace falta en local para el build)

## Comandos

```powershell
# Desarrollo (http://localhost:8080)
npm.cmd start

# Build de producción → dist/
npm.cmd run build
```

## Estructura

```
src/                 Plantillas .njk (páginas + _includes + _data/site.js)
public/              CSS, JS, imágenes, PDF, videos (se copian a dist/public/)
api/                 PHP: crear-reclamacion, constancia, session
admin/               Panel del Libro de Reclamaciones
includes/            Bootstrap PHP, auth, .htaccess (deny)
sql/schema.sql       Esquema MySQL
.env                 Secretos: BD, URL base, correo (en .gitignore; ver .env.example)
.env.example         Plantilla de variables de entorno
config.php           Cableado de configuración (lee .env; sin secretos)
sitemap.xml          Sitemap (se sirve desde la raíz)
robots.txt           Robots + sitemap
doc/                 Plan de arquitectura, spec del libro, instalación
dist/                Salida de build (no commitear; se publica)
```

## Configuración central

Editar **`src/_data/site.js`**: teléfonos, WhatsApp, menús, footer, RUC, URL canónica. Se propaga a todas las páginas.

## Despliegue

1. `npm.cmd run build`
2. Subir **el contenido de `dist/`** a la raíz del dominio (`public_html/`)
3. Crear `.env` en el servidor (copiar `.env.example` y poner credenciales reales de BD + `BASE_URL=https://tudominio.com`)
4. phpMyAdmin → importar `sql/schema.sql`
5. Backend: ver [`doc/INSTALACION_LIBRO_RECLAMACIONES.md`](doc/INSTALACION_LIBRO_RECLAMACIONES.md)

`.env` está en `.gitignore`: **nunca commitearlo** (en el servidor sí va subido, solo a la raíz del sitio).
Las credenciales se gestionan por variables de entorno — no dejarlas en el código ni en documentación.

## Documentación

| Archivo | Contenido |
|---------|-----------|
| `doc/PLAN_ARQUITECTURA.md` | Migración a Eleventy (fases 0–4) |
| `doc/ESPECIFICACION_LIBRO_RECLAMACIONES_WEB.md` | Especificación del libro |
| `doc/INSTALACION_LIBRO_RECLAMACIONES.md` | Instalación en cPanel |
