/**
 * Libro de Reclamaciones — formulario multi-paso (vanilla JS)
 * Depende de: data-api, data-session, data-constancia-base en #libroForm
 */
(function () {
  "use strict";

  const form = document.getElementById("libroForm");
  if (!form) return;

  const steps = Array.from(form.querySelectorAll(".libro-step"));
  const stepIndicators = Array.from(document.querySelectorAll("#libroSteps li"));
  const apiURL = form.getAttribute("data-api") || "api/crear-reclamacion.php";
  const sessionURL = form.getAttribute("data-session") || "api/session.php";
  const constanciaBase = form.getAttribute("data-constancia-base") || "api/constancia.php";

  let current = 1;
  let csrf = null;
  const MAX_STEP = 5;

  const fieldsByStep = {
    1: ["tipo_persona", "tipo_documento", "nombre_razon_social", "numero_documento", "telefono", "email", "domicilio"],
    2: ["tipo_bien", "servicio", "monto_reclamado"],
    3: ["tipo", "detalle", "pedido"],
    4: ["declaro"],
  };

  // ---- Utilidades --------------------------------------------------------
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

  function setInvalid(el, on) {
    if (!el) return;
    el.classList.toggle("invalid", !!on);
  }

  function clearErrors() {
    form.querySelectorAll(".invalid").forEach((el) => el.classList.remove("invalid"));
    form.querySelectorAll(".field.has-error").forEach((el) => el.classList.remove("has-error"));
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

    names.forEach((name) => {
      const el = form.elements.namedItem(name);
      if (!el) return;

      if (name === "tipo") {
        const chosen = form.querySelector('input[name="tipo"]:checked');
        if (!chosen) {
          ok = false;
          firstBad = firstBad || form.querySelector(".radios");
        }
        return;
      }

      if (el.type === "checkbox") {
        if (el.required && !el.checked) {
          ok = false;
          setInvalid(el, true);
          firstBad = firstBad || el;
        }
        return;
      }

      const v = (el.value || "").trim();

      if (el.required && !v) {
        ok = false;
        setInvalid(el, true);
        firstBad = firstBad || el;
      } else if (name === "email" && v && !validEmail(v)) {
        ok = false;
        setInvalid(el, true);
        firstBad = firstBad || el;
      } else if (name === "numero_documento" && v && !/^[0-9A-Za-z-]{5,20}$/.test(v.replace(/\s+/g, ""))) {
        ok = false;
        setInvalid(el, true);
        firstBad = firstBad || el;
      } else if (name === "telefono" && v && v.replace(/\D/g, "").length < 7) {
        ok = false;
        setInvalid(el, true);
        firstBad = firstBad || el;
      } else if (name === "monto_reclamado" && v) {
        const n = Number(v.replace(/[,\s]/g, "."));
        if (!Number.isFinite(n) || n < 0 || n > 9999999.99) {
          ok = false;
          setInvalid(el, true);
          firstBad = firstBad || el;
        }
      }
    });

    // Representante si es menor
    if (n === 1) {
      const menor = $("es_menor") && $("es_menor").checked;
      if (menor) {
        ["representante_nombre", "representante_documento"].forEach((name) => {
          const el = form.elements.namedItem(name);
          const v = el ? (el.value || "").trim() : "";
          if (!v) {
            ok = false;
            setInvalid(el, true);
            firstBad = firstBad || el;
          }
        });
      }
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
      .replace(/"/g, "&quot;");
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
            const el = form.elements.namedItem(k);
            if (el && el.length === undefined) {
              setInvalid(el, true);
              // Volver al paso que contiene el campo inválido
              const fs = el.closest(".libro-step");
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
            "No se envió automáticamente — guarda el código y usa la constancia";
          $("emailStatusOut").classList.add("warn-mail");
        }
      }
      const link = $("btnConstancia");
      if (link && data.constancia_url) {
        link.href = data.constancia_url;
      } else if (link && data.codigo) {
        link.href = constanciaBase + "?codigo=" + encodeURIComponent(data.codigo);
      }

      updateStepUI(5);
    } catch (e) {
      showError("Error de red. Verifique su conexión e intente de nuevo.");
    }
    if (btn) btn.disabled = false;
  });

  // Éxito: acciones
  if ($("btnImprimir")) {
    $("btnImprimir").addEventListener("click", () => {
      const link = $("btnConstancia");
      if (link && link.href && link.getAttribute("href") !== "#") {
        window.open(link.href, "_blank", "noopener");
      } else {
        window.print();
      }
    });
  }
  if ($("btnNuevo")) {
    $("btnNuevo").addEventListener("click", () => {
      form.reset();
      if ($("detalleCount")) $("detalleCount").textContent = "0";
      if ($("pedidoCount")) $("pedidoCount").textContent = "0";
      toggleRepresentante();
      clearErrors();
      updateStepUI(1);
    });
  }

  // Prefetch CSRF al cargar
  ensureCsrf();
})();
