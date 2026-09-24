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
| `api/` | `crear-reclamacion.php`, `constancia.php`, `session.php` |
| `admin/` | Panel (`login`, `index`, `reclamacion`, `setup`, `assets/`) |
| `includes/` | Bootstrap, auth, `.htaccess` (deny) |
| `sql/schema.sql` | Esquema de BD |
| `config.php.example` | Plantilla de configuración |

## 3. Desplegar en cPanel

1. Subir **el contenido de `dist/`** a `public_html/` (o la raíz del dominio).
2. phpMyAdmin → crear BD + usuario → importar `sql/schema.sql`.
3. Copiar `config.php.example` → `config.php` (en la raíz) y completar:
   - `db.*` (host, name, user, pass)
   - `base_url` (ej. `https://proredperu.com`)
   - `mail` opcional (SMTP del hosting usa `mail()` de PHP si `enabled: true` y `from` válido)
4. Abrir `https://DOMINIO/admin/setup.php` → crear el primer usuario (mín. 10 caracteres).
5. **Borrar o proteger** `admin/setup.php` en el servidor (ya no es necesario).
6. Verificar:
   - `https://DOMINIO/libro-reclamaciones/` → formulario en 5 pasos
   - Enviar un reclamo de prueba → código `LR-AAAA-000001` + constancia
   - `https://DOMINIO/admin/` → login y listado

## 4. Seguridad (checklist)

- [ ] `config.php` fuera del repositorio (`.gitignore`) y con contraseña fuerte de BD
- [ ] `includes/.htaccess` → `Require all denied` (viene en el repo)
- [ ] `admin/setup.php` eliminado tras crear el admin
- [ ] HTTPS forzado (`.htaccess` del hosting o AutoSSL)
- [ ] Panel admin solo con usuario propio; no compartir
- [ ] Copias de BD periódicas (cPanel → Backup)

## 5. Notas legales

- Formato de respuesta: **15 días hábiles** (D.S. 011-2011-PCM / Indecopi).
- Reclamos de telecomunicaciones pueden requerir **OSIPTEL** → aviso visible en la página; validación legal pendiente.
- La constancia es imprimible (`Imprimir / Guardar PDF` del navegador); no se genera PDF server-side.

## 6. Estructura de tablas

Ver `sql/schema.sql`:

- `reclamaciones` — registro (código único `LR-YYYY-NNNNNN`)
- `reclamacion_historial` — auditoría por reclamación
- `admin_access_log` — login/logout del panel
- `reclamaciones_contador` — correlativo transaccional por año
- `admin_users` — panel (`password_hash`)
- `rate_limit` — máximo de envíos por IP/hora (default 5)

Si la BD ya existe y se actualiza el código, volver a importar `sql/schema.sql` es seguro (`IF NOT EXISTS`).
