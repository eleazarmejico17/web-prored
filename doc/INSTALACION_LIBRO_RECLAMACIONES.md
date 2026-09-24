# Instalación — Libro de Reclamaciones (PHP + MySQL)

Guía para publicar el Libro de Reclamaciones en hosting compartido (cPanel / PHP 8.1+).

## 1. Requisitos

- PHP **8.1+** (PDO MySQL habilitado; `mbstring`; `session`)
- MySQL 5.7+ o MariaDB 10.3+
- HTTPS activo en el dominio
- `AllowOverride All` (o equivalente) para que `includes/.htaccess` aplique

## 2. Build del sitio

```powershell
npm.cmd run build
```

La salida queda en `dist/` e **incluye**:

| Ruta en dist/ | Contenido |
|---------------|-----------|
| `*.html`, `*/index.html` | Páginas del sitio (incluye `libro-reclamaciones/`) |
| `public/` | CSS, JS, imágenes |
| `sitemap.xml` | Sitemap |
| `robots.txt` | Robots de rastreo |
| `api/` | `crear-reclamacion.php`, `pdf.php`, `session.php` |
| `admin/` | Panel (`login`, `index`, `reclamacion`, `setup`, `assets/`) |
| `includes/` | Bootstrap, auth, `.htaccess` (deny) |
| `sql/schema.sql` | Esquema de BD |
| `config.php` | Cableado de configuración (lee `.env`; sin secretos) |
| `.env.example` | Plantilla de variables de entorno |

> El archivo `.env` **no va en `dist/`**: se crea directamente en el servidor (paso 3).

## 3. Desplegar en cPanel

Despliegue completo (recomendado): ejecutar `build-deploy.ps1` genera `deploy/prored-deploy-AAAAMMDD-HHmm.zip`
con la estructura final: `.htaccess` (raíz, enrutado) + `web/` (todo el sitio, con su `.htaccess` y `.env`).

1. Subir el ZIP a `public_html/` → Administrador de Archivos → **Extraer** → **borrar el ZIP**.
   (Estructura resultante: `public_html/.htaccess`, `public_html/web/*`; los demás sistemas
   (`almacen/`, `crmservicios/`, …) quedan excluidos del enrutado en el `.htaccess` raíz.)
2. phpMyAdmin → crear BD + usuario → importar `web/sql/schema.sql`.
3. Completar `public_html/web/.env` (ya viene con datos de producción; verificar):
   - `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` (datos del usuario de BD de cPanel)
   - `BASE_URL` = `https://tudominio.com` (sin barra final; links de constancia y correo)
   - `MAIL_ENABLED=true` + `MAIL_FROM` solo si se quiere copia automática por correo
   - `config.php` ya está en `web/`: no requiere edición (lee el `.env`)
4. Abrir `https://DOMINIO/admin/setup.php` → crear el primer usuario (mín. 10 caracteres).
5. **Borrar o proteger** `admin/setup.php` en el servidor (ya no es necesario).
6. Verificar:
   - `https://DOMINIO/libro-reclamaciones/` → formulario en 5 pasos
   - Enviar un reclamo de prueba → código `LR-AAAA-000001` + constancia
   - `https://DOMINIO/admin/` → login y listado
   - `https://almacen.DOMINIO/` (y el resto de sistemas) → siguen respondiendo igual
   - `https://DOMINIO/web/` → **403** (carpeta interna no visible)

## 4. Seguridad (checklist)

- [ ] `.env` con credenciales reales **fuera del repositorio** (`.gitignore`) y contraseña fuerte de BD
- [ ] `.htaccess` bloquea `.env*` y `config.php` (viene en el build de producción)
- [ ] `includes/.htaccess` → `Require all denied` (viene en el repo)
- [ ] `admin/setup.php` eliminado tras crear el admin
- [ ] HTTPS forzado (`.htaccess` del hosting o AutoSSL)
- [ ] Panel admin solo con usuario propio; no compartir
- [ ] Copias de BD periódicas (cPanel → Backup)

## 5. Notas legales

- Formato de respuesta: **15 días hábiles** (D.S. 011-2011-PCM / Indecopi).
- Reclamos de telecomunicaciones pueden requerir **OSIPTEL** → aviso visible en la página; validación legal pendiente.
- La constancia es un **PDF server-side** (`api/pdf.php?codigo=…&token=…`, enlace en el paso 5 y en el correo).

## 6. Estructura de tablas

Ver `sql/schema.sql`:

- `reclamaciones` — registro (código único `LR-YYYY-NNNNNN`)
- `reclamacion_historial` — auditoría por reclamación
- `admin_access_log` — login/logout del panel
- `reclamaciones_contador` — correlativo transaccional por año
- `admin_users` — panel (`password_hash`)
- `rate_limit` — máximo de envíos por IP/hora (default 5)

Si la BD ya existe y se actualiza el código, volver a importar `sql/schema.sql` es seguro (`IF NOT EXISTS`).
