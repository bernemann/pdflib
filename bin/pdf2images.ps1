param(
    [string]$Root = "tests/_files",
    [int]$Density = 300
)

$ErrorActionPreference = "Stop"

Set-Location (Resolve-Path (Join-Path $PSScriptRoot ".."))

if (-not (Get-Command magick -ErrorAction SilentlyContinue)) {
    throw "ImageMagick 'magick' was not found in PATH."
}

if (-not (Test-Path $Root)) {
    throw "Root path '$Root' does not exist."
}

$pdfs = Get-ChildItem $Root -Filter *.pdf -Recurse -File

if (-not $pdfs) {
    Write-Host "No PDF files found in '$Root'."
    exit 0
}

foreach ($pdf in $pdfs) {
    $png = [IO.Path]::ChangeExtension($pdf.FullName, "png")

    magick `
        -density $Density `
        "$($pdf.FullName)[0]" `
        -background white `
        -flatten `
        -define png:exclude-chunk=tIME,tEXt,zTXt `
        $png

    Write-Host "Converted: $($pdf.Name)"
}