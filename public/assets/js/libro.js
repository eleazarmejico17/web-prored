/**
 * Libro de Reclamaciones — formulario multi-paso (vanilla JS)
 * Depende de: data-api, data-session, data-pdf-base en #libroForm
 */
(function () {
  "use strict";

  const form = document.getElementById("libroForm");
  if (!form) return;

  const steps = Array.from(form.querySelectorAll(".libro-step"));
  const stepIndicators = Array.from(document.querySelectorAll("#libroSteps li"));
  const apiURL = form.getAttribute("data-api") || "api/crear-reclamacion.php";
  const sessionURL = form.getAttribute("data-session") || "api/session.php";
  const pdfBase = form.getAttribute("data-pdf-base") || "api/pdf.php";

  let current = 1;
  let csrf = null;
  let lastResult = null;

  const fieldsByStep = {
    1: ["tipo_persona", "tipo_documento", "nombre_razon_social", "numero_documento", "telefono", "email", "domicilio"],
    2: ["tipo_bien", "servicio", "monto_reclamado"],
    3: ["tipo", "detalle", "pedido"],
    4: ["declaro"],
  };

  function $(id) {
    return document.getElementById(id);
  }

  function val(name) {
    const el = form.elements.namedItem(name);
    if (!el) return "";
    if (el instanceof RadioNodeList) return el.value;
    if (el.type === "checkbox") return el.checked ? el.value || "1" : "";
    return (el.value || "").trim();
  }

  function fieldWrap(el) {
    if (!el) return null;
    if (el.classList && el.classList.contains("field")) return el;
    return el.closest ? el.closest(".field") : null;
  }

  function ensureErrMsg(wrap) {
    if (!wrap) return null;
    let msg = wrap.querySelector(".err-msg");
    if (!msg) {
      msg = document.createElement("div");
      msg.className = "err-msg";
      msg.setAttribute("role", "alert");
      wrap.appendChild(msg);
    }
    return msg;
  }

  function setFieldError(name, message) {
    const el = form.elements.namedItem(name);
    if (!el) return;
    const target = el instanceof RadioNodeList ? el[0] : el;
    if (!target) return;
    setInvalid(target, true);
    const wrap = fieldWrap(target.closest(".field") || target);
    if (wrap) {
      wrap.classList.add("has-error");
      const msg = ensureErrMsg(wrap);
      if (msg) msg.textContent = message;
    }
  }

  function clearFieldError(el) {
    if (!el) return;
    const target = el instanceof RadioNodeList ? el[0] : el;
    if (!target) return;
    setInvalid(target, false);
    const wrap = fieldWrap(target.closest(".field") || target);
    if (wrap) {
      wrap.classList.remove("has-error");
      const msg = wrap.querySelector(".err-msg");
      if (msg) msg.textContent = "";
    }
  }

  function setInvalid(el, on) {
    if (!el) return;
    el.classList.toggle("invalid", !!on);
  }

  function clearErrors() {
    form.querySelectorAll(".invalid").forEach((el) => el.classList.remove("invalid"));
    form.querySelectorAll(".field.has-error").forEach((el) => {
      el.classList.remove("has-error");
      const msg = el.querySelector(".err-msg");
      if (msg) msg.textContent = "";
    });
    const box = $("formError");
    if (box) {
      box.classList.add("hidden");
      box.textContent = "";
    }
  }

  function showError(msg) {
    const box = $("formError");
    if (!box) return;
    box.textContent = msg;
    box.classList.remove("hidden");
  }

  function validEmail(v) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v);
  }

  // ---- Validadores de identificación ------------------------------------
  function validateNumeroDocumento(tipoDoc, v) {
    const clean = v.replace(/\s+/g, "");
    if (!clean) return "El número de documento es obligatorio.";
    switch (tipoDoc) {
      case "dni":
        return /^\d{8}$/.test(clean) ? null : "El DNI debe tener 8 dígitos.";
      case "ruc":
        return /^\d{11}$/.test(clean) ? null : "El RUC debe tener 11 dígitos.";
      case "ce":
        return /^[0-9A-Za-z-]{6,12}$/.test(clean)
          ? null
          : "Carné de extranjería inválido (6–12 caracteres).";
      case "otro":
        return /^[0-9A-Za-z-]{5,20}$/.test(clean)
          ? null
          : "Número de documento inválido (5–20 caracteres).";
      default:
        return "Seleccione el tipo de documento.";
    }
  }

  function validateTelefono(v) {
    if (!v) return "El teléfono es obligatorio.";
    let d = v.replace(/\D/g, "");
    if (v.startsWith("+51")) d = d.slice(2);
    const len = d.length;
    const ok =
      (len === 9 && d[0] === "9") || // móvil
      (len === 9 && d[0] === "0") || // fijo con área
      (len >= 7 && len <= 8); // fijo sin área
    return ok ? null : "Teléfono inválido (ej. 999 999 999 o 064 123456).";
  }

  function validateNombre(v, tipoPersona) {
    if (!v) return tipoPersona === "juridica" ? "La razón social es obligatoria." : "El nombre es obligatorio.";
    if (v.length < 3) return "Ingrese al menos 3 caracteres.";
    if (!/^[\p{L}\p{N} .,&'/-]+$/u.test(v)) return "Nombre con caracteres no válidos.";
    return null;
  }

  function syncTipoDocumentoOptions() {
    const persona = val("tipo_persona");
    const sel = form.elements.namedItem("tipo_documento");
    if (!sel) return;
    const hint = document.getElementById("numero_documento_hint");
    if (persona === "juridica") {
      sel.value = "ruc";
      sel.disabled = false;
      if (hint) hint.textContent = "RUC: 11 dígitos";
    } else {
      // Natural: si estaba en RUC por jurídica, volver a DNI
      if (sel.value === "ruc") sel.value = "dni";
      if (hint) hint.textContent = "DNI: 8 dígitos · CE: 6–12 caracteres";
    }
  }

  function updateStepUI(n) {
    current = n;
    steps.forEach((fs) => {
      fs.classList.toggle("active", Number(fs.getAttribute("data-step")) === n);
    });
    stepIndicators.forEach((li) => {
      const s = Number(li.getAttribute("data-step"));
      li.classList.toggle("active", s === n);
      li.classList.toggle("done", s < n);
    });
    form.scrollIntoView({ behavior: "smooth", block: "start" });
  }

  function validateStep(n) {
    clearErrors();
    let ok = true;
    const names = fieldsByStep[n] || [];
    let firstBad = null;

    const fail = (el, msg) => {
      ok = false;
      setFieldError(el instanceof RadioNodeList ? el[0] : el, msg);
      if (!firstBad) firstBad = el instanceof RadioNodeList ? el[0] : el;
    };

    names.forEach((name) => {
      const el = form.elements.namedItem(name);
      if (!el) return;

      if (name === "tipo") {
        const chosen = form.querySelector('input[name="tipo"]:checked');
        if (!chosen) {
          ok = false;
          const radios = form.querySelector(".radios");
          if (radios) {
            const wrap = radios.closest(".field");
            if (wrap) {
              wrap.classList.add("has-error");
              const msg = ensureErrMsg(wrap);
              if (msg) msg.textContent = "Seleccione Reclamo o Queja.";
            }
          }
          firstBad = firstBad || form.querySelector(".radios");
        }
        return;
      }

      if (el.type === "checkbox") {
        if (el.required && !el.checked) {
          fail(el, "Debe marcar esta casilla para continuar.");
        }
        return;
      }

      const v = (el.value || "").trim();

      if (el.required && !v) {
        fail(el, "Este campo es obligatorio.");
        return;
      }

      switch (name) {
        case "email":
          if (v && !validEmail(v)) fail(el, "Correo electrónico inválido.");
          break;
        case "numero_documento": {
          const err = validateNumeroDocumento(val("tipo_documento"), v);
          if (err) fail(el, err);
          break;
        }
        case "telefono": {
          const err = validateTelefono(v);
          if (err) fail(el, err);
          break;
        }
        case "nombre_razon_social": {
          const err = validateNombre(v, val("tipo_persona"));
          if (err) fail(el, err);
          break;
        }
        case "domicilio":
          if (v && v.length < 5) fail(el, "Ingrese una dirección más completa.");
          break;
        case "servicio":
          if (v && v.length < 3) fail(el, "Describa el servicio con al menos 3 caracteres.");
          break;
        case "monto_reclamado":
          if (v) {
            const num = Number(v.replace(/[,\s]/g, "."));
            if (!/^\d+(\.\d{1,2})?$/.test(v.replace(/[,\s]/g, ".")) || !Number.isFinite(num) || num < 0 || num > 9999999.99) {
              fail(el, "Monto inválido (solo números, ej. 99.90).");
            }
          }
          break;
        case "detalle":
          if (v && v.length < 20) fail(el, "Describe los hechos con al menos 20 caracteres.");
          break;
        case "pedido":
          if (v && v.length < 10) fail(el, "Especifica tu pedido con al menos 10 caracteres.");
          break;
        case "tipo_persona": {
          const persona = v;
          const tipoDoc = val("tipo_documento");
          if (persona === "juridica" && tipoDoc && tipoDoc !== "ruc") {
            fail(form.elements.namedItem("tipo_documento"), "Para persona jurídica seleccione RUC.");
          }
          break;
        }
        default:
          break;
      }
    });

    // Representante si es menor
    if (n === 1) {
      const menor = $("es_menor") && $("es_menor").checked;
      if (menor) {
        const repN = form.elements.namedItem("representante_nombre");
        const repD = form.elements.namedItem("representante_documento");
        const vn = repN ? (repN.value || "").trim() : "";
        const vd = repD ? (repD.value || "").trim().replace(/\s+/g, "") : "";
        if (vn.length < 3) fail(repN, "Nombre del representante obligatorio (mín. 3 caracteres).");
        if (!/^\d{8}$/.test(vd) && !/^[0-9A-Za-z-]{5,20}$/.test(vd)) {
          fail(repD, "Documento del representante inválido.");
        }
      }
    }

    // Cruce jurídica ↔ RUC en paso 1
    if (n === 1 && val("tipo_persona") === "juridica" && val("tipo_documento") !== "ruc") {
      fail(form.elements.namedItem("tipo_documento"), "Para persona jurídica seleccione RUC.");
    }

    if (!ok && firstBad) {
      firstBad.scrollIntoView({ behavior: "smooth", block: "center" });
      if (typeof firstBad.focus === "function") {
        try { firstBad.focus({ preventScroll: true }); } catch (_) {}
      }
    }
    return ok;
  }

  function buildResumen() {
    const box = $("resumen");
    if (!box) return;
    const tipo = val("tipo") || "—";
    const rows = [
      ["Persona", val("tipo_persona") === "juridica" ? "Jurídica" : "Natural"],
      ["Nombre", val("nombre_razon_social")],
      ["Documento", (val("tipo_documento") || "").toUpperCase() + " " + val("numero_documento")],
      ["Contacto", val("email") + " · " + val("telefono")],
      ["Domicilio", val("domicilio")],
      ["Bien/Servicio", val("servicio") + (val("descripcion_bien") ? " — " + val("descripcion_bien") : "")],
      ["Monto", val("monto_reclamado") ? "S/ " + val("monto_reclamado") : "—"],
      ["Tipo", tipo.charAt(0).toUpperCase() + tipo.slice(1)],
      ["Detalle", val("detalle")],
      ["Pedido", val("pedido")],
    ];
    box.innerHTML =
      "<h4>Resumen de tu Hoja de Reclamación</h4><dl>" +
      rows
        .map(
          ([k, v]) =>
            "<dt>" + escapeHtml(k) + "</dt><dd>" + escapeHtml(String(v || "—")) + "</dd>"
        )
        .join("") +
      "</dl>";
  }

  function escapeHtml(s) {
    return s
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  // Solo acepta URLs del PDF en el mismo origen y con el path esperado
  // (protege contra javascript:/data: si la respuesta del servidor se alterara)
  function safePdfUrl(u) {
    if (!u) return null;
    try {
      const url = new URL(String(u), location.href);
      if (url.origin !== location.origin) return null;
      if (!url.pathname.endsWith("/api/pdf.php")) return null;
      return url.href;
    } catch (_) {
      return null;
    }
  }

  async function ensureCsrf() {
    if (csrf) return csrf;
    try {
      const res = await fetch(sessionURL, { credentials: "same-origin", headers: { Accept: "application/json" } });
      const data = await res.json();
      if (data && data.csrf) {
        csrf = data.csrf;
        return csrf;
      }
    } catch (_) {}
    return null;
  }

  // ---- Contadores de texto ----------------------------------------------
  function bindCounter(textareaId, counterId) {
    const ta = $(textareaId);
    const c = $(counterId);
    if (!ta || !c) return;
    const upd = () => {
      c.textContent = String(ta.value.length);
    };
    ta.addEventListener("input", upd);
    upd();
  }
  bindCounter("detalle", "detalleCount");
  bindCounter("pedido", "pedidoCount");

  // ---- Menor de edad -----------------------------------------------------
  function toggleRepresentante() {
    const box = $("representanteBox");
    const chk = $("es_menor");
    if (!box || !chk) return;
    box.classList.toggle("hidden", !chk.checked);
  }
  if ($("es_menor")) {
    $("es_menor").addEventListener("change", toggleRepresentante);
    toggleRepresentante();
  }

  // ---- Sincronía persona ↔ documento ------------------------------------
  const tipoPersonaSel = form.elements.namedItem("tipo_persona");
  if (tipoPersonaSel && tipoPersonaSel.addEventListener) {
    tipoPersonaSel.addEventListener("change", () => {
      syncTipoDocumentoOptions();
      clearFieldError(form.elements.namedItem("tipo_documento"));
      clearFieldError(form.elements.namedItem("numero_documento"));
      clearFieldError(form.elements.namedItem("nombre_razon_social"));
    });
  }
  syncTipoDocumentoOptions();

  // Limpiar error al escribir / cambiar
  form.addEventListener("input", (ev) => {
    const t = ev.target;
    if (t && t.name) clearFieldError(t);
  });
  form.addEventListener("change", (ev) => {
    const t = ev.target;
    if (t && t.name) clearFieldError(t);
  });

  // ---- Navegación pasos --------------------------------------------------
  form.querySelectorAll(".btn-next").forEach((btn) => {
    btn.addEventListener("click", () => {
      const next = Number(btn.getAttribute("data-next"));
      if (!validateStep(current)) return;
      if (next === 4) buildResumen();
      updateStepUI(next);
    });
  });

  form.querySelectorAll(".btn-back").forEach((btn) => {
    btn.addEventListener("click", () => {
      const back = Number(btn.getAttribute("data-back"));
      clearErrors();
      updateStepUI(back);
    });
  });

  // ---- Envío -------------------------------------------------------------
  form.addEventListener("submit", async (ev) => {
    ev.preventDefault();
    clearErrors();
    if (!validateStep(4)) return;

    const btn = $("btnSubmit");
    if (btn) btn.disabled = true;

    try {
      const token = await ensureCsrf();
      if (!token) {
        showError("No se pudo iniciar la sesión segura. Recargue la página.");
        if (btn) btn.disabled = false;
        return;
      }

      const payload = {
        csrf: token,
        website: val("website"),
        tipo_persona: val("tipo_persona"),
        tipo_documento: val("tipo_documento"),
        nombre_razon_social: val("nombre_razon_social"),
        numero_documento: val("numero_documento"),
        domicilio: val("domicilio"),
        telefono: val("telefono"),
        email: val("email"),
        es_menor: $("es_menor") && $("es_menor").checked ? "1" : "",
        representante_nombre: val("representante_nombre"),
        representante_documento: val("representante_documento"),
        tipo_bien: val("tipo_bien"),
        servicio: val("servicio"),
        descripcion_bien: val("descripcion_bien"),
        monto_reclamado: val("monto_reclamado"),
        tipo: val("tipo"),
        detalle: val("detalle"),
        pedido: val("pedido"),
        declaro: $("declaro") && $("declaro").checked ? "1" : "0",
      };

      const res = await fetch(apiURL, {
        method: "POST",
        credentials: "same-origin",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(payload),
      });

      let data;
      try {
        data = await res.json();
      } catch (_) {
        data = { success: false, message: "Respuesta inválida del servidor." };
      }

      if (!res.ok || !data.success) {
        showError(data.message || "No fue posible registrar. Intente de nuevo.");
        if (data.errors) {
          Object.keys(data.errors).forEach((k) => {
            setFieldError(k, data.errors[k]);
            const el = form.elements.namedItem(k);
            if (el) {
              const target = el instanceof RadioNodeList ? el[0] : el;
              const fs = target && target.closest ? target.closest(".libro-step") : null;
              if (fs) {
                const stepN = Number(fs.getAttribute("data-step"));
                if (stepN && stepN !== current) updateStepUI(stepN);
              }
            }
          });
        }
        if (btn) btn.disabled = false;
        return;
      }

      lastResult = data;

      // Éxito → paso 5
      if ($("codigoOut")) $("codigoOut").textContent = data.codigo || "—";
      if ($("limiteOut")) {
        const f = data.fecha_limite_respuesta;
        if (f) {
          const [y, m, d] = f.split("-");
          $("limiteOut").textContent = d + "/" + m + "/" + y;
        }
      }
      if ($("emailOut")) $("emailOut").textContent = val("email");
      if ($("emailStatusOut")) {
        if (data.email_enviado) {
          $("emailStatusOut").textContent = "Enviada a " + val("email");
          $("emailStatusOut").classList.add("ok-mail");
        } else {
          $("emailStatusOut").textContent =
            "No se envió automáticamente — guarda el código y usa el PDF";
          $("emailStatusOut").classList.add("warn-mail");
        }
      }

      const pdfUrl = safePdfUrl(
        data.pdf_url ||
          (data.codigo && data.token
            ? pdfBase + "?codigo=" + encodeURIComponent(data.codigo) + "&token=" + encodeURIComponent(data.token)
            : null)
      );
      const verPdf = $("btnVerPdf");
      if (verPdf && pdfUrl) {
        verPdf.href = pdfUrl;
      }

      updateStepUI(5);
    } catch (e) {
      showError("Error de red. Verifique su conexión e intente de nuevo.");
    }
    if (btn) btn.disabled = false;
  });

  // Éxito: acción única — Ver PDF en nueva pestaña
  const verPdf = $("btnVerPdf");
  if (verPdf) {
    verPdf.addEventListener("click", (ev) => {
      const href = verPdf.getAttribute("href");
      if (!href || href === "#") {
        ev.preventDefault();
      }
    });
  }

  // Prefetch CSRF al cargar
  ensureCsrf();
})();
