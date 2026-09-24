    # Especificación de implementación --- Libro de Reclamaciones Web

**Proyecto:** Conversión del Libro de Reclamaciones PDF a formulario web
interactivo\
**Fecha de elaboración:** 2026-09-23\
**Estado:** Especificación inicial para agente de IA/desarrollo\
**Prioridad:** Alta\
**Tecnología actual del sitio:** HTML + CSS + JavaScript nativo\
**Backend/BD:** Por definir durante la implementación

------------------------------------------------------------------------

## 1. Objetivo

Actualmente el sitio web muestra un enlace que permite al usuario
abrir/rellenar un archivo PDF del Libro de Reclamaciones.

El objetivo es reemplazar ese flujo por un **Libro de Reclamaciones
virtual mediante formulario web**, conectado a una base de datos.

El sistema debe permitir:

1.  Presentar una queja o reclamo desde la página web.
2.  Validar los datos antes de registrar la solicitud.
3.  Generar un código/número único y correlativo para cada Hoja de
    Reclamación.
4.  Impedir duplicación de códigos mediante restricciones de base de
    datos.
5.  Guardar toda la información de la Hoja de Reclamación.
6.  Mostrar al consumidor una constancia de registro.
7.  Enviar o permitir obtener una copia de la Hoja de Reclamación.
8.  Permitir al personal autorizado consultar y gestionar las
    reclamaciones.
9.  Registrar las acciones y respuestas realizadas por el proveedor.
10. Mantener trazabilidad e historial.
11. Aplicar medidas adecuadas para proteger los datos personales.

> **IMPORTANTE:** Este documento es una especificación técnica y
> funcional. Los requisitos legales deben ser validados antes de
> producción por el responsable legal/asesor de la empresa. No asumir
> que un tutorial antiguo refleja la normativa vigente.

------------------------------------------------------------------------

# 2. Contexto actual

El PDF actual corresponde al formato de Libro de Reclamaciones y
contiene, entre otros, los siguientes bloques:

1.  Identificación del consumidor reclamante.
2.  Identificación del bien contratado.
3.  Detalle de la reclamación y pedido del consumidor.
4.  Observaciones y acciones adoptadas por el proveedor.

En el PDF actual figuran como proveedor:

-   **Razón social:** INVERSIONES STARNET PERU SAC
-   **RUC:** 20608786598
-   **Domicilio mostrado en el PDF:** AV. RAMON CASTILLA NRO 631
    CONCEPCION

Estos datos deben configurarse como datos del proveedor y no quedar
necesariamente escritos de forma fija en cada archivo del frontend.

------------------------------------------------------------------------

# 3. Consideración legal crítica: empresa de telecomunicaciones

Antes de implementar el formulario, confirmar con el responsable legal
si la empresa es una **empresa operadora de servicios públicos de
telecomunicaciones**.

Si corresponde al régimen de OSIPTEL, **no se debe confundir el Libro de
Reclamaciones con el procedimiento de reclamos de telecomunicaciones**.

OSIPTEL indica actualmente que los reclamos por servicios públicos de
telecomunicaciones no deben presentarse en el Libro de Reclamaciones de
las empresas operadoras. Sin embargo, las empresas operadoras deben
encausar los reclamos registrados allí y atenderlos según corresponda.

Fuente oficial:
https://www.osiptel.gob.pe/portal-del-usuario/lo-que-debes-saber/guia-de-informacion-y-orientacion/lo-que-debes-saber-antes-de-reclamar/

También existe un procedimiento específico de reclamos de
telecomunicaciones que puede presentarse por la página web o aplicativo
de la empresa operadora.

Fuente oficial:
https://www.osiptel.gob.pe/portal-del-usuario/preguntas-frecuentes/problemas-con-tu-servicio/

## Requisito de diseño

No implementar un único formulario suponiendo que todo problema del
cliente es un Libro de Reclamaciones.

El responsable del proyecto debe decidir si la web tendrá:

-   **Libro de Reclamaciones**, y
-   **Canal/Formulario de Reclamos OSIPTEL**, separado cuando
    corresponda.

Si ambos existen, deben estar claramente diferenciados para el usuario.

------------------------------------------------------------------------

# 4. Marco normativo que debe revisarse

## 4.1 Código de Protección y Defensa del Consumidor

Revisar la **Ley N.° 29571**, Código de Protección y Defensa del
Consumidor, especialmente las disposiciones relativas al Libro de
Reclamaciones.

Fuente: https://www.gob.pe/institucion/indecopi/normas-legales

## 4.2 Reglamento del Libro de Reclamaciones

Revisar el **Decreto Supremo N.° 011-2011-PCM** y sus modificaciones
vigentes.

Indecopi indica que el formato oficial de la Hoja de Reclamación se
encuentra en el Anexo I del D.S. N.° 011-2011-PCM.

Fuente oficial: https://consumidor.gob.pe/libro-de-reclamaciones/

La implementación web debe conservar la información mínima exigible
aunque la interfaz visual sea diferente al PDF.

## 4.3 Ley N.° 32495

La **Ley N.° 32495**, publicada el 11/11/2025, modificó los artículos
150 y 151 del Código de Protección y Defensa del Consumidor para incluir
expresamente a las plataformas digitales de comercio electrónico dentro
de la obligación de contar con Libro de Reclamaciones.

Fuente oficial: https://busquedas.elperuano.pe/dispositivo/NL/2457203-1

**Importante:** verificar con el responsable legal si el sitio concreto
de la empresa entra en el ámbito de esta modificación. No asumir
automáticamente que cualquier página web es una "plataforma digital de
comercio electrónico".

## 4.4 Protección de datos personales

Revisar:

-   Ley N.° 29733, Ley de Protección de Datos Personales.
-   Decreto Supremo N.° 016-2024-JUS, nuevo Reglamento de la Ley N.°
    29733.
-   Normativa y criterios vigentes de la Autoridad Nacional de
    Protección de Datos Personales (ANPD).

El D.S. N.° 016-2024-JUS está vigente desde el 31/03/2025.

Fuente oficial:
https://www.gob.pe/institucion/anpd/normas-legales/6554453-n-016-2024-jus

La ANPD informa que los titulares de bancos de datos personales deben
inscribirlos en el Registro Nacional de Protección de Datos Personales.

Fuente oficial:
https://www.gob.pe/8060-inscribir-banco-de-datos-en-el-registro-nacional-de-proteccion-de-datos-personales

------------------------------------------------------------------------

# 5. Requisitos legales/funcionales confirmados por Indecopi

Según la información oficial actualmente publicada por Indecopi, el
Libro de Reclamaciones puede ser físico o virtual.

Para un Libro virtual:

-   Debe ser accesible en el mismo medio virtual donde se ofrecen los
    productos/servicios.
-   Debe permitir imprimir o enviar copia al correo del consumidor.
-   Debe contener la información mínima exigida.
-   El proveedor debe responder en un plazo máximo de 15 días hábiles no
    prorrogables.
-   La respuesta debe ser escrita.
-   Si el proveedor no acepta el pedido del consumidor, debe fundamentar
    su posición.
-   El proveedor debe conservar evidencia de la respuesta enviada por
    correo en los casos correspondientes.

Fuente oficial: https://consumidor.gob.pe/libro-de-reclamaciones/

## Importante sobre el plazo

El sistema debe trabajar con **días hábiles**, no simplemente sumar 15
días calendario.

La fecha límite debe calcularse en backend utilizando un calendario de
días hábiles que contemple los feriados aplicables.

No implementar inicialmente una fórmula simplificada como:

``` text
fecha_limite = fecha_registro + 15 días
```

sin considerar días no laborables.

------------------------------------------------------------------------

# 6. Diferencia entre RECLAMO y QUEJA

El formulario debe permitir seleccionar:

### Reclamo

Disconformidad relacionada con el producto o servicio
adquirido/recibido.

### Queja

Manifestación de malestar o disconformidad relacionada con la atención
al público u otros aspectos que no constituyen directamente una
disconformidad con el producto o servicio.

El sistema debe guardar la opción seleccionada.

Además, el backend y el personal administrativo deben poder
corregir/encauzar la clasificación cuando corresponda conforme a la
normativa.

**No confiar únicamente en JavaScript para esta clasificación.**

------------------------------------------------------------------------

# 7. Campos del formulario web

## 7.1 Datos del proveedor

Estos datos pueden mostrarse automáticamente y no deben ser editables
por el consumidor:

-   Razón social.
-   RUC.
-   Domicilio.
-   Datos de contacto institucionales, si corresponde.

## 7.2 Identificación del consumidor

Campos propuestos:

-   Tipo de persona:
    -   Persona natural.
    -   Persona jurídica, si corresponde.
-   Nombre completo / Razón social.
-   Tipo de documento:
    -   DNI.
    -   Carné de Extranjería.
    -   RUC.
    -   Otros, solo si legalmente corresponde.
-   Número de documento.
-   Domicilio.
-   Teléfono.
-   Correo electrónico.

## 7.3 Menor de edad

Campo:

``` text
¿El consumidor es menor de edad?
[ ] Sí
[ ] No
```

Si selecciona "Sí", mostrar:

-   Nombre del padre, madre o apoderado.
-   Documento del padre, madre o apoderado.

Los datos del representante deben guardarse únicamente cuando
correspondan.

------------------------------------------------------------------------

# 8. Identificación del bien o servicio contratado

Mantener la estructura del PDF actual:

-   Producto.
-   Servicio.
-   Descripción.
-   Monto reclamado.

Para un ISP, adaptar la sección al catálogo real de servicios de la
empresa.

Ejemplo:

``` text
Tipo:
[ Servicio ]

Servicio:
[ Internet ]
[ Internet + TV ]
[ Otro ]

Descripción:
[........................]

Monto reclamado:
S/ [........]
```

El campo "monto reclamado" debe permitir quedar vacío cuando no
corresponda.

------------------------------------------------------------------------

# 9. Tipo de presentación

Mostrar:

``` text
Tipo de atención:

( ) RECLAMO
( ) QUEJA
```

Mostrar una explicación breve de cada opción.

No utilizar textos que induzcan al usuario a seleccionar una categoría
específica.

------------------------------------------------------------------------

# 10. Detalle de la reclamación

Campo:

``` html
<textarea name="detalle" maxlength="5000"></textarea>
```

Requisitos:

-   Obligatorio.
-   No aceptar únicamente espacios.
-   Limpiar espacios innecesarios al inicio/final.
-   Validar longitud también en backend.
-   No confiar solamente en `maxlength` del HTML.

El límite de 5000 es una propuesta técnica y puede modificarse.

------------------------------------------------------------------------

# 11. Pedido concreto del consumidor

Campo obligatorio:

``` html
<textarea name="pedido" maxlength="3000"></textarea>
```

Debe permitir que el consumidor indique claramente qué solución
solicita.

Ejemplo:

> Solicito la revisión del cobro aplicado en mi recibo.

No eliminar este campo.

------------------------------------------------------------------------

# 12. Confirmación antes de enviar

Antes de registrar:

Mostrar un resumen de la información ingresada.

El usuario debe poder:

-   volver atrás;
-   corregir datos;
-   confirmar el envío.

Registrar:

-   fecha y hora del servidor;
-   datos de la Hoja de Reclamación;
-   método/canal de presentación;
-   usuario/consumidor asociado si existe;
-   evidencia técnica disponible que sea legalmente apropiada.

------------------------------------------------------------------------

# 13. Firma

El PDF físico actual contiene:

> FIRMA DEL CONSUMIDOR

Para la versión virtual no se debe copiar automáticamente el concepto de
firma manuscrita del formato físico.

Indecopi diferencia los requisitos del Libro físico y virtual.

Para el sistema virtual, implementar como mínimo un mecanismo de
confirmación del envío y una constancia electrónica.

Opcionalmente se puede implementar verificación por correo electrónico
mediante código OTP.

**No considerar la firma dibujada en pantalla como requisito obligatorio
salvo que el asesor legal determine que corresponde.**

------------------------------------------------------------------------

# 14. Numeración y código único

Este es un requisito crítico.

Cada Hoja de Reclamación debe tener una identificación única y
correlativa.

Formato sugerido:

``` text
LR-2026-000001
LR-2026-000002
LR-2026-000003
```

La numeración debe ser generada en backend.

## NO HACER

No generar el número definitivo solamente con JavaScript:

``` javascript
const codigo = "LR-2026-" + numero;
```

No confiar en el frontend para garantizar unicidad.

## HACER

La base de datos debe tener:

