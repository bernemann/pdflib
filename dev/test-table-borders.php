<?php

/**
 * Comprehensive Table Border Test
 *
 * Covers every meaningful border-type / border-width / border-color /
 * background-color combination, including per-cell overrides, colspans,
 * multiline content, alternating rows, and real-world table layouts.
 */
require_once __DIR__ . '/../autoload.php';

use EvoSys21\PdfLib\Fpdf\Pdf;
use EvoSys21\PdfLib\Table;

// ---------------------------------------------------------------------------
// PDF setup
// ---------------------------------------------------------------------------
$pdf = new Pdf();
$pdf->SetAuthor('EvoSys21 - Border Test Suite');
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 15);
$pdf->SetFont('helvetica', '', 9);
$pdf->SetTextColor(0, 0, 0);
$pdf->AddPage();

if (!class_exists('PdfLayout')) {
    final class PdfLayout
    {
        public static function section(Pdf $pdf, string $title, bool $newPage = false): void
        {
            if ($newPage) {
                $pdf->AddPage();
            }

            $pdf->SetFont('helvetica', 'B', 11);
            $pdf->SetFillColor(40, 40, 40);
            $pdf->SetTextColor(255, 255, 255);
            $pdf->Cell(0, 7, "  $title", 0, 1, 'L', true);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Ln(2);
        }

        public static function sub(Pdf $pdf, string $title): void
        {
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(220, 230, 245);
            $pdf->Cell(0, 5, "  $title", 0, 1, 'L', true);
            $pdf->SetFont('helvetica', '', 9);
            $pdf->Ln(1);
        }

        public static function gap(Pdf $pdf, float $h = 5): void
        {
            $pdf->Ln($h);
        }
    }
}

// ---------------------------------------------------------------------------
// Shared column-width sets
// ---------------------------------------------------------------------------
$cols3eq = [57, 57, 56];   // 3 equal ~170
$cols4eq = [42, 43, 43, 42]; // 4 equal ~170
$cols5eq = [34, 34, 34, 34, 34];
$colsAsym = [25, 75, 40, 30]; // asymmetric

// ---------------------------------------------------------------------------
// Common cell defaults (to avoid repeating every time)
// ---------------------------------------------------------------------------
$baseCell = [
    'TEXT_COLOR' => [0, 0, 0],
    'TEXT_SIZE' => 8,
    'TEXT_FONT' => 'helvetica',
    'TEXT_ALIGN' => 'L',
    'VERTICAL_ALIGN' => 'M',
    'TEXT_TYPE' => '',
    'LINE_SIZE' => 4,
    'PADDING_TOP' => 2,
    'PADDING_RIGHT' => 2,
    'PADDING_BOTTOM' => 2,
    'PADDING_LEFT' => 2,
];

$baseHdr = array_merge($baseCell, [
    'TEXT_TYPE' => 'B',
    'BACKGROUND_COLOR' => [50, 50, 50],
    'TEXT_COLOR' => [255, 255, 255],
]);

// ============================================================================
// PAGE 1 – SINGLE-SIDE BORDERS
// ============================================================================
PdfLayout::section($pdf, '1. Single-Side Borders');

// ---------- 1a. Bottom only ------------------------------------------------
PdfLayout::sub($pdf, '1a  Border-Type: B   (bottom only)   size 0.4   black');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [0, 0, 0]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([['TEXT' => 'Name'], ['TEXT' => 'Value'], ['TEXT' => 'Status']]);
$table->addRow([['TEXT' => 'Alpha'], ['TEXT' => '100'], ['TEXT' => 'Active']]);
$table->addRow([['TEXT' => 'Beta'], ['TEXT' => '250'], ['TEXT' => 'Pending']]);
$table->addRow([['TEXT' => 'Gamma'], ['TEXT' => '75'], ['TEXT' => 'Closed']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 1b. Top only ---------------------------------------------------
PdfLayout::sub($pdf, '1b  Border-Type: T   (top only)   size 0.4   red');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [200, 0, 0]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [200, 0, 0], 'BACKGROUND_COLOR' => [255, 240, 240]]),
]);
$table->addHeader([['TEXT' => 'Fruit'], ['TEXT' => 'Qty'], ['TEXT' => 'Price']]);
$table->addRow([['TEXT' => 'Apple'], ['TEXT' => '12'], ['TEXT' => '1.20']]);
$table->addRow([['TEXT' => 'Banana'], ['TEXT' => '5'], ['TEXT' => '0.50']]);
$table->addRow([['TEXT' => 'Cherry'], ['TEXT' => '30'], ['TEXT' => '3.00']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 1c. Left only --------------------------------------------------
PdfLayout::sub($pdf, '1c  Border-Type: L   (left only)   size 0.4   blue');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'L', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [0, 0, 200]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'L', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [0, 0, 200], 'BACKGROUND_COLOR' => [240, 240, 255]]),
]);
$table->addHeader([['TEXT' => 'Code'], ['TEXT' => 'Description'], ['TEXT' => 'Unit']]);
$table->addRow([['TEXT' => 'A-01'], ['TEXT' => 'Widget A'], ['TEXT' => 'pcs']]);
$table->addRow([['TEXT' => 'B-02'], ['TEXT' => 'Gadget B'], ['TEXT' => 'kg']]);
$table->addRow([['TEXT' => 'C-03'], ['TEXT' => 'Component C'], ['TEXT' => 'm']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 1d. Right only -------------------------------------------------
PdfLayout::sub($pdf, '1d  Border-Type: R   (right only)   size 0.4   green');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'R', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [0, 150, 0]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'R', 'BORDER_SIZE' => 0.4, 'BORDER_COLOR' => [0, 150, 0], 'BACKGROUND_COLOR' => [240, 255, 240]]),
]);
$table->addHeader([['TEXT' => 'City'], ['TEXT' => 'Country'], ['TEXT' => 'Pop.']]);
$table->addRow([['TEXT' => 'Paris'], ['TEXT' => 'France'], ['TEXT' => '2.1M']]);
$table->addRow([['TEXT' => 'Berlin'], ['TEXT' => 'Germany'], ['TEXT' => '3.7M']]);
$table->addRow([['TEXT' => 'Rome'], ['TEXT' => 'Italy'], ['TEXT' => '2.9M']]);
$table->close();

// ============================================================================
// PAGE 2 – TWO-SIDE COMBINATIONS
// ============================================================================
PdfLayout::section($pdf, '2. Two-Side Border Combinations', true);

// ---------- 2a. BL ----------------------------------------------------------
PdfLayout::sub($pdf, '2a  BL   size 0.5   dark-gray');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'BL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [60, 60, 60]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'BL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [60, 60, 60], 'BACKGROUND_COLOR' => [248, 248, 248]]),
]);
$table->addHeader([['TEXT' => 'Q1'], ['TEXT' => 'Q2'], ['TEXT' => 'Q3'], ['TEXT' => 'Q4']]);
$table->addRow([['TEXT' => '120'], ['TEXT' => '145'], ['TEXT' => '132'], ['TEXT' => '158']]);
$table->addRow([['TEXT' => '98'], ['TEXT' => '112'], ['TEXT' => '99'], ['TEXT' => '134']]);
$table->addRow([['TEXT' => '200'], ['TEXT' => '190'], ['TEXT' => '210'], ['TEXT' => '225']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 2b. BR ----------------------------------------------------------
PdfLayout::sub($pdf, '2b  BR   size 0.5   orange');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'BR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [220, 100, 0]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'BR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [220, 100, 0], 'BACKGROUND_COLOR' => [255, 248, 240]]),
]);
$table->addHeader([['TEXT' => 'Jan'], ['TEXT' => 'Feb'], ['TEXT' => 'Mar'], ['TEXT' => 'Apr']]);
$table->addRow([['TEXT' => '310'], ['TEXT' => '295'], ['TEXT' => '330'], ['TEXT' => '280']]);
$table->addRow([['TEXT' => '410'], ['TEXT' => '390'], ['TEXT' => '420'], ['TEXT' => '415']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 2c. TB (top + bottom) -------------------------------------------
PdfLayout::sub($pdf, '2c  TB   size 0.5   teal   - horizontal lines only');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'TB', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 128, 128]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'TB', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 128, 128], 'BACKGROUND_COLOR' => [240, 255, 255]]),
]);
$table->addHeader([['TEXT' => 'Mon'], ['TEXT' => 'Tue'], ['TEXT' => 'Wed'], ['TEXT' => 'Thu']]);
$table->addRow([['TEXT' => 'Meeting'], ['TEXT' => 'Review'], ['TEXT' => 'Deploy'], ['TEXT' => 'Test']]);
$table->addRow([['TEXT' => 'Sprint'], ['TEXT' => 'Retro'], ['TEXT' => 'Release'], ['TEXT' => 'Idle']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 2d. LR (left + right) -------------------------------------------
PdfLayout::sub($pdf, '2d  LR   size 0.5   purple   - vertical lines only');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'LR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [120, 0, 180]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'LR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [120, 0, 180], 'BACKGROUND_COLOR' => [250, 240, 255]]),
]);
$table->addHeader([['TEXT' => 'Dept'], ['TEXT' => 'Head'], ['TEXT' => 'Staff'], ['TEXT' => 'Budget']]);
$table->addRow([['TEXT' => 'Engineering'], ['TEXT' => 'Alice'], ['TEXT' => '42'], ['TEXT' => '500k']]);
$table->addRow([['TEXT' => 'Sales'], ['TEXT' => 'Bob'], ['TEXT' => '18'], ['TEXT' => '200k']]);
$table->addRow([['TEXT' => 'HR'], ['TEXT' => 'Carol'], ['TEXT' => '6'], ['TEXT' => '80k']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 2e. TR ----------------------------------------------------------
PdfLayout::sub($pdf, '2e  TR   size 0.5   crimson');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'TR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [180, 0, 30]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'TR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [180, 0, 30], 'BACKGROUND_COLOR' => [255, 245, 245]]),
]);
$table->addHeader([['TEXT' => 'Task'], ['TEXT' => 'Owner'], ['TEXT' => 'Due']]);
$table->addRow([['TEXT' => 'Design'], ['TEXT' => 'Alice'], ['TEXT' => '2026-06-10']]);
$table->addRow([['TEXT' => 'Implement'], ['TEXT' => 'Bob'], ['TEXT' => '2026-06-20']]);
$table->addRow([['TEXT' => 'Test'], ['TEXT' => 'Carol'], ['TEXT' => '2026-06-25']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 2f. TL ----------------------------------------------------------
PdfLayout::sub($pdf, '2f  TL   size 0.5   navy');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'TL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 128]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'TL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 128], 'BACKGROUND_COLOR' => [240, 245, 255]]),
]);
$table->addHeader([['TEXT' => 'Server'], ['TEXT' => 'IP'], ['TEXT' => 'Role']]);
$table->addRow([['TEXT' => 'web-01'], ['TEXT' => '10.0.0.1'], ['TEXT' => 'frontend']]);
$table->addRow([['TEXT' => 'db-01'], ['TEXT' => '10.0.0.2'], ['TEXT' => 'database']]);
$table->addRow([['TEXT' => 'cache-01'], ['TEXT' => '10.0.0.3'], ['TEXT' => 'redis']]);
$table->close();

// ============================================================================
// PAGE 3 – THREE-SIDE COMBINATIONS
// ============================================================================
PdfLayout::section($pdf, '3. Three-Side Border Combinations', true);

// ---------- 3a. BLR (no top) ------------------------------------------------
PdfLayout::sub($pdf, '3a  BLR   (bottom + left + right, no top)   size 0.5   indigo');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'BLR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [75, 0, 130]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'BLR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [75, 0, 130], 'BACKGROUND_COLOR' => [248, 245, 255]]),
]);
$table->addHeader([['TEXT' => 'SKU'], ['TEXT' => 'Product'], ['TEXT' => 'Stock']]);
$table->addRow([['TEXT' => 'X-100'], ['TEXT' => 'Bolt M6'], ['TEXT' => '500']]);
$table->addRow([['TEXT' => 'X-101'], ['TEXT' => 'Nut M6'], ['TEXT' => '450']]);
$table->addRow([['TEXT' => 'X-200'], ['TEXT' => 'Washer 6mm'], ['TEXT' => '1200']]);
$table->addRow([['TEXT' => 'X-300'], ['TEXT' => 'Spring pin'], ['TEXT' => '80']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 3b. TBR (no left) -----------------------------------------------
PdfLayout::sub($pdf, '3b  TBR   (top + bottom + right, no left)   size 0.5   forest-green');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'TBR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 100, 0]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'TBR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 100, 0], 'BACKGROUND_COLOR' => [240, 255, 240]]),
]);
$table->addHeader([['TEXT' => 'Month'], ['TEXT' => 'Revenue'], ['TEXT' => 'Cost']]);
$table->addRow([['TEXT' => 'Jan'], ['TEXT' => '12 500'], ['TEXT' => '8 200']]);
$table->addRow([['TEXT' => 'Feb'], ['TEXT' => '13 100'], ['TEXT' => '8 500']]);
$table->addRow([['TEXT' => 'Mar'], ['TEXT' => '14 800'], ['TEXT' => '9 100']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 3c. TBL (no right) ----------------------------------------------
PdfLayout::sub($pdf, '3c  TBL   (top + bottom + left, no right)   size 0.5   sienna');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'TBL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [160, 82, 45]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'TBL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [160, 82, 45], 'BACKGROUND_COLOR' => [255, 248, 240]]),
]);
$table->addHeader([['TEXT' => 'Type'], ['TEXT' => 'Count'], ['TEXT' => '%']]);
$table->addRow([['TEXT' => 'Critical'], ['TEXT' => '3'], ['TEXT' => '5%']]);
$table->addRow([['TEXT' => 'High'], ['TEXT' => '12'], ['TEXT' => '20%']]);
$table->addRow([['TEXT' => 'Medium'], ['TEXT' => '28'], ['TEXT' => '47%']]);
$table->addRow([['TEXT' => 'Low'], ['TEXT' => '17'], ['TEXT' => '28%']]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 3d. TLR (no bottom) ---------------------------------------------
PdfLayout::sub($pdf, '3d  TLR   (top + left + right, no bottom)   size 0.5   slate');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'TLR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [100, 110, 120]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'TLR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [100, 110, 120], 'BACKGROUND_COLOR' => [245, 247, 250]]),
]);
$table->addHeader([['TEXT' => 'Param'], ['TEXT' => 'Min'], ['TEXT' => 'Max']]);
$table->addRow([['TEXT' => 'Temp'], ['TEXT' => '-20°C'], ['TEXT' => '80°C']]);
$table->addRow([['TEXT' => 'Humidity'], ['TEXT' => '10%'], ['TEXT' => '90%']]);
$table->addRow([['TEXT' => 'Pressure'], ['TEXT' => '950 hPa'], ['TEXT' => '1050 hPa']]);
$table->close();

// ============================================================================
// PAGE 4 – ALL BORDERS + BORDER WIDTHS
// ============================================================================
PdfLayout::section($pdf, '4. All Borders + Varying Border Widths', true);

PdfLayout::sub($pdf, '4a  Border-Type: 1 (all sides)   - 5 different widths');

$widths = [0.1, 0.3, 0.5, 0.8, 1.2];
$widthLabel = ['0.1', '0.3', '0.5', '0.8', '1.2'];
$colsW = [34, 34, 34, 34, 34]; // 5 columns

$table = new Table($pdf);
$table->initialize($colsW, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [30, 30, 30]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [30, 30, 30], 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([
    ['TEXT' => 'W=0.1'], ['TEXT' => 'W=0.3'], ['TEXT' => 'W=0.5'],
    ['TEXT' => 'W=0.8'], ['TEXT' => 'W=1.2'],
]);
// Row 1: each cell overrides its own border width
$table->addRow([
    ['TEXT' => 'thin', 'BORDER_SIZE' => 0.1],
    ['TEXT' => 'light', 'BORDER_SIZE' => 0.3],
    ['TEXT' => 'medium', 'BORDER_SIZE' => 0.5],
    ['TEXT' => 'thick', 'BORDER_SIZE' => 0.8],
    ['TEXT' => 'heaviest', 'BORDER_SIZE' => 1.2],
]);
$table->addRow([
    ['TEXT' => '0.1 mm', 'BORDER_SIZE' => 0.1],
    ['TEXT' => '0.3 mm', 'BORDER_SIZE' => 0.3],
    ['TEXT' => '0.5 mm', 'BORDER_SIZE' => 0.5],
    ['TEXT' => '0.8 mm', 'BORDER_SIZE' => 0.8],
    ['TEXT' => '1.2 mm', 'BORDER_SIZE' => 1.2],
]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '4b  Bottom-only border - 5 widths (0.1 -> 1.5)');

$table = new Table($pdf);
$table->initialize($colsW, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.5]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'B', 'BACKGROUND_COLOR' => [252, 252, 252]]),
]);
$table->addHeader([
    ['TEXT' => '0.1'], ['TEXT' => '0.3'], ['TEXT' => '0.5'],
    ['TEXT' => '1.0'], ['TEXT' => '1.5'],
]);
foreach (['Row A', 'Row B', 'Row C'] as $r) {
    $table->addRow([
        ['TEXT' => $r, 'BORDER_SIZE' => 0.1],
        ['TEXT' => $r, 'BORDER_SIZE' => 0.3],
        ['TEXT' => $r, 'BORDER_SIZE' => 0.5],
        ['TEXT' => $r, 'BORDER_SIZE' => 1.0],
        ['TEXT' => $r, 'BORDER_SIZE' => 1.5],
    ]);
}
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '4c  Left-only border - 5 widths');

$table = new Table($pdf);
$table->initialize($colsW, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'L', 'BORDER_SIZE' => 0.5]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'L', 'BACKGROUND_COLOR' => [252, 252, 252]]),
]);
$table->addHeader([
    ['TEXT' => '0.1'], ['TEXT' => '0.3'], ['TEXT' => '0.5'],
    ['TEXT' => '1.0'], ['TEXT' => '1.5'],
]);
foreach (['X', 'Y', 'Z'] as $r) {
    $table->addRow([
        ['TEXT' => $r, 'BORDER_SIZE' => 0.1],
        ['TEXT' => $r, 'BORDER_SIZE' => 0.3],
        ['TEXT' => $r, 'BORDER_SIZE' => 0.5],
        ['TEXT' => $r, 'BORDER_SIZE' => 1.0],
        ['TEXT' => $r, 'BORDER_SIZE' => 1.5],
    ]);
}
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '4d  Right-only border - 5 widths');

$table = new Table($pdf);
$table->initialize($colsW, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'R', 'BORDER_SIZE' => 0.5]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'R', 'BACKGROUND_COLOR' => [252, 252, 252]]),
]);
$table->addHeader([
    ['TEXT' => '0.1'], ['TEXT' => '0.3'], ['TEXT' => '0.5'],
    ['TEXT' => '1.0'], ['TEXT' => '1.5'],
]);
foreach (['P', 'Q', 'R'] as $r) {
    $table->addRow([
        ['TEXT' => $r, 'BORDER_SIZE' => 0.1],
        ['TEXT' => $r, 'BORDER_SIZE' => 0.3],
        ['TEXT' => $r, 'BORDER_SIZE' => 0.5],
        ['TEXT' => $r, 'BORDER_SIZE' => 1.0],
        ['TEXT' => $r, 'BORDER_SIZE' => 1.5],
    ]);
}
$table->close();

// ============================================================================
// PAGE 5 – BORDER COLORS
// ============================================================================
PdfLayout::section($pdf, '5. Border Colors', true);

// A set of vivid colors to use
$colorPalette = [
    'Red' => [220, 30, 30],
    'Green' => [20, 160, 20],
    'Blue' => [30, 60, 200],
    'Orange' => [230, 110, 0],
    'Purple' => [130, 0, 180],
    'Teal' => [0, 150, 150],
    'Gold' => [200, 160, 0],
    'Maroon' => [140, 0, 0],
    'Navy' => [0, 0, 120],
    'Olive' => [80, 100, 0],
];

PdfLayout::sub($pdf, '5a  All-borders with 10 different colors (one table each, side-by-side style)');

$smallCols = [55, 55, 60];
foreach ($colorPalette as $colorName => $rgb) {
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->Cell(0, 4, "  Color: $colorName  [{$rgb[0]},{$rgb[1]},{$rgb[2]}]", 0, 1);
    $pdf->SetFont('helvetica', '', 9);

    $table = new Table($pdf);
    $table->initialize($smallCols, [
        'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
        'HEADER' => array_merge($baseHdr, [
            'BORDER_TYPE' => '1',
            'BORDER_SIZE' => 0.5,
            'BORDER_COLOR' => $rgb,
            'BACKGROUND_COLOR' => [240, 240, 240],
            'TEXT_COLOR' => [0, 0, 0],
        ]),
        'ROW' => array_merge($baseCell, [
            'BORDER_TYPE' => '1',
            'BORDER_SIZE' => 0.5,
            'BORDER_COLOR' => $rgb,
            'BACKGROUND_COLOR' => [255, 255, 255],
        ]),
    ]);
    $table->addHeader([['TEXT' => 'Col A'], ['TEXT' => 'Col B'], ['TEXT' => 'Col C']]);
    $table->addRow([['TEXT' => 'Data 1'], ['TEXT' => 'Data 2'], ['TEXT' => 'Data 3']]);
    $table->addRow([['TEXT' => 'Data 4'], ['TEXT' => 'Data 5'], ['TEXT' => 'Data 6']]);
    $table->close();
    PdfLayout::gap($pdf, 3);
}

// ============================================================================
// PAGE 6 – BACKGROUND COLORS WITH BORDERS
// ============================================================================
PdfLayout::section($pdf, '6. Background Colors combined with Various Borders', true);

PdfLayout::sub($pdf, '6a  Alternating row backgrounds - bottom border');

$bgLight = [245, 245, 245];
$bgWhite = [255, 255, 255];

$table = new Table($pdf);
$table->initialize($colsAsym, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.8,
        'BORDER_COLOR' => [0, 0, 0],
        'BACKGROUND_COLOR' => [30, 60, 120],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [180, 180, 180],
        'BACKGROUND_COLOR' => $bgWhite,
    ]),
]);
$table->addHeader([
    ['TEXT' => '#'],
    ['TEXT' => 'Article Name'],
    ['TEXT' => 'Category'],
    ['TEXT' => 'Price'],
]);
$rows = [
    ['1', 'Stainless Steel Bolt', 'Fasteners', '0.12'],
    ['2', 'Hex Nut M8', 'Fasteners', '0.08'],
    ['3', 'Rubber Gasket 10mm', 'Seals', '0.45'],
    ['4', 'Bearing 6002-2RS', 'Bearings', '2.30'],
    ['5', 'Drive Belt 1000mm', 'Drive', '8.50'],
    ['6', 'Oil Seal 25x35x7', 'Seals', '1.20'],
];
foreach ($rows as $idx => $r) {
    $bg = ($idx % 2 === 0) ? $bgWhite : $bgLight;
    $table->addRow([
        ['TEXT' => $r[0], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[1], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[2], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[3], 'BACKGROUND_COLOR' => $bg, 'TEXT_ALIGN' => 'R'],
    ]);
}
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '6b  Rich colored backgrounds - all borders');

$bgRows = [
    [[255, 230, 230], [255, 245, 245], [230, 255, 230], [245, 255, 245]],
    [[230, 230, 255], [245, 245, 255], [255, 255, 220], [255, 255, 235]],
    [[255, 235, 205], [255, 245, 225], [225, 245, 255], [240, 250, 255]],
];

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 0.4,
        'BORDER_COLOR' => [80, 80, 80],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [140, 140, 140],
    ]),
]);
$table->addHeader([['TEXT' => 'Red tint'], ['TEXT' => 'Blue tint'], ['TEXT' => 'Yellow tint'], ['TEXT' => 'Cyan tint']]);
foreach ($bgRows as $bgRow) {
    $table->addRow([
        ['TEXT' => 'Cell', 'BACKGROUND_COLOR' => $bgRow[0]],
        ['TEXT' => 'Cell', 'BACKGROUND_COLOR' => $bgRow[1]],
        ['TEXT' => 'Cell', 'BACKGROUND_COLOR' => $bgRow[2]],
        ['TEXT' => 'Cell', 'BACKGROUND_COLOR' => $bgRow[3]],
    ]);
}
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '6c  No background (transparent) - bottom border only');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.6,
        'BORDER_COLOR' => [0, 0, 0],
        'BACKGROUND_COLOR' => false,
        'TEXT_COLOR' => [0, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [0, 0, 0],
        'BACKGROUND_COLOR' => false,
    ]),
]);
$table->addHeader([['TEXT' => 'Article'], ['TEXT' => 'Qty'], ['TEXT' => 'Note']]);
$table->addRow([['TEXT' => 'Pen'], ['TEXT' => '5'], ['TEXT' => 'Blue ink']]);
$table->addRow([['TEXT' => 'Ruler'], ['TEXT' => '2'], ['TEXT' => '30 cm']]);
$table->addRow([['TEXT' => 'Eraser'], ['TEXT' => '10'], ['TEXT' => 'White']]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '6d  Mixed: some cells with background, some transparent');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [100, 100, 100],
        'BACKGROUND_COLOR' => false,
    ]),
]);
$table->addHeader([['TEXT' => 'A'], ['TEXT' => 'B'], ['TEXT' => 'C'], ['TEXT' => 'D']]);
$table->addRow([
    ['TEXT' => 'Filled', 'BACKGROUND_COLOR' => [220, 240, 255]],
    ['TEXT' => 'Transparent', 'BACKGROUND_COLOR' => false],
    ['TEXT' => 'Filled', 'BACKGROUND_COLOR' => [255, 240, 220]],
    ['TEXT' => 'Transparent', 'BACKGROUND_COLOR' => false],
]);
$table->addRow([
    ['TEXT' => 'Transparent', 'BACKGROUND_COLOR' => false],
    ['TEXT' => 'Filled', 'BACKGROUND_COLOR' => [220, 255, 220]],
    ['TEXT' => 'Transparent', 'BACKGROUND_COLOR' => false],
    ['TEXT' => 'Filled', 'BACKGROUND_COLOR' => [255, 220, 255]],
]);
$table->addRow([
    ['TEXT' => 'Filled', 'BACKGROUND_COLOR' => [255, 255, 200]],
    ['TEXT' => 'Filled', 'BACKGROUND_COLOR' => [200, 255, 255]],
    ['TEXT' => 'Transparent', 'BACKGROUND_COLOR' => false],
    ['TEXT' => 'Transparent', 'BACKGROUND_COLOR' => false],
]);
$table->close();

// ============================================================================
// PAGE 7 – PER-CELL BORDER OVERRIDES (mixed types, widths, colors per cell)
// ============================================================================
PdfLayout::section($pdf, '7. Per-Cell Border Overrides', true);

PdfLayout::sub($pdf, '7a  Every cell has a different border type');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.4]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([['TEXT' => 'B'], ['TEXT' => 'T'], ['TEXT' => 'L'], ['TEXT' => 'R']]);
$table->addRow([
    ['TEXT' => 'Bottom', 'BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 0]],
    ['TEXT' => 'Top', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [200, 0, 0]],
    ['TEXT' => 'Left', 'BORDER_TYPE' => 'L', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 200]],
    ['TEXT' => 'Right', 'BORDER_TYPE' => 'R', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 180, 0]],
]);
$table->addRow([
    ['TEXT' => 'BL', 'BORDER_TYPE' => 'BL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [130, 0, 130]],
    ['TEXT' => 'TR', 'BORDER_TYPE' => 'TR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 120, 120]],
    ['TEXT' => 'TB', 'BORDER_TYPE' => 'TB', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [180, 90, 0]],
    ['TEXT' => 'LR', 'BORDER_TYPE' => 'LR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [80, 80, 0]],
]);
$table->addRow([
    ['TEXT' => 'BLR', 'BORDER_TYPE' => 'BLR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 50, 150]],
    ['TEXT' => 'TLR', 'BORDER_TYPE' => 'TLR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [150, 50, 0]],
    ['TEXT' => 'TBL', 'BORDER_TYPE' => 'TBL', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 120, 60]],
    ['TEXT' => 'TBLR', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [60, 0, 120]],
]);
$table->addRow([
    ['TEXT' => 'none', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [240, 240, 240]],
    ['TEXT' => 'none', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [240, 240, 240]],
    ['TEXT' => 'none', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [240, 240, 240]],
    ['TEXT' => 'none', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [240, 240, 240]],
]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '7b  Per-cell: mixed border widths and colors on a single table');

$borderVariants = [
    ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.1, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [250, 250, 250]],
    ['BORDER_TYPE' => 'BL', 'BORDER_SIZE' => 0.3, 'BORDER_COLOR' => [180, 0, 0], 'BACKGROUND_COLOR' => [255, 245, 245]],
    ['BORDER_TYPE' => 'TBR', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 120, 0], 'BACKGROUND_COLOR' => [245, 255, 245]],
    ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 180], 'BACKGROUND_COLOR' => [245, 245, 255]],
    ['BORDER_TYPE' => 'LR', 'BORDER_SIZE' => 1.0, 'BORDER_COLOR' => [150, 80, 0], 'BACKGROUND_COLOR' => [255, 248, 240]],
];
$table = new Table($pdf);
$table->initialize($colsW, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.4]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([
    ['TEXT' => 'B 0.1'], ['TEXT' => 'BL 0.3'], ['TEXT' => 'TBR 0.5'],
    ['TEXT' => 'All 0.8'], ['TEXT' => 'LR 1.0'],
]);
foreach (['Row 1', 'Row 2', 'Row 3'] as $rl) {
    $row = [];
    foreach ($borderVariants as $bv) {
        $row[] = array_merge(['TEXT' => $rl], $bv);
    }
    $table->addRow($row);
}
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '7c  Header row only vs data rows only border highlight');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 1.0,
        'BORDER_COLOR' => [220, 50, 50],
        'BACKGROUND_COLOR' => [255, 240, 240],
        'TEXT_COLOR' => [150, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => '0',
        'BACKGROUND_COLOR' => [255, 255, 255],
    ]),
]);
$table->addHeader([['TEXT' => 'Bold Header A'], ['TEXT' => 'Bold Header B'], ['TEXT' => 'Bold Header C']]);
$table->addRow([['TEXT' => 'No border on data rows'], ['TEXT' => 'Just the header'], ['TEXT' => 'is outlined']]);
$table->addRow([['TEXT' => 'Clean data area'], ['TEXT' => 'with no distractions'], ['TEXT' => 'look']]);
$table->close();

// ============================================================================
// PAGE 8 – MULTILINE CONTENT WITH VARIOUS BORDERS
// ============================================================================
PdfLayout::section($pdf, '8. Multiline Content with Various Borders', true);

PdfLayout::sub($pdf, '8a  Bottom border + multiline - verifying border stays at row bottom');

$table = new Table($pdf);
$table->initialize([30, 85, 55], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.7,
        'BORDER_COLOR' => [0, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [80, 80, 80],
        'BACKGROUND_COLOR' => [255, 255, 255],
    ]),
]);
$table->addHeader([['TEXT' => 'ID'], ['TEXT' => 'Description'], ['TEXT' => 'Remark']]);
$table->addRow([
    ['TEXT' => '001'],
    ['TEXT' => 'This description is intentionally long enough to wrap across multiple lines in order to demonstrate that the bottom border always appears at the very bottom of the tallest cell.'],
    ['TEXT' => 'Short remark'],
]);
$table->addRow([
    ['TEXT' => '002'],
    ['TEXT' => 'Medium length text that wraps once or twice.'],
    ['TEXT' => "Line one\nLine two\nLine three\nLine four"],
]);
$table->addRow([
    ['TEXT' => '003'],
    ['TEXT' => 'Single-line'],
    ['TEXT' => 'Single-line'],
]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '8b  All-borders + multiline - no border overlap between rows');

$table = new Table($pdf);
$table->initialize([30, 85, 55], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 0.5,
        'BORDER_COLOR' => [0, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [100, 100, 100],
        'BACKGROUND_COLOR' => [255, 255, 255],
    ]),
]);
$table->addHeader([['TEXT' => 'Ref'], ['TEXT' => 'Content'], ['TEXT' => 'Note']]);
$table->addRow([
    ['TEXT' => 'A'],
    ['TEXT' => 'Row with all borders and a long text that wraps to check there is no double-border artifact between rows.'],
    ['TEXT' => 'Check top and bottom borders on this row carefully'],
]);
$table->addRow([
    ['TEXT' => 'B'],
    ['TEXT' => 'Second multi-line row. The top border of this row should not overlap the bottom border of the previous one.'],
    ['TEXT' => "Note line 1\nNote line 2"],
]);
$table->addRow([
    ['TEXT' => 'C'],
    ['TEXT' => 'Third row - single line.'],
    ['TEXT' => 'End'],
]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '8c  BL borders + multiline + alternating backgrounds');

$table = new Table($pdf);
$table->initialize([25, 90, 55], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'BL',
        'BORDER_SIZE' => 0.6,
        'BORDER_COLOR' => [0, 60, 130],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => 'BL',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [0, 60, 130],
        'BACKGROUND_COLOR' => [255, 255, 255],
    ]),
]);
$table->addHeader([['TEXT' => '#'], ['TEXT' => 'Text'], ['TEXT' => 'Tag']]);
$dataRows = [
    ['1', 'A short text.', 'quick'],
    ['2', 'This text is quite a bit longer and will wrap to a second line at minimum width.', 'wrapped'],
    ['3', 'Even longer text to create a three-line cell for proper visual verification of the bottom border alignment across all cells in the row.', 'long'],
    ['4', 'Back to a short text.', 'end'],
];
foreach ($dataRows as $i => $r) {
    $bg = ($i % 2 === 0) ? [248, 250, 255] : [255, 255, 255];
    $table->addRow([
        ['TEXT' => $r[0], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[1], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[2], 'BACKGROUND_COLOR' => $bg],
    ]);
}
$table->close();

// ============================================================================
// PAGE 9 – COLSPAN + MIXED BORDERS
// ============================================================================
PdfLayout::section($pdf, '9. Colspan with Mixed Borders', true);

PdfLayout::sub($pdf, '9a  Colspan header + per-cell borders');

$table = new Table($pdf);
$table->initialize([40, 40, 45, 45], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.6, 'BORDER_COLOR' => [0, 0, 0]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.3, 'BORDER_COLOR' => [100, 100, 100], 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
// Two header rows: first merges cols 1+2 and cols 3+4
$table->addHeader([
    ['TEXT' => 'Group A', 'COLSPAN' => 2],
    ['TEXT' => '', 'TEXT' => ''],   // skipped by colspan
    ['TEXT' => 'Group B', 'COLSPAN' => 2],
    ['TEXT' => ''],                 // skipped by colspan
]);
$table->addHeader([
    ['TEXT' => 'Sub-1'], ['TEXT' => 'Sub-2'],
    ['TEXT' => 'Sub-3'], ['TEXT' => 'Sub-4'],
]);
$table->addRow([['TEXT' => 'v1'], ['TEXT' => 'v2'], ['TEXT' => 'v3'], ['TEXT' => 'v4']]);
$table->addRow([['TEXT' => 'v5'], ['TEXT' => 'v6'], ['TEXT' => 'v7'], ['TEXT' => 'v8']]);
$table->addRow([['TEXT' => 'v9'], ['TEXT' => 'v10'], ['TEXT' => 'v11'], ['TEXT' => 'v12']]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '9b  Colspan data cells spanning 2 and 3 columns - all borders');

$table = new Table($pdf);
$table->initialize([35, 35, 40, 30, 30], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.4]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.3, 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([
    ['TEXT' => 'A'], ['TEXT' => 'B'], ['TEXT' => 'C'],
    ['TEXT' => 'D'], ['TEXT' => 'E'],
]);
$table->addRow([
    ['TEXT' => 'Normal'],
    ['TEXT' => 'Span 2 cols', 'COLSPAN' => 2, 'BACKGROUND_COLOR' => [255, 240, 200]],
    ['TEXT' => ''],
    ['TEXT' => 'Normal'],
    ['TEXT' => 'Normal'],
]);
$table->addRow([
    ['TEXT' => 'Span 3 cols', 'COLSPAN' => 3, 'BACKGROUND_COLOR' => [200, 255, 210]],
    ['TEXT' => ''],
    ['TEXT' => ''],
    ['TEXT' => 'n'],
    ['TEXT' => 'n'],
]);
$table->addRow([
    ['TEXT' => 'a'],
    ['TEXT' => 'b'],
    ['TEXT' => 'c'],
    ['TEXT' => 'Span 2', 'COLSPAN' => 2, 'BACKGROUND_COLOR' => [210, 220, 255]],
    ['TEXT' => ''],
]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '9c  Colspan with bottom-only borders - mixed widths');

$table = new Table($pdf);
$table->initialize([55, 55, 60], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 100]]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.3, 'BORDER_COLOR' => [0, 0, 100], 'BACKGROUND_COLOR' => [250, 250, 255]]),
]);
$table->addHeader([
    ['TEXT' => 'Section', 'COLSPAN' => 3],
    ['TEXT' => ''], ['TEXT' => ''],
]);
$table->addHeader([['TEXT' => 'Left'], ['TEXT' => 'Middle'], ['TEXT' => 'Right']]);
$table->addRow([
    ['TEXT' => 'Row 1 - left cell with a longer text that wraps'],
    ['TEXT' => 'Middle content'],
    ['TEXT' => 'Right'],
]);
$table->addRow([
    ['TEXT' => 'Row 2'],
    ['TEXT' => 'Span whole row', 'COLSPAN' => 2, 'BACKGROUND_COLOR' => [230, 240, 255]],
    ['TEXT' => ''],
]);
$table->addRow([
    ['TEXT' => 'Row 3 - left'],
    ['TEXT' => 'Middle'],
    ['TEXT' => 'Right - longer note text'],
]);
$table->close();

// ============================================================================
// PAGE 10 – REAL-WORLD STYLE TABLES
// ============================================================================
PdfLayout::section($pdf, '10. Real-World Style Examples', true);

// ---------- 10a. Invoice-style -----------------------------------------------
PdfLayout::sub($pdf, '10a  Invoice-style table');

$table = new Table($pdf);
$table->initialize([12, 80, 22, 22, 22, 12], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => [
        'TEXT_COLOR' => [255, 255, 255],
        'TEXT_SIZE' => 8,
        'TEXT_FONT' => 'helvetica',
        'TEXT_TYPE' => 'B',
        'TEXT_ALIGN' => 'C',
        'VERTICAL_ALIGN' => 'M',
        'LINE_SIZE' => 4,
        'BACKGROUND_COLOR' => [25, 55, 100],
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.6,
        'BORDER_COLOR' => [25, 55, 100],
        'PADDING_TOP' => 3,
        'PADDING_BOTTOM' => 3,
        'PADDING_LEFT' => 2,
        'PADDING_RIGHT' => 2,
    ],
    'ROW' => [
        'TEXT_COLOR' => [30, 30, 30],
        'TEXT_SIZE' => 8,
        'TEXT_FONT' => 'helvetica',
        'TEXT_TYPE' => '',
        'TEXT_ALIGN' => 'L',
        'VERTICAL_ALIGN' => 'M',
        'LINE_SIZE' => 4,
        'BACKGROUND_COLOR' => [255, 255, 255],
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.2,
        'BORDER_COLOR' => [180, 180, 180],
        'PADDING_TOP' => 2,
        'PADDING_BOTTOM' => 2,
        'PADDING_LEFT' => 2,
        'PADDING_RIGHT' => 2,
    ],
]);
$table->addHeader([
    ['TEXT' => '#'],
    ['TEXT' => 'Description'],
    ['TEXT' => 'Qty'],
    ['TEXT' => 'Unit'],
    ['TEXT' => 'Total'],
    ['TEXT' => 'VAT'],
]);
$invoiceRows = [
    ['1', 'Professional consulting - system architecture review and design', '8 h', '150.00', '1 200.00', '20%'],
    ['2', 'Software development - backend API implementation (sprint 1)', '40 h', '120.00', '4 800.00', '20%'],
    ['3', 'Software development - frontend React components', '24 h', '120.00', '2 880.00', '20%'],
    ['4', 'Code review and QA testing', '12 h', '100.00', '1 200.00', '20%'],
    ['5', 'Project management and documentation', '10 h', '90.00', '900.00', '20%'],
    ['6', 'Hosting infrastructure setup and configuration', '1 pcs', '350.00', '350.00', '20%'],
];
foreach ($invoiceRows as $i => $r) {
    $bg = ($i % 2 === 0) ? [255, 255, 255] : [248, 250, 255];
    $table->addRow([
        ['TEXT' => $r[0], 'TEXT_ALIGN' => 'C', 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[1], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[2], 'TEXT_ALIGN' => 'R', 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[3], 'TEXT_ALIGN' => 'R', 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[4], 'TEXT_ALIGN' => 'R', 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[5], 'TEXT_ALIGN' => 'C', 'BACKGROUND_COLOR' => $bg],
    ]);
}
// Totals row with strong border
$table->addRow([
    ['TEXT' => '', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 245, 255]],
    ['TEXT' => 'TOTAL', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 245, 255], 'TEXT_TYPE' => 'B'],
    ['TEXT' => '', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 245, 255]],
    ['TEXT' => '', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 245, 255]],
    ['TEXT' => '11 330.00', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 245, 255], 'TEXT_TYPE' => 'B', 'TEXT_ALIGN' => 'R'],
    ['TEXT' => '', 'BORDER_TYPE' => 'T', 'BORDER_SIZE' => 0.8, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 245, 255]],
]);
$table->close();
PdfLayout::gap($pdf);

// ---------- 10b. Dense data grid --------------------------------------------
PdfLayout::sub($pdf, '10b  Dense data grid - all borders, narrow columns, small font');

$gridCols = array_fill(0, 8, 21); // 8×21 = 168
$table = new Table($pdf);
$table->initialize($gridCols, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => [
        'TEXT_COLOR' => [255, 255, 255],
        'TEXT_SIZE' => 7,
        'TEXT_FONT' => 'helvetica',
        'TEXT_TYPE' => 'B',
        'TEXT_ALIGN' => 'C',
        'VERTICAL_ALIGN' => 'M',
        'LINE_SIZE' => 4,
        'BACKGROUND_COLOR' => [60, 60, 60],
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 0.3,
        'BORDER_COLOR' => [30, 30, 30],
        'PADDING_TOP' => 1,
        'PADDING_BOTTOM' => 1,
        'PADDING_LEFT' => 1,
        'PADDING_RIGHT' => 1,
    ],
    'ROW' => [
        'TEXT_COLOR' => [20, 20, 20],
        'TEXT_SIZE' => 7,
        'TEXT_FONT' => 'helvetica',
        'TEXT_TYPE' => '',
        'TEXT_ALIGN' => 'C',
        'VERTICAL_ALIGN' => 'M',
        'LINE_SIZE' => 4,
        'BACKGROUND_COLOR' => [255, 255, 255],
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 0.2,
        'BORDER_COLOR' => [170, 170, 170],
        'PADDING_TOP' => 1,
        'PADDING_BOTTOM' => 1,
        'PADDING_LEFT' => 1,
        'PADDING_RIGHT' => 1,
    ],
]);
$headers = array_map(fn($n) => ['TEXT' => "H$n"], range(1, 8));
$table->addHeader($headers);
$gridBg = [[255, 255, 255], [248, 248, 255], [255, 255, 248], [248, 255, 248]];
for ($row = 1; $row <= 8; $row++) {
    $cells = [];
    for ($col = 1; $col <= 8; $col++) {
        $bg = $gridBg[($row + $col) % 4];
        $cells[] = ['TEXT' => ($row * 10 + $col), 'BACKGROUND_COLOR' => $bg];
    }
    $table->addRow($cells);
}
$table->close();
PdfLayout::gap($pdf);

// ---------- 10c. Accent-column table ----------------------------------------
PdfLayout::sub($pdf, '10c  Accent left column - thick colored left border on first column, plain on rest');

$table = new Table($pdf);
$table->initialize([35, 50, 45, 40], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.6,
        'BORDER_COLOR' => [0, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.2,
        'BORDER_COLOR' => [180, 180, 180],
        'BACKGROUND_COLOR' => [255, 255, 255],
    ]),
]);
$table->addHeader([
    ['TEXT' => 'Category'],
    ['TEXT' => 'Label'],
    ['TEXT' => 'Value'],
    ['TEXT' => 'Change'],
]);
$accentData = [
    [['Revenue', [0, 80, 180]], 'Total sales', '€ 84 200', '+12%'],
    [['Revenue', [0, 80, 180]], 'Net margin', '€ 18 740', '+8%'],
    [['Costs', [180, 40, 0]], 'Personnel', '€ 42 100', '+3%'],
    [['Costs', [180, 40, 0]], 'Overhead', '€ 15 200', '+1%'],
    [['KPIs', [0, 130, 60]], 'NPS score', '72', '+5 pts'],
    [['KPIs', [0, 130, 60]], 'Churn rate', '1.8%', '-0.3%'],
];
foreach ($accentData as $i => $r) {
    [$catInfo, $label, $value, $change] = $r;
    [$cat, $accentColor] = $catInfo;
    $bg = ($i % 2 === 0) ? [255, 255, 255] : [248, 248, 248];
    $table->addRow([
        ['TEXT' => $cat, 'BORDER_TYPE' => 'BL', 'BORDER_SIZE' => 1.2, 'BORDER_COLOR' => $accentColor, 'BACKGROUND_COLOR' => $bg, 'TEXT_TYPE' => 'B', 'TEXT_COLOR' => $accentColor],
        ['TEXT' => $label, 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $value, 'BACKGROUND_COLOR' => $bg, 'TEXT_ALIGN' => 'R'],
        ['TEXT' => $change, 'BACKGROUND_COLOR' => $bg, 'TEXT_ALIGN' => 'R'],
    ]);
}
$table->close();
PdfLayout::gap($pdf);

