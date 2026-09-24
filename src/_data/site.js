// Configuración central del sitio (dato global de Eleventy → {{ site.* }})
// Editar AQUÍ los teléfonos, dirección, menús y enlaces: se propagan a todas las páginas.
module.exports = {
  name: "ProRed",
  lang: "es",
  version: "3.3",
  // Dominio canónico para OG / canonical / robots
  url: "https://proredperu.com",

  legal: {
    company: "INVERSIONES STARNET PERU SAC",
    ruc: "20608786598",
    copyrightYear: new Date().getFullYear(),
  },

  address: "AV.Ramòn Castilla Nº 631 - Concepcion",
  city: "Concepción, Junín",

  contact: {
    landline: "064 624 507",
    salesEmail: "ventas@proredperu.com",
    supportEmail: "soporte@prored.pe",
    privacyEmail: "admin@proredperu.com",
  },

  // Números completos (con código de país 51) para enlaces wa.me / api.whatsapp.com
  whatsapp: {
    general: "51991445527",   // atención y contratación (hogar)
    enterprises: "51935444206", // cotizaciones empresas
    dedicated: "51975366197",   // cotización plan dedicado (solo index)
  },

  // Texto por defecto de los enlaces de WhatsApp
  whatsappDefaultText: "Hola, deseo ser un cliente ProRed",

  // Números mostrados en el footer (formato visual) — sincronizados con whatsapp.*
  whatsappDisplay: ["991 445 527", "935 444 206", "975 366 197"],

  schedule: [
    "Lunes - Viernes: 8:00 AM - 6:00 PM",
    "Sábados: 8:00 AM - 3:00 PM",
  ],
  scheduleBadge: "Soporte 24/7",

  social: {
    facebook: "https://www.facebook.com/fibraoptica.prored",
    instagram: "https://www.instagram.com/proredperu?igsh=MXRkcG95ZXNjeHhqMA==",
    tiktok: "https://vt.tiktok.com/ZSamJcWMh/",
  },

  // Rutas relativas a la RAÍZ del sitio (el prefijo ../ se calcula en el layout)
  navItems: [
    { id: "inicio", href: "", label: "Inicio" },
    { id: "nosotros", href: "nosotros/", label: "Nosotros" },
    { id: "planes", href: "planes/", label: "Planes" },
    { id: "internet-empresas", href: "internet-empresas/", label: "Internet Empresas" },
    { id: "cobertura", href: "cobertura/", label: "Cobertura" },
    { id: "realizar-pagos", href: "realizar-pagos/", label: "Pagos" },
  ],

  footerLinks: [
    { href: "nosotros/", label: "Nosotros" },
    { href: "planes/", label: "Planes" },
    { href: "cobertura/", label: "Cobertura" },
    { href: "realizar-pagos/", label: "Pagos" },
    { href: "politica-de-privacidad/", label: "Política de Privacidad" },
    { href: "libro-reclamaciones/", label: "Libro de Reclamaciones" },
  ],

  // CTA principal del navbar (por página se puede sobreescribir: internet-empresas usa enterprises)
  navCta: {
    label: "Contratar",
    whatsapp: "general",
  },
};
