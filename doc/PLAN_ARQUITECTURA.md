# Plan de Mejora de Arquitectura — ProRed Web

> **Objetivo:** centralizar las páginas y extraer los bloques repetitivos (navbar, footer, botones flotantes, head, scripts) en componentes reutilizables, eliminando la duplicación y las inconsistencias actuales, sin perder las URLs existentes (SEO).

---

## 1. Diagnóstico del estado actual

### 1.1 Stack
- Sitio **estático**: HTML + CSS + JavaScript vanilla, **sin herramienta de build** ni dependencias (`package.json` inexistente).
- 7 páginas HTML, cada una con su carpeta (`index.html` raíz + 6 carpetas con `index.html`).
- Assets compartidos en `public/assets/` (CSS, JS, imágenes, videos, PDF).
- Documentación y auxiliares en `doc/` (`sitemap.xml`, `TC-duomax.txt`, especificación del libro de reclamaciones).

### 1.2 Duplicación detectada

| Bloque repetido | Veces | Líneas aprox. c/u | Diferencias entre copias |
|---|---|---|---|
| `<head>` (meta, CSS, favicon) | 7 | ~12 | Título/meta por página + CSS propio |
| Navbar + overlay | 7 | ~24 | Solo cambia la clase `active` y, en 2 casos, el CTA de WhatsApp |
| Footer completo | 7 | ~58 | `planes` sin redes sociales ni razón social; enlace autorreferencial en `politica` |
| Botón flotante WhatsApp | 7 | ~3 | `internet-empresas` con otro número; `cobertura` con **markup distinto** (clases Tailwind + `wa.me`) |
| Notificación de pago flotante | 1 | ~10 | Solo en `index.html` |
| Bloque completo de **tabs de planes** (Internet/IPTV/Dúo con videos y tarjetas) | 2 | **~330** | Copia casi idéntica en `index.html` y `planes/index.html` |
| Scripts base (`script.js` + `cookies.js`) | 7 | 2 | — |
| CTA WhatsApp genérico en navbar | 7 | 1 | — |

**Total estimado de código duplicado:** ~700–800 líneas HTML.

### 1.3 Inconsistencias y errores detectados

| # | Problema | Ubicación |
|---|---|---|
| 1 | Navbar de `cobertura` usa el número de WhatsApp **51935444206** (empresas) en el botón "Contratar"; el resto usa **51991445527** | `cobertura/index.html:35` |
| 2 | Navbar de `internet-empresas` cambia la etiqueta del CTA a "Solicitar Cotización" (decisión de negocio válida, pero debe ser configurable) | `internet-empresas/index.html:35` |
| 3 | El flotante de WhatsApp en `cobertura` usa clases Tailwind (`fixed bottom-5 right-5...`) y enlace genérico `wa.me`, ignorando la clase compartida `.whatsapp-float` | `cobertura/index.html:263` |
| 4 | Footer de `planes` **no** muestra redes sociales ni la línea "Razón Social … RUC" (las demás páginas sí) | `planes/index.html` footer |
| 5 | Footer de `politica-de-privacidad` se enlaza a sí mismo con `index.html` en vez de `../politica-de-privacidad/` | `politica-de-privacidad/index.html:243` |
| 6 | Script inline `copiarAlPortapapeles()` dentro del HTML en vez de un archivo `.js` | `realizar-pagos/index.html:401-418` |
| 7 | **JS huérfanos** (no referenciados por ninguna página): `login.js`, `politicas.js` | `public/assets/js/` |
| 8 | Versionado de assets desincronizado: `styles.css?v=2.6` vs `?v=2.5` en el resto, `pagos.css?v=2.6.2`, etc. | Todas las páginas |
| 9 | 3 números de WhatsApp en circulación: `991445527` (general), `935444206` (empresas), `975366197` (cotización dedicado en index) — centralizables en una config | Múltiples |
| 10 | `sitemap.xml` y `TC-duomax.txt` fueron movidos a `doc/`; el sitemap debería servirse desde la raíz del sitio para SEO | `doc/sitemap.xml` |
| 11 | ~330 líneas de tabs de planes duplicadas entre inicio y `/planes/` (cualquier cambio de precio toca 2 archivos) | `index.html` + `planes/index.html` |

### 1.4 Impacto de no cambiar
- Actualizar un precio, teléfono o enlace exige editar **hasta 3–7 archivos** (y ~50 enlaces de WhatsApp).
- Cualquier corrección de footer/nav puede olvidarse en alguna página (ya ocurrió: casos 1, 3, 4, 5).
- El bloque de planes duplicado garantiza divergencia futura de precios.

---

## 2. Opciones de arquitectura

### Opción A — Componentes JavaScript (sin build)
Los componentes se renderizan en cliente: cada página deja marcadores y un `components.js` inyecta navbar/footer/flotantes desde una **única configuración**.

```
<!-- en cada página -->
<div data-component="navbar" data-page="planes"></div>
<div data-component="footer"></div>
<div data-component="floats"></div>
<script src="public/assets/js/components.js"></script>
```

| ✔️ Ventajas | ❌ Desventajas |
|---|---|
| Cero herramientas; sigue subiendo HTML tal cual | Parpadeo inicial (FOUC): navbar/footer aparecen tras cargar JS |
| Cambios centralizados al instante | SEO/`no-JS`: los enlaces del nav/footer no están en el HTML inicial |
| Esfuerzo bajo (~½ día) | La lógica de menú móvil (`script.js`) debe coordinarse con la inyección |

### Opción B — Build con plantillas: **Eleventy (11ty) + Nunjucks** ✅ **DECIDIDA**
Las páginas dejan de ser HTML "sueltos" y pasan a ser **plantillas** con un layout base; el build genera exactamente la misma estructura de HTML final que hoy (estático, sin JS obligatorio).

```
src/
  _layouts/base.njk          ← html, head, nav, floats, footer, scripts
  _components/
    navbar.njk
    footer.njk
    whatsapp-float.njk
    payment-notification.njk
    planes-tabs.njk          ← macro: el bloque de planes duplicado, 1 sola copia
  _config/site.njk           ← teléfonos, WhatsApp, dirección, redes, año, menús
  pages/
    index.njk                → index.html
    planes.njk               → planes/index.html
    nosotros.njk             → nosotros/index.html
    internet-empresas.njk    → internet-empresas/index.html
    cobertura.njk            → cobertura/index.html
    realizar-pagos.njk       → realizar-pagos/index.html
    politica-de-privacidad.njk → politica-de-privacidad/index.html
public/                      → assets pasados sin tocar (misma ruta final)
dist/                        → salida de build (esto es lo que se publica)
```

| ✔️ Ventajas | ❌ Desventajas |
|---|---|
| Centralización **real** (SSR en build, sin FOUC) | Requiere Node.js local y `npm run build` antes de publicar |
| HTML final idéntico al actual → URLs y SEO intactos | Cambia el flujo de despliegue: subir `dist/` en vez del repo |
| Bloque de planes en 1 sola copia | Curva de aprendizaje mínima de Nunjucks (templates tipo Jinja) |
| Versionado/cache-busting automático opcional | — |

### Opción C — Includes del servidor (PHP / SSI)
`<!--#include virtual="/includes/navbar.html" -->` o `<?php include('navbar.php'); ?>`.

| ✔️ Ventajas | ❌ Desventajas |
|---|---|
| Sin JS ni build | **Depende del hosting**; muchas veces obliga a cambiar la extensión `.html` → `.php` o reglas de rewrite |
| — | Riesgo de romper URLs actuales (`/planes/`, etc.) |

> **Recomendación:** **Opción B (Eleventy)** si se puede agregar el paso de build (GitHub Actions o `npm run build` al desplegar). Si el sitio debe seguir siendo "subir y ya", **Opción A** es el plan de contingencia con el mismo diseño de componentes (la capa de `site.njk` de configuración sirve para ambas).

---

## 3. Plan de implementación por fases (recomendado: Opción B)

### Fase 0 — Normalización previa (corrección de bugs, sin tocar arquitectura)
Arregla las inconsistencias **antes** de extraer componentes, para que el componente copie la versión correcta.

