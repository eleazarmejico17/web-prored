// Configuración de Eleventy 3
// - Plantillas en src/ → salida a dist/
// - public/ (CSS, JS, imágenes, videos, PDF) se copia tal cual a dist/public/
module.exports = function (eleventyConfig) {
  // Copia los assets estáticos sin procesarlos (mismas rutas relativas que hoy)
  eleventyConfig.addPassthroughCopy("public");
  // sitemap.xml debe servirse desde la raíz del sitio (SEO)
  eleventyConfig.addPassthroughCopy("sitemap.xml");
  eleventyConfig.addPassthroughCopy("robots.txt");

  // Backend Libro de Reclamaciones (PHP + MySQL) — se despliega junto al sitio
  eleventyConfig.addPassthroughCopy("api");
  eleventyConfig.addPassthroughCopy("admin");
  eleventyConfig.addPassthroughCopy("includes");
  eleventyConfig.addPassthroughCopy("sql");
  eleventyConfig.addPassthroughCopy("config.php");
  eleventyConfig.addPassthroughCopy(".env.example");

  return {
    dir: {
      input: "src",
      includes: "_includes",
      data: "_data",
      output: "dist",
    },
    templateFormats: ["njk", "html", "md"],
    htmlTemplateEngine: "njk",
    markdownTemplateEngine: "njk",
  };
};