-   ID interno autoincremental o UUID.
-   Número correlativo.
-   Código público único.
-   Restricción `UNIQUE`.

Ejemplo:

``` text
id: 157
numero: 157
codigo: LR-2026-000157
```

La base de datos debe impedir duplicados aunque exista un error en el
backend.

------------------------------------------------------------------------

# 15. Modelo de base de datos

## Tabla: reclamaciones

Propuesta inicial:

``` text
reclamaciones
--------------------------------
id
numero
codigo
fecha_registro

tipo_persona
nombre_razon_social
tipo_documento
numero_documento
domicilio
telefono
email

es_menor
representante_nombre
representante_documento

tipo_bien
servicio
descripcion_bien
monto_reclamado

tipo
detalle
pedido

estado

fecha_limite_respuesta
fecha_respuesta
respuesta

acciones_adoptadas

created_at
updated_at
```

### Restricciones

``` text
PRIMARY KEY(id)
UNIQUE(numero)
UNIQUE(codigo)
```

El diseño exacto de tipos de datos queda pendiente según el motor de
base de datos.

------------------------------------------------------------------------

# 16. Tabla de historial

Crear una tabla separada:

``` text
reclamacion_historial
--------------------------------
id
reclamacion_id
usuario_id
accion
descripcion
created_at
```

Ejemplos:

``` text
RECLAMACION_CREADA
RECLAMACION_ASIGNADA
ESTADO_CAMBIADO
RESPUESTA_REGISTRADA
RESPUESTA_ENVIADA
PDF_GENERADO
DATOS_ACTUALIZADOS
```

Objetivo:

No depender solamente del campo `estado`.

Debe existir trazabilidad de las acciones realizadas.

------------------------------------------------------------------------

# 17. Estados sugeridos

Estados iniciales:

``` text
RECIBIDO
EN_REVISION
EN_ATENCION
PENDIENTE_INFORMACION
RESPONDIDO
CERRADO
```

El responsable del negocio puede modificarlos.

No eliminar una reclamación para "cerrarla".

Preferir:

``` text
estado = CERRADO
```

y conservar el historial.

------------------------------------------------------------------------

# 18. Fecha límite de respuesta

Al registrar una reclamación:

``` text
fecha_registro
fecha_limite_respuesta
```

La fecha límite debe calcularse en backend con días hábiles.

El panel administrativo debe mostrar alertas:

``` text
VERDE  = plazo normal
AMARILLO = próximo a vencer
ROJO = vencido
```

Los colores son únicamente una propuesta de interfaz.

------------------------------------------------------------------------

# 19. Copia para el consumidor

Después del envío mostrar:

``` text
Reclamación registrada correctamente.

Código:
LR-2026-000157
```

Opciones:

``` text
[ DESCARGAR CONSTANCIA ]
[ IMPRIMIR ]
```

Y enviar copia al correo registrado cuando corresponda.

La copia debe contener al menos la información necesaria para
identificar la Hoja de Reclamación y su contenido.

------------------------------------------------------------------------

# 20. Generación de PDF

Aunque el formulario sea web, mantener la posibilidad de generar una
representación PDF.

Objetivos:

-   archivo interno;
-   copia para consumidor;
-   eventual fiscalización;
-   evidencia documental.

El PDF no debe depender de que el usuario haya utilizado el PDF
original.

El sistema debe generar el documento desde los datos almacenados en la
base de datos.

------------------------------------------------------------------------

# 21. Panel administrativo

Crear un panel protegido.

## Lista

Mostrar:

``` text
Código
Fecha
Consumidor
Tipo
Servicio
Estado
Fecha límite
```

Funciones:

-   Buscar por código.
-   Buscar por documento.
-   Filtrar por estado.
-   Filtrar por tipo.
-   Filtrar por fecha.
-   Ordenar por fecha.
-   Ver detalle.

## Detalle

Mostrar:

``` text
Código
Fecha de registro
Datos del consumidor
Servicio/producto
Detalle
Pedido
Estado
Fecha límite
Historial
Respuesta
Acciones del proveedor
```

Acciones:

``` text
Cambiar estado
Registrar observación
Registrar respuesta
Enviar respuesta
Generar PDF
```

------------------------------------------------------------------------

# 22. Respuesta del proveedor

La respuesta debe quedar almacenada.

Campos sugeridos:

``` text
fecha_respuesta
respuesta
acciones_adoptadas
usuario_responsable
medio_envio
evidencia_envio
```

Si se envía por correo:

Guardar la evidencia/registro de envío que sea necesario para demostrar
el cumplimiento.

No sobrescribir respuestas anteriores sin conservar historial.

------------------------------------------------------------------------

# 23. Seguridad

El sistema tratará datos personales.

Implementar como mínimo:

## Backend

-   Validación de todos los campos.
-   Sanitización/normalización.
-   Consultas preparadas o ORM.
-   Protección contra SQL Injection.
-   Protección contra XSS.
-   Protección CSRF cuando corresponda.
-   Rate limiting del formulario.
-   Protección contra spam/bots.
-   Límites de tamaño de solicitudes.
-   Control de acceso al panel administrativo.
-   Contraseñas almacenadas con hash seguro.
-   HTTPS obligatorio en producción.

## Base de datos

No almacenar contraseñas en texto plano.

No permitir acceso público directo a MySQL.

No exponer endpoints administrativos sin autenticación.

## Logs

Registrar errores técnicos sin guardar innecesariamente datos personales
sensibles en logs.

------------------------------------------------------------------------

# 24. Protección de datos personales

El formulario recopilará datos como:

-   nombre;
-   DNI/CE/RUC;
-   domicilio;
-   teléfono;
-   correo;
-   contenido de la reclamación.

Antes de producción debe revisarse:

1.  Política de privacidad.
2.  Finalidad del tratamiento.
3.  Base legal correspondiente.
4.  Información proporcionada al titular.
5.  Medidas de seguridad.
6.  Conservación de datos.
7.  Control de accesos.
8.  Derechos del titular.
9.  Encargados/terceros involucrados, si existen.
10. Inscripción del banco de datos cuando corresponda.

No utilizar los datos obtenidos mediante el Libro de Reclamaciones para
campañas comerciales sin revisar previamente la base legal y los
requisitos de protección de datos.

------------------------------------------------------------------------

# 25. Banco de datos personales

La ANPD indica que los titulares de bancos de datos personales tienen
obligación de inscribirlos en el Registro Nacional de Protección de
Datos Personales.

El equipo debe confirmar si la empresa ya tiene un banco registrado que
cubra este tratamiento o si debe registrarse uno específico.

No crear automáticamente un nuevo registro legal sin revisar la
estructura existente de la empresa.

Fuente:

https://www.gob.pe/8060-inscribir-banco-de-datos-en-el-registro-nacional-de-proteccion-de-datos-personales

------------------------------------------------------------------------

# 26. Aviso visible del Libro de Reclamaciones

La web debe tener un acceso claro al Libro de Reclamaciones.

Ejemplo:

``` text
LIBRO DE RECLAMACIONES
```

Debe ser accesible desde el sitio donde se ofrecen los
productos/servicios.

Revisar el aviso oficial de Indecopi y utilizar la versión vigente.

Fuente:

https://consumidor.gob.pe/libro-de-reclamaciones/

No reemplazar el aviso oficial por un texto improvisado sin validación.

------------------------------------------------------------------------

# 27. UX recomendada

No copiar visualmente el PDF completo.

El PDF es un formato documental.

La web debe utilizar un formulario sencillo, por ejemplo:

``` text
PASO 1
Datos del consumidor

PASO 2
Producto o servicio

PASO 3
Reclamo / Queja

PASO 4
Detalle y pedido

PASO 5
Revisión y envío
```

Después:

``` text
REGISTRO EXITOSO

Código:
LR-2026-000157

[Descargar constancia]
[Enviar copia al correo]
```

Debe funcionar correctamente en:

-   PC.
-   Laptop.
-   Tablet.
-   Celular.

------------------------------------------------------------------------

# 28. Validaciones del frontend

JavaScript puede validar:

-   campos obligatorios;
-   formato de correo;
-   longitud;
-   documento;
-   teléfono;
-   monto;
-   selección de tipo;
-   aceptación de términos;
-   confirmación.

Pero estas validaciones son solamente de UX.

**El backend debe repetir las validaciones.**

Nunca confiar en:

``` text
HTML + JavaScript
```

como mecanismo de seguridad.

------------------------------------------------------------------------

# 29. API sugerida

Si se implementa un backend PHP, una estructura sencilla podría ser:

``` text
/api/
    crear-reclamacion.php
    consultar-reclamacion.php
    enviar-copia.php

/admin/
    login.php
    reclamaciones.php
    reclamacion.php
    responder.php
```

Si el proyecto utiliza otro backend, adaptar la estructura.

## Endpoint principal

``` text
POST /api/crear-reclamacion.php
```

Flujo:

``` text
Frontend
   |
   | POST
   v
Backend
   |
   | validar
   v
Base de datos
   |
   | generar número/código
   v
Registro
   |
   +--> generar constancia
   |
   +--> enviar correo
   |
   v
Respuesta JSON
```

------------------------------------------------------------------------

# 30. Respuesta JSON sugerida

Éxito:

``` json
{
  "success": true,
  "codigo": "LR-2026-000157",
  "mensaje": "La Hoja de Reclamación fue registrada correctamente."
}
```

Error:

``` json
{
  "success": false,
  "message": "No fue posible registrar la Hoja de Reclamación."
}
```

No devolver al frontend información interna de la base de datos ni
mensajes SQL.

------------------------------------------------------------------------

# 31. Transacción para generar el código

El registro debe realizarse mediante una transacción.

Conceptualmente:

``` text
BEGIN TRANSACTION

1. Crear registro.
2. Obtener número correlativo.
3. Construir código.
4. Guardar código.
5. Crear historial.
6. COMMIT

Si ocurre un error:
ROLLBACK
```

La implementación concreta dependerá del motor de base de datos.

Además:

``` text
UNIQUE(numero)
UNIQUE(codigo)
```

deben existir en la base de datos.

------------------------------------------------------------------------

# 32. Concurrencia

El sistema debe soportar dos o más personas enviando el formulario al
mismo tiempo.

Ejemplo:

``` text
Usuario A -> enviar
Usuario B -> enviar
Usuario C -> enviar
```

No debe producir:

``` text
LR-2026-000157
LR-2026-000157
LR-2026-000157
```

Debe producir:

``` text
LR-2026-000157
LR-2026-000158
LR-2026-000159
```

La unicidad debe estar garantizada por el backend + base de datos.

------------------------------------------------------------------------

# 33. Anti-spam

Como el formulario estará públicamente accesible, implementar protección
contra abuso.

Opciones:

-   Rate limiting.
-   CAPTCHA/Turnstile.
-   Honeypot.
-   Validación de tiempo mínimo de envío.
-   Límite de solicitudes por IP cuando sea apropiado.

No bloquear injustificadamente a usuarios reales.

------------------------------------------------------------------------

# 34. No almacenar información innecesaria

No agregar campos solamente porque son técnicamente fáciles de capturar.

Por ejemplo:

-   ubicación GPS;
-   lista completa de navegación;
-   información del dispositivo;
-   datos comerciales adicionales;

no deben almacenarse salvo que exista una finalidad legítima y
previamente definida.

El principio debe ser:

> recopilar únicamente los datos necesarios para la finalidad
> correspondiente.

------------------------------------------------------------------------

# 35. Diferenciar datos públicos y privados

El código:

``` text
LR-2026-000157
```

puede utilizarse como referencia.

Pero no crear una página pública donde cualquier persona pueda consultar
todos los datos de una reclamación únicamente introduciendo el código.

Si se crea consulta pública, debe existir autenticación/verificación
adicional y limitar la información expuesta.

------------------------------------------------------------------------

# 36. Retención y eliminación

No implementar un botón simple de:

``` text
DELETE
```

para reclamaciones.

Antes de definir eliminación automática, revisar:

-   obligaciones legales;
-   plazos de conservación;
-   fiscalización;
-   protección de datos;
-   políticas internas.

Preferir mecanismos de archivo/estado antes que borrado físico.

------------------------------------------------------------------------

# 37. Requerimientos de auditoría

Registrar como mínimo:

-   creación;
-   cambio de estado;
-   modificación;
-   respuesta;
-   envío de correo;
-   generación de PDF;
-   usuario administrativo responsable;
-   fecha y hora.

El historial no debería ser editable desde la interfaz normal.

------------------------------------------------------------------------

# 38. Fuentes oficiales para el agente de IA

El agente debe priorizar siempre fuentes oficiales.

## Indecopi

Libro de Reclamaciones:

https://consumidor.gob.pe/libro-de-reclamaciones/

## El Peruano

Ley N.° 32495:

https://busquedas.elperuano.pe/dispositivo/NL/2457203-1

## OSIPTEL

Lo que debes saber antes de reclamar:

https://www.osiptel.gob.pe/portal-del-usuario/lo-que-debes-saber/guia-de-informacion-y-orientacion/lo-que-debes-saber-antes-de-reclamar/

Problemas con el servicio:

https://www.osiptel.gob.pe/portal-del-usuario/preguntas-frecuentes/problemas-con-tu-servicio/

Formularios:

https://www.osiptel.gob.pe/portal-del-usuario/lo-que-debes-saber/guia-de-informacion-y-orientacion/formularios-copia/

## ANPD

Reglamento de la Ley N.° 29733:

https://www.gob.pe/institucion/anpd/normas-legales/6554453-n-016-2024-jus

Inscripción de banco de datos personales:

https://www.gob.pe/8060-inscribir-banco-de-datos-en-el-registro-nacional-de-proteccion-de-datos-personales

------------------------------------------------------------------------

# 39. Regla para el agente de IA

Antes de modificar el código:

1.  Leer este documento completo.
2.  Revisar la estructura actual del proyecto.
3.  No asumir el framework/backend.
4.  No sobrescribir archivos existentes sin revisar su contenido.
5.  Identificar dónde está actualmente el enlace al PDF.
6.  Identificar el servidor/backend disponible.
7.  Identificar el motor de base de datos.
8.  Revisar si ya existe autenticación administrativa.
9.  Revisar si ya existe sistema de envío de correo.
10. Revisar si existe una política de privacidad.
11. Proponer cambios antes de realizar modificaciones grandes.

------------------------------------------------------------------------

# 40. Regla legal para el agente

El agente de IA **no debe inventar requisitos legales**.

Cuando exista una duda:

1.  Buscar primero en fuentes oficiales.
2.  Priorizar:
    -   El Peruano.
    -   Indecopi.
    -   OSIPTEL.
    -   ANPD.
    -   Gob.pe.
3.  Indicar claramente cuando algo sea:
    -   requisito legal;
    -   recomendación técnica;
    -   decisión de negocio;
    -   decisión de UX.
4.  No presentar una recomendación técnica como si fuera obligación
    legal.
5.  Si una norma fue modificada recientemente, verificar la versión
    vigente antes de implementar.

------------------------------------------------------------------------

# 41. Regla especial para OSIPTEL

Si la empresa es una operadora de servicios públicos de
telecomunicaciones:

**NO implementar el Libro de Reclamaciones como sustituto del
procedimiento de reclamos OSIPTEL.**

Debe analizarse un flujo separado para los reclamos de
telecomunicaciones.

El agente debe revisar la normativa y documentación vigente de OSIPTEL
antes de implementar cualquier formulario destinado a reclamos sobre:

-   facturación;
-   calidad;
-   suspensión;
-   contratación;
-   instalación;
-   migración;
-   baja;
-   servicios públicos de telecomunicaciones;
-   otros motivos regulados por OSIPTEL.

------------------------------------------------------------------------

# 42. Checklist de implementación

## Legal

-   [ ] Confirmar aplicabilidad del Libro de Reclamaciones.
-   [ ] Revisar normativa vigente.
-   [ ] Confirmar si la empresa está sujeta a OSIPTEL.
-   [ ] Separar Libro de Reclamaciones y reclamos OSIPTEL cuando
    corresponda.
-   [ ] Revisar política de privacidad.
-   [ ] Revisar banco de datos personales.
-   [ ] Revisar aviso oficial del Libro de Reclamaciones.

## Base de datos

-   [ ] Crear tabla `reclamaciones`.
-   [ ] Crear tabla `reclamacion_historial`.
-   [ ] Definir PK.
-   [ ] Definir `UNIQUE(numero)`.
-   [ ] Definir `UNIQUE(codigo)`.
-   [ ] Crear estados.
-   [ ] Crear fecha de registro.
-   [ ] Crear fecha límite.
-   [ ] Crear campos de respuesta.
-   [ ] Crear auditoría.