| # | Tarea | Archivos |
|---|---|---|
| 0.1 | Unificar el botón "Contratar" del navbar de cobertura al número general `991445527` (confirmar con negocio) | `cobertura/index.html` |
| 0.2 | Reemplazar el flotante Tailwind de cobertura por el estándar `.whatsapp-float` | `cobertura/index.html` |
| 0.3 | Completar footer de `planes` (redes sociales + línea Razón Social/RUC) | `planes/index.html` |
| 0.4 | Corregir enlace autorreferencial del footer en `politica` | `politica-de-privacidad/index.html` |
| 0.5 | Mover `copiarAlPortapapeles()` a un JS (nuevo `pagos.js` o a `script.js`) | `realizar-pagos/index.html` |
| 0.6 | Verificar y eliminar `login.js` y `politicas.js` si siguen sin usarse | `public/assets/js/` |
| 0.7 | Unificar el versionado de assets a un solo número (ej. `?v=3.0`) | 7 HTML |
| 0.8 | Definir catálogo de números: general / empresas / cotización dedicado (quién usa cuál) | Config futura |

**Criterio de aceptación:** las 7 páginas se ven y funcionan igual que antes, sin errores de consola.

### Fase 1 — Configuración central
- Crear `src/_config/site.njk` (o `site.config.json` si Opción A) con:
  - `phones`, `whatsapp.general`, `whatsapp.empresas`, `whatsapp.dedicado`
  - Dirección, horarios, redes sociales, RUC, razón social, año copyright
  - `navItems[]` (href, label, id) y `footerLinks[]`
  - Número de teléfono por página para el `active` del navbar

### Fase 2 — Componentes y layout ✅ COMPLETADA
**Nota:** el layout vive en `src/_includes/base.njk` (Eleventy resuelve `layout:` desde el directorio de includes; se descartó `_layouts/`).

1. **`base.njk`** ✅ — head (title/description por página), CSS base + `extraCss[]`/`cdnCss[]`, navbar, `{{ content }}`, footer, floats opcionales, scripts base + `extraJs[]`/`cdnJs[]`.
2. **`navbar.njk`** ✅ — menú desde `site.navItems[]`, `active` desde `navId`, CTA configurable por página (`navCtaLabel`, `navWhatsapp`, `navCtaText`), overlay y menú móvil.
3. **`footer.njk`** ✅ — 4 secciones canónicas + redes (`site.social`) + horarios (`site.schedule`) + libro de reclamaciones (enlace a `/libro-reclamaciones/`) + footer-bottom con RUC.
4. **`whatsapp-float.njk`** ✅ (override `floatWhatsapp`) y **`payment-notification.njk`** ✅ (solo si `showPaymentNotification: true`).
5. **`planes-tabs.njk` (macro)** ✅ — ~330 líneas en **una sola copia**; uso: `{{ planesTabs(pathPrefix, site.whatsapp.general) }}` (las macros no heredan contexto: prefijo y WhatsApp se pasan como argumentos).
6. Rutas de salida preservadas ✅ — smoke test verificó `public/...` (raíz), `../public/...` (subcarpeta), `active` de navbar y teléfonos desde `site.js`; smoke tests eliminados tras validar.
7. **`tabs.js`** — lógica de tabs extraída de la duplicación `inicio.js`/`planes.js` (el bloque duplicado se elimina de ambos al migrar en Fase 3).

### Fase 3 — Migración de páginas (una a una)
Orden por riesgo creciente:

1. `politica-de-privacidad` (página estática, sin JS propio) — valida layout base.
2. `realizar-pagos` — mueve el script inline a `pagos.js`.
3. `nosotros` — agrega `sliderclient.css` + `nosotros.js`.
4. `internet-empresas` — CTA navbar parametrizado (`Solicitar Cotización`).
5. `cobertura` — Leaflet CDN + float unificado.
6. `planes` — usa `planes-tabs.njk`; modales TyC.
7. `index` — el más complejo (partículas, tabs duplicados, payment-notification, satelital/dedicado).

**Tras cada página:** verificar visualmente en desktop/móvil, enlaces y CTA WhatsApp.

