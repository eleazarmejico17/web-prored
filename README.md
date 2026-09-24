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
config.php.example   Plantilla de configuración (copiar → config.php en el server)
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
3. Backend: ver [`doc/INSTALACION_LIBRO_RECLAMACIONES.md`](doc/INSTALACION_LIBRO_RECLAMACIONES.md)

`config.php` (credenciales) está en `.gitignore`: nunca commitearlo.

## Documentación

| Archivo | Contenido |
|---------|-----------|
| `doc/PLAN_ARQUITECTURA.md` | Migración a Eleventy (fases 0–4) |
| `doc/ESPECIFICACION_LIBRO_RECLAMACIONES_WEB.md` | Especificación del libro |
| `doc/INSTALACION_LIBRO_RECLAMACIONES.md` | Instalación en cPanel |