// ---------- 10d. Zebra + thick outer border ----------------------------------
PdfLayout::sub($pdf, '10d  Zebra stripes with thick outer border outline');

$table = new Table($pdf);
$table->initialize([50, 60, 60], [
    'TABLE' => [
        'BORDER_TYPE' => '1',
        'BORDER_SIZE' => 1.2,
        'BORDER_COLOR' => [40, 40, 40],
        'TABLE_ALIGN' => 'L',
    ],
    'HEADER' => array_merge($baseHdr, [
        'BORDER_TYPE' => 'B',
        'BORDER_SIZE' => 0.7,
        'BORDER_COLOR' => [0, 0, 0],
    ]),
    'ROW' => array_merge($baseCell, [
        'BORDER_TYPE' => '0',
        'BACKGROUND_COLOR' => [255, 255, 255],
    ]),
]);
$table->addHeader([['TEXT' => 'Region'], ['TEXT' => 'Product'], ['TEXT' => 'Units Sold']]);
$zebraData = [
    ['North', 'Widget A', '1 234'],
    ['North', 'Widget B', '987'],
    ['South', 'Widget A', '2 100'],
    ['South', 'Widget C', '450'],
    ['East', 'Widget B', '1 678'],
    ['West', 'Widget A', '890'],
];
foreach ($zebraData as $i => $r) {
    $bg = ($i % 2 === 0) ? [255, 255, 255] : [235, 240, 250];
    $table->addRow([
        ['TEXT' => $r[0], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[1], 'BACKGROUND_COLOR' => $bg],
        ['TEXT' => $r[2], 'BACKGROUND_COLOR' => $bg, 'TEXT_ALIGN' => 'R'],
    ]);
}
$table->close();

// ============================================================================
// PAGE 11 – EDGE CASES
// ============================================================================
PdfLayout::section($pdf, '11. Edge Cases', true);

PdfLayout::sub($pdf, '11a  Single row + single column');

$table = new Table($pdf);
$table->initialize([170], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BACKGROUND_COLOR' => [255, 255, 240]]),
]);
$table->addHeader([['TEXT' => 'Single Column Header']]);
$table->addRow([['TEXT' => 'Only one row and one column in this table. Borders should be clean on all four sides.']]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '11b  Single row table - no header');

$table = new Table($pdf);
$table->initialize($cols3eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BACKGROUND_COLOR' => [240, 255, 240]]),
]);
$table->addRow([['TEXT' => 'Only cell A'], ['TEXT' => 'Only cell B'], ['TEXT' => 'Only cell C']]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '11c  Border size extremes: hairline 0.05 vs very thick 2.0');

$table = new Table($pdf);
$table->initialize([85, 85], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '0']),
]);
$table->addHeader([['TEXT' => 'Hairline (0.05)'], ['TEXT' => 'Very thick (2.0)']]);
$table->addRow([
    ['TEXT' => 'hairline border', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.05, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [255, 255, 240]],
    ['TEXT' => 'very thick border', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 2.0, 'BORDER_COLOR' => [80, 0, 80], 'BACKGROUND_COLOR' => [255, 240, 255]],
]);
$table->addRow([
    ['TEXT' => '0.05 mm line', 'BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.05, 'BORDER_COLOR' => [0, 0, 120], 'BACKGROUND_COLOR' => [248, 248, 255]],
    ['TEXT' => '2.0 mm baseline', 'BORDER_TYPE' => 'B', 'BORDER_SIZE' => 2.0, 'BORDER_COLOR' => [200, 60, 0], 'BACKGROUND_COLOR' => [255, 248, 245]],
]);
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '11d  All 15 distinct border-type strings in one table');

// B T L R BL BR BT LR TL TR BLR BRT BTL TLR TBLR(=1)
$allTypes = ['B', 'T', 'L', 'R', 'BL', 'BR', 'TB', 'LR', 'TL', 'TR', 'BLR', 'TBR', 'TBL', 'TLR', '1'];
// 3 cols × 5 rows  = 15 cells
$table = new Table($pdf);
$table->initialize([56, 57, 57], [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => 'B', 'BORDER_SIZE' => 0.5]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([['TEXT' => 'Type 1'], ['TEXT' => 'Type 2'], ['TEXT' => 'Type 3']]);
for ($i = 0; $i < 5; $i++) {
    $cells = [];
    for ($j = 0; $j < 3; $j++) {
        $idx = $i * 3 + $j;
        $t = $allTypes[$idx];
        $cells[] = [
            'TEXT' => $t,
            'BORDER_TYPE' => $t,
            'BORDER_SIZE' => 0.5,
            'BORDER_COLOR' => [40, 40, 40],
            'BACKGROUND_COLOR' => [248, 248, 248],
        ];
    }
    $table->addRow($cells);
}
$table->close();
PdfLayout::gap($pdf);

PdfLayout::sub($pdf, '11e  No-border cells mixed with full-border cells in same row');

$table = new Table($pdf);
$table->initialize($cols4eq, [
    'TABLE' => ['BORDER_TYPE' => 0, 'TABLE_ALIGN' => 'L'],
    'HEADER' => array_merge($baseHdr, ['BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.4]),
    'ROW' => array_merge($baseCell, ['BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]]),
]);
$table->addHeader([['TEXT' => 'A'], ['TEXT' => 'B'], ['TEXT' => 'C'], ['TEXT' => 'D']]);
$table->addRow([
    ['TEXT' => 'bordered', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 240, 240]],
    ['TEXT' => 'no border', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]],
    ['TEXT' => 'bordered', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 240, 240]],
    ['TEXT' => 'no border', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]],
]);
$table->addRow([
    ['TEXT' => 'no border', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]],
    ['TEXT' => 'bordered', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 240, 240]],
    ['TEXT' => 'no border', 'BORDER_TYPE' => '0', 'BACKGROUND_COLOR' => [255, 255, 255]],
    ['TEXT' => 'bordered', 'BORDER_TYPE' => '1', 'BORDER_SIZE' => 0.5, 'BORDER_COLOR' => [0, 0, 0], 'BACKGROUND_COLOR' => [240, 240, 240]],
]);
$table->close();

$pdf->Output();