### Fase 4 — Limpieza y validación final
- Eliminar HTML duplicado y scripts huérfanos.
- Verificar `sitemap.xml`: debe servirse desde la **raíz** del sitio (hoy está en `doc/`); revisar que las URLs listadas sigan existiendo.
- Chequeo de enlaces rotos + validación HTML + prueba responsive + revisión de títulos/meta por página.
- Opcional: cache-busting por hash en build; `lazy-load` en videos `.webm` y pesadas imágenes (>1 MB).

---

## 4. Riesgos y mitigación

| Riesgo | Mitigación |
|---|---|
| Cambiar URLs (SEO) | Configurar las plantillas para emitir exactamente `planes/index.html`, `cobertura/index.html`, …; verificar tras build |
| Olvidar el paso de build al desplegar | Documentar `npm run build` + opcional GitHub Action que construye y publica `dist/` |
| Regresión visual al extraer componentes | Fase 0 primero; migrar página a página comparando con la original |
| Hosting incompatible con build | Alternativa Opción A (componentes JS) con el mismo modelo de `site` config |

## 5. Esfuerzo estimado

| Fase | Estimación |
|---|---|
| Fase 0 — Normalización | 2–3 h |
| Fase 1–2 — Config + componentes | 4–6 h |
| Fase 3 — Migración de 7 páginas | 6–8 h |
| Fase 4 — Validación y limpieza | 2–3 h |
| **Total** | **~1.5 días** |

---

## 6. Próximos pasos (decisión tomada)
1. **Opción elegida: B — Eleventy + Nunjucks.** ✅
2. **Navbar de cobertura:** usar el número general **991445527** (igual que el resto). ✅
3. **Aprobadas para ejecución inmediata: Fase 0 y Fase 1.** Las Fases 2–4 quedan pendientes de aprobación posterior.

### Fase 0 — Checklist ✅ COMPLETADA
- [x] 0.1 `cobertura/index.html` → WhatsApp del navbar cambiado a `51991445527`
- [x] 0.2 `cobertura/index.html` → flotante Tailwind reemplazado por `.whatsapp-float` estándar
- [x] 0.3 `planes/index.html` footer → añadidas redes sociales + línea "Razón Social … RUC"
- [x] 0.4 `politica-de-privacidad/index.html` → enlace del footer corregido a `../politica-de-privacidad/`
- [x] 0.5 `copiarAlPortapapeles()` movida a `public/assets/js/pagos.js` (script inline eliminado)
- [x] 0.6 JS huérfanos `login.js` y `politicas.js` eliminados (verificados sin referencias)
- [x] 0.7 Versionado unificado a `?v=3.0` en los 7 HTML (incluye `nosotros.js` y `empresas.js` sin versión)
- [x] Bonus: corregidos espacios faltantes `target="_blank"aria-label` en `index` y `nosotros`

### Fase 1 — Checklist ✅ COMPLETADA (adaptación: `src/_data/site.js` en lugar de `_config/site.njk`, forma nativa de Eleventy para datos globales)
- [x] 1.1 Creado `src/_data/site.js` con: teléfonos (general `51991445527`, empresas `51935444206`, dedicado `51975366197`), dirección, horarios, redes sociales, RUC, razón social, año
- [x] 1.2 `navItems[]`, `footerLinks[]` y `navCta` definidos en `site.js`
- [x] 1.3 Eleventy inicializado: `package.json` (scripts `build`/`start`), `.eleventy.js` (input `src` → output `dist`, passthrough `public`), `.gitignore`, Node.js 24.19.0 + `@11ty/eleventy@3.1.6` instalados
- [x] 1.4 `npm run build` verificado: copia 106 assets a `dist/public/...` manteniendo la estructura `public/assets/...` que las páginas HTML ya usan ("Wrote 0 files" = aún no hay plantillas, llega en Fase 2–3)