## Backend

-   [ ] Endpoint de creación.
-   [ ] Validación server-side.
-   [ ] Transacciones.
-   [ ] Generación de número.
-   [ ] Generación de código.
-   [ ] Manejo de concurrencia.
-   [ ] Rate limiting.
-   [ ] Protección contra spam.
-   [ ] Envío de correo.
-   [ ] Generación de PDF.

## Frontend

-   [ ] Reemplazar enlace al PDF.
-   [ ] Crear formulario responsive.
-   [ ] Validación JS.
-   [ ] Mostrar errores.
-   [ ] Confirmación previa.
-   [ ] Pantalla de éxito.
-   [ ] Mostrar código.
-   [ ] Opción de descarga/impresión.

## Administración

-   [ ] Login.
-   [ ] Lista.
-   [ ] Filtros.
-   [ ] Búsqueda.
-   [ ] Detalle.
-   [ ] Cambio de estado.
-   [ ] Registro de respuesta.
-   [ ] Historial.
-   [ ] PDF.
-   [ ] Evidencia de envío.

## Seguridad

-   [ ] HTTPS.
-   [ ] CSRF.
-   [ ] SQL Injection.
-   [ ] XSS.
-   [ ] Rate limiting.
-   [ ] Control de acceso.
-   [ ] Logs seguros.
-   [ ] Backups.
-   [ ] Acceso restringido a BD.

------------------------------------------------------------------------

# 43. Orden recomendado de desarrollo

### Fase 1 --- Auditoría del proyecto actual

No modificar código todavía.

Identificar:

-   estructura de carpetas;
-   servidor;
-   backend;
-   base de datos;
-   formulario/PDF actual;
-   sistema de correo;
-   autenticación.

### Fase 2 --- Diseño

Crear:

-   modelo de datos;
-   endpoints;
-   flujo del formulario;
-   estados;
-   permisos.

### Fase 3 --- Base de datos

Crear tablas y restricciones.

### Fase 4 --- Backend

Implementar registro seguro y generación del código.

### Fase 5 --- Frontend

Reemplazar el PDF por formulario.

### Fase 6 --- Administración

Crear panel interno.

### Fase 7 --- PDF/correo

Generar constancia y envío.

### Fase 8 --- Seguridad

Realizar pruebas de:

-   duplicación;
-   concurrencia;
-   inyección;
-   XSS;
-   CSRF;
-   spam;
-   permisos.

### Fase 9 --- Revisión legal

Antes de producción, validar el resultado final con la persona
responsable de cumplimiento/legal.

------------------------------------------------------------------------

# 44. Criterios de aceptación

El desarrollo se considerará funcionalmente terminado cuando:

1.  El usuario pueda abrir el Libro de Reclamaciones desde la web.
2.  El formulario sea responsive.
3.  Los campos obligatorios estén validados.
4.  El backend vuelva a validar todos los datos.
5.  Cada registro tenga un número único.
6.  No existan códigos duplicados.
7.  La numeración soporte envíos simultáneos.
8.  Se almacene la información en BD.
9.  Se registre el historial.
10. Se calcule correctamente el plazo de respuesta según días hábiles.
11. El administrador pueda revisar la reclamación.
12. El administrador pueda registrar la respuesta.
13. El consumidor reciba/pueda obtener una copia.
14. Se pueda generar una constancia PDF.
15. Exista control de acceso administrativo.
16. Los datos personales estén protegidos.
17. Se haya revisado la compatibilidad con las reglas de OSIPTEL si la
    empresa es una operadora de telecomunicaciones.
18. Se haya realizado revisión legal antes de producción.

------------------------------------------------------------------------

# 45. Nota final para el agente

Este documento define **qué debe conseguir el sistema**, pero no obliga
a una tecnología específica.

El agente debe adaptarse al código existente.

**No convertir el proyecto completo a un framework nuevo solamente para
implementar el Libro de Reclamaciones.**

Si el sitio actual funciona con HTML + CSS + JavaScript y existe un
backend PHP/MySQL disponible, priorizar una integración pequeña, segura
y mantenible.

La prioridad es:

**cumplimiento legal + integridad de datos + seguridad + trazabilidad +
simplicidad.**
