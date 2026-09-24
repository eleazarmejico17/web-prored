# Plan de implementación — Alineación de diseño (Libro + Admin)

**Estado:** ✅ **Aprobado. Fases A–E COMPLETADAS.** Verificación local: build 8/8, 0 enlaces rotos, 0 CSS inline en admin, 0 colores slate/dark en `libro.css`/`admin.css`.

## 1. Objetivo

Alinear login, panel admin y formulario público del **Libro de Reclamaciones** con el **sistema de diseño de ProRed** (`public/assets/css/styles.css`): paleta, tipografía y componentes, sin romper funcionalidad (CSRF, validaciones, multi-paso, filtros).

## 2. No objetivos

- Cambiar lógica PHP, BD, estados o API
- Rediseñar el sitio público fuera del libro
- Framework CSS o bundler
- Tema dark si choca con la marca → **tema claro corporativo**

## 3. Sistema de diseño de referencia (`styles.css`)

### Tokens

| Token | Valor |
|-------|--------|
| `--color-principal` | `#005B9F` |
| `--color-secundario` | `#E58E21` |
| `--color-hover` | `#004A7F` |
| `--color-hover-secundario` | `#CC7A1D` |
| `--color-fondo` | `#FFFFFF` |
| `--color-texto` | `#333333` |
| `--color-texto-claro` | `#666666` |
| `--sombra-suave` / `--sombra-media` | `rgba(0,91,159,…)` |
| `--transicion` | `all 0.3s ease` |
| Fuente | `'Segoe UI', Tahoma, Geneva, Verdana, sans-serif` |
| Fondo sección | `#f8f9fa` |

### Componentes

- **`.btn`**: padding `12px 30px`, **radius `50px`**, weight 600, transition
- **`.btn-primary`**: principal → hover hover + `translateY(-2px)` + sombra media
- **`.btn-secondary`**: naranja → hover `--color-hover-secundario`
- **`.btn-outline`**: borde 2px azul, relleno al hover
- **`.hero-page`**: overlay gradiente, `h1` uppercase 900, `.hero-badges` pills
- Títulos: `color: var(--color-principal)`
- Navbar: `linear-gradient(135deg, #005B9F, #0077CC)`, radio 14px

## 4. Auditoría (gap)

### Público (`libro.css`) — ✅ Fases A–B hechas

| Ítem | Antes | Ahora |
|------|--------|--------|
| Colores marca | hardcodeados | `var(--color-*)` (61 usos) |
| Hero | override propio vs `.hero-page` | solo fondo de marca; overlay/tipografía en `.hero-page` |
| Botones | radio 8px | `.btn*` pills 50px + hover corp |
| Textos slate | `#102a43` etc. | 0; `#333` / `#666` vía tokens |
| Sombras | grises | `--sombra-suave` / `--sombra-media` |
| Fondo main | `#f4f6f9` | `#f8f9fa` |

### Admin / login — Fases C–D (siguientes)

| Ítem | Actual | Target |
|------|--------|--------|
| Tema | ❌ dark slate | claro corporativo |
| Login/setup | ❌ CSS inline | `admin.css` |
| Fuente admin | ❌ system-ui | Segoe UI |
| Acentos KPI | ❌ cian | marca |

## 5. Fases

### Fase A — Tokens públicos (libro) ✅
1. `libro.css` usa `var(--color-*)` de `styles.css` (sin redeclarar divergentes).
2. Eliminar slate → `#333` / `#666`.
3. Fondo main `#f8f9fa`.

### Fase B — Hero y botones públicos ✅
1. Mantener `hero-page` + `hero-badges`; alinear markup a otras subpáginas.
2. Quitar background/padding custom que choque con `.hero-page`.
3. Mapear CTA a `.btn` / `.btn-primary` / `.btn-secondary` / `.btn-outline`.
4. Hover: `--transicion`, `translateY(-2px)`, `--sombra-media`.

**Archivos A–B:** `public/assets/css/libro.css`, `src/libro-reclamaciones.njk`

### Fase C — Admin tema claro ✅
Variables claras, header gradiente marca, cards blancas, inputs focus corp, tags semánticos sobre fondo claro.

### Fase D — Login/setup sin CSS inline ✅
Extraer estilos a `admin.css`; misma estética clara.

### Fase E — Verificación ✅
Build limpio, checklist visual local, 0 enlaces rotos, smoke filtros/form (heurísticas PHP; smoke en vivo pendiente deploy cPanel).

## 6. Archivos

| Archivo | Acción |
|---------|--------|
| `doc/PLAN_DISENO_LIBRO_ADMIN.md` | Este plan |
| `public/assets/css/libro.css` | Fase A+B |
| `src/libro-reclamaciones.njk` | Fase B |
| `admin/assets/admin.css` | Fase C+D |
| `admin/login.php`, `admin/setup.php` | Fase D |

## 7. Criterios de aceptación

1. Colores ∈ paleta del sitio (o tokens).
2. Botones del libro/admin → radio 50px (o hereda `.btn`).
3. Login/setup sin `<style>` inline (Fase D).
4. Admin tema claro (Fase C).
5. Hero sin conflicto con `.hero-page::before`.
6. Build OK + 0 enlaces rotos.

## 8. Riesgos

| Riesgo | Mitigación |
|--------|------------|
| Conflicto hero | No duplicar overlay |
| Romper multi-paso | Solo CSS, no estructura JS |
| Regresión en otros sitios | No editar `styles.css` salvo añadir clases |

## 9. Historial

- Plan redactado y **aprobado** por el usuario.
- **Fases A y B (público) COMPLETADAS:** `libro.css` con 61 `var(--color-*)`, 0 colores slate, hero solo fondo de marca bajo `.hero-page`, botones `.btn`/`.btn-primary`/`.btn-outline`/`.btn-secondary` (pills 50px + hover corp), main `#f8f9fa`, `site.version` → `3.1`.
- **Fase C (admin tema claro) COMPLETADA:** `admin/assets/admin.css` reescrito con tokens de marca, header gradiente `#005B9F→#0077CC`, cards blancas, tags semánticos claros, botones pills, fuente Segoe UI.
- **Fase D (login/setup) COMPLETADA:** `<style>` inline eliminado de `login.php` y `setup.php`; usan `assets/admin.css` + clases `auth-body`/`auth-card`.
- **Fase E (verificación) COMPLETADA:** build 8/8, **0 enlaces rotos**, 0 CSS inline en admin, 0 dark/slate en `libro.css`/`admin.css`, heurísticas PHP (10 archivos, braces balanceados). Smoke en vivo de filtros/form pendiente deploy cPanel (sin PHP/MySQL local).
- **Plan de diseño Libro+Admin cerrado.**
