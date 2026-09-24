# ============================================================
# build-deploy.ps1 — Empaqueta el sitio para cPanel
# Uso:  powershell -ExecutionPolicy Bypass -File build-deploy.ps1
# Salida: deploy/prored-deploy-AAAAMMDD-HHmm.zip
#   → subir a public_html/ con el Administrador de Archivos de cPanel
#   → Extraer → BORRAR el zip de inmediato
# ============================================================
param([switch]$SkipBuild)
$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path

# 1) Build limpio (borra restos de builds viejos, ej. dist/api/constancia.php)
if (-not $SkipBuild) {
    Remove-Item -Recurse -Force "$root\dist" -ErrorAction SilentlyContinue
    Push-Location $root
    & npm.cmd run build
    Pop-Location
    if ($LASTEXITCODE -ne 0) { throw 'El build de Eleventy fallo.' }
}

# 2) Staging: .htaccess (raíz fusionada) + web/ (todo el sitio)
$stamp = Get-Date -Format 'yyyyMMdd-HHmm'
$stage = "$root\deploy\stage-$stamp"
New-Item -ItemType Directory -Force -Path "$stage\web" | Out-Null
Copy-Item -Recurse "$root\dist\*" "$stage\web"
Copy-Item "$root\htaccess.production" "$stage\.htaccess"
Copy-Item "$root\htaccess.web" "$stage\web\.htaccess"

# 3) .env de PRODUCCION (solo dentro del ZIP local; nunca entra a dist ni a git)
$envProd = @'
# ProRed — Libro de Reclamaciones (PRODUCCION)
# Editar aqui o en public_html/web/.env tras subir. NO compartir.

DB_HOST=localhost
DB_NAME=proredpe_libro_reclamaciones
DB_USER=userlreclamaciones
DB_PASS=g4lT+0-Pwc#8M%se

BASE_URL=https://proredperu.com

MAIL_ENABLED=true
MAIL_FROM=libro-reclamaciones@proredperu.com
MAIL_FROM_NAME=ProRed - Libro de Reclamaciones
MAIL_ADMIN=prored.adm@gmail.com
'@
[System.IO.File]::WriteAllText("$stage\web\.env", $envProd, (New-Object System.Text.UTF8Encoding($false)))

# 4) ZIP con tar.exe (barras "/" correctas y archivos .htaccess/.env incluidos;
#    Compress-Archive de PS 5.1 genera rutas con "\" que cPanel no extrae bien)
$zip = "$root\deploy\prored-deploy-$stamp.zip"
tar.exe -a -c -f $zip -C $stage .
if ($LASTEXITCODE -ne 0) { throw 'tar.exe fallo al crear el ZIP.' }
Remove-Item -Recurse -Force $stage

# 5) Verificacion del contenido del ZIP
Add-Type -AssemblyName System.IO.Compression.FileSystem
$archive = [System.IO.Compression.ZipFile]::OpenRead($zip)
$names = $archive.Entries | ForEach-Object { $_.FullName -replace '^\./', '' }
$archive.Dispose()

$requeridos = @('.htaccess', 'web/.htaccess', 'web/.env', 'web/index.html', 'web/config.php', 'web/.env.example',
    'web/admin/login.php', 'web/admin/index.php', 'web/api/crear-reclamacion.php',
    'web/api/pdf.php', 'web/includes/bootstrap.php', 'web/includes/env.php', 'web/sql/schema.sql',
    'web/libro-reclamaciones/index.html', 'web/public/assets/js/libro.js')
$faltan = $requeridos | Where-Object { $_ -notin $names }

Write-Host ""
Write-Host "ZIP: $zip"
Write-Host ("Entradas: {0}" -f $names.Count)
if ($faltan) {
    Write-Host "FALTAN en el ZIP:" -ForegroundColor Red
    $faltan | ForEach-Object { Write-Host "  - $_" -ForegroundColor Red }
    exit 1
}
Write-Host "Contenido verificado: todo presente." -ForegroundColor Green
Write-Host ""
Write-Host "Sube este ZIP a public_html/ y EXTRAEL; despues BORRA el zip." -ForegroundColor Yellow
