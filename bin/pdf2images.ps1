param(
    [string]$Root = "tests/_files",
    [int]$Density = 300,
    [switch]$VerboseMagick,
    [switch]$FirstPageOnly = $true
)

$ErrorActionPreference = 'Stop'

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$repoRoot = Resolve-Path (Join-Path $scriptDir "..")
Set-Location $repoRoot

Write-Host "Working directory: $repoRoot" -ForegroundColor Cyan

if (-not (Get-Command magick -ErrorAction SilentlyContinue)) {
    throw "ImageMagick 'magick' was not found in PATH."
}

if (-not (Test-Path $Root)) {
    throw "Root path '$Root' does not exist."
}

$files = @(Get-ChildItem -Path $Root -Filter *.pdf -Recurse -File)
$total = $files.Count

if ($total -eq 0) {
    Write-Host "No PDF files found in '$Root'." -ForegroundColor Yellow
    exit 0
}

Write-Host "Found $total PDF file(s) in '$Root'. Converting..." -ForegroundColor Cyan

$index = 0
foreach ($file in $files) {
    $index++
    $pdf = $file.FullName
    $png = [System.IO.Path]::ChangeExtension($pdf, 'png')

    Write-Host "[$index/$total] $($file.Name)" -NoNewline

    $source = if ($FirstPageOnly) { "$pdf`[0`]" } else { $pdf }
    $magickArgs = @()

    if ($VerboseMagick) {
        $magickArgs += '-verbose'
    }

    $magickArgs += @(
        '-density', $Density,
        $source,
        '-background', 'white',
        '-flatten',
        $png
    )

    & magick @magickArgs
    if ($LASTEXITCODE -ne 0) {
        Write-Host " [FAILED]" -ForegroundColor Red
        throw "ImageMagick failed for '$pdf'"
    }

    Write-Host " -> $([System.IO.Path]::GetFileName($png))" -ForegroundColor Green
}

Write-Host ""
Write-Host "Done. $total file(s) converted." -ForegroundColor Cyan