### Fase 2 — Checklist ✅ COMPLETADA
- [x] 2.1 `src/_includes/base.njk` — head, CSS (`preCss`/`extraCss`/`cdnCss`), navbar, content, footer, floats, scripts (`cdnJs` antes de `extraJs`)
- [x] 2.2 `src/_includes/navbar.njk` — menú desde `site.navItems`, overrides por página
- [x] 2.3 `src/_includes/footer.njk` — 4 secciones + redes + horarios + libro de reclamaciones + RUC
- [x] 2.4 `whatsapp-float.njk` (overrides `floatWhatsapp`/`floatWhatsappText`) y `payment-notification.njk`
- [x] 2.5 `planes-tabs.njk` macro (~330 líneas, 1 sola copia)
- [x] 2.6 `public/assets/js/tabs.js` extraído; smoke tests de rutas/active/teléfonos verificados y eliminados

### Fase 3 — Migración de páginas ✅ COMPLETADA
- [x] 3.1 `politica-de-privacidad.njk` — build OK, sin JS propio
- [x] 3.2 `realizar-pagos.njk` — YAML de description con `:` entrecomillado; `pagos.js`
- [x] 3.3 `nosotros.njk` — `nosotros.css` + `sliderclient.css` + `nosotros.js`
- [x] 3.4 `internet-empresas.njk` — CTA "Solicitar Cotización" + WhatsApp `51935444206` (navbar y flotante)
- [x] 3.5 `cobertura.njk` — Leaflet 1.9.4 CDN (`cdnCss`/`cdnJs`, Leaflet antes de `cobertura.js`), flotante `.whatsapp-float` general
- [x] 3.6 `planes.njk` — macro `planesTabs`; `tabs.js` + `planes.js` + `TyC.js`; lógica de tabs duplicada eliminada de `planes.js`
- [x] 3.7 `index.njk` — `pathPrefix: ""`, `preCss: inicio.css` (antes de `styles.css`), payment-notification, `network-particles.js` + `inicio.js` + `tabs.js`; tabs duplicados eliminados de `inicio.js`
- [x] 3.8 Build final: **7/7 páginas** → `dist/index.html` + 6 `dist/*/index.html`; rutas `public/` (raíz) y `../public/` (sub) verificadas; títulos/meta OK; `51975366197` solo en dedicado del index

**Fase 4 aprobada y ejecutada** — ver sección siguiente.

### Fase 4 — Limpieza y validación final ✅ COMPLETADA
| # | Tarea | Estado |
|---|---|---|
| 4.1 | Eliminar los 7 HTML originales; carpetas vacías (`cobertura/`, `planes/`, …) retiradas del repo | ✅ |
| 4.2 | `sitemap.xml` movido a la **raíz** + `addPassthroughCopy("sitemap.xml")` → `dist/sitemap.xml` | ✅ |
| 4.3 | 7/7 URLs del sitemap mapean a rutas generadas | ✅ |
| 4.4 | Chequeo en `dist/`: **331** refs locales, **0** rotas | ✅ |
| 4.5 | 0 JS huérfanos en `public/assets/js/` (11 archivos, todos referenciados) | ✅ |
| 4.6 | Títulos + meta descriptions presentes en las 7 páginas; 1× `nav-link active` por página (0 en politica, correcto); flotante + RUC + redes en todas | ✅ |
| 4.7 | (Opcional) cache-busting por hash; lazy-load en videos/pesadas imágenes — **diferido** (requiere QA visual; videos de fondo con autoplay no deben usar `loading="lazy"`) | ⏸ |

**Nota de despliegue:** publicar el contenido de `dist/` (no la raíz del repo). Build: `npm run build`.

---

## 7. Resultado final

| Métrica | Antes | Después |
|---|---|---|
| Páginas HTML sueltas en repo | 7 | 0 (solo `src/*.njk` → `dist/`) |
| Copias del bloque de planes (~330 líneas) | 2 | 1 (`planes-tabs.njk`) |
| Navbar/footer/flotantes duplicados | 7 c/u | 1 c/u (includes) |
| Teléfonos/WhatsApp en HTML | hardcodeados | `src/_data/site.js` |
| `sitemap.xml` | `doc/` | raíz + `dist/` |

**Flujo de trabajo:** editar `src/` → `npm run build` → publicar `dist/`.
