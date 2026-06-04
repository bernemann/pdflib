<?php

/**
 * Specific test for BL (Bottom-Left) borders to verify no cutoff
 */
require_once __DIR__ . '/../autoload.php';

use EvoSys21\PdfLib\Fpdf\Pdf;
use EvoSys21\PdfLib\Table;

$pdf = new Pdf();
$pdf->SetAuthor('EvoSys21');
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(0, 0, 0);
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'BL Border Test - Multiline Cells', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(5);

$pdf->Cell(0, 5, 'Each cell has ONLY Bottom and Left borders. Background should NOT cut off borders.', 0, 1, 'L');
$pdf->Ln(5);

$table = new Table($pdf);
$table->initialize([30, 80, 60], [
    'TABLE' => [
        'TABLE_ALIGN' => 'L',
        'BORDER_TYPE' => 0,
        'BORDER_SIZE' => 1,
    ],
    'HEADER' => [
        'BORDER_TYPE' => 'BL',
        'BORDER_SIZE' => 1,
        'BORDER_COLOR' => [0, 0, 200],
        'BACKGROUND_COLOR' => [220, 220, 255],
        'TEXT_TYPE' => 'B',
        'PADDING_TOP' => 3,
        'PADDING_BOTTOM' => 3,
        'PADDING_LEFT' => 3,
        'PADDING_RIGHT' => 3,
    ],
    'ROW' => [
        'BORDER_TYPE' => 'BL',
        'BORDER_SIZE' => 1,
        'BORDER_COLOR' => [0, 0, 200],
        'BACKGROUND_COLOR' => [255, 255, 255],
        'PADDING_TOP' => 3,
        'PADDING_BOTTOM' => 3,
        'PADDING_LEFT' => 3,
        'PADDING_RIGHT' => 3,
    ],
]);

$table->addHeader([
    ['TEXT' => 'No.'],
    ['TEXT' => 'Description'],
    ['TEXT' => 'Notes'],
]);

$table->addRow([
    ['TEXT' => '1', 'TEXT_ALIGN' => 'C'],
    ['TEXT' => 'This is a long description that wraps to multiple lines to test if the bottom border from the previous row gets cut off by the background color.'],
    ['TEXT' => 'Watch the borders closely!'],
]);

$table->addRow([
    ['TEXT' => '2', 'TEXT_ALIGN' => 'C'],
    ['TEXT' => 'Another multiline cell. The background fill should be INSET so it does not overwrite the bottom border of the row above.'],
    ['TEXT' => "Multiple\nlines\nhere\ntoo"],
]);

$table->addRow([
    ['TEXT' => '3', 'TEXT_ALIGN' => 'C'],
    ['TEXT' => 'Short text'],
    ['TEXT' => 'All borders should be clean and continuous with no gaps or double-thickness.'],
]);

$table->addRow([
    ['TEXT' => '4', 'TEXT_ALIGN' => 'C'],
    ['TEXT' => 'Final row with enough text to wrap naturally and ensure all borders render correctly without artifacts or overlaps.'],
    ['TEXT' => 'Perfect!'],
]);

$table->close();

if (PHP_SAPI === 'cli') {
    $outputFile = __DIR__ . '/test-bl-border-specific.pdf';
    $pdf->Output('F', $outputFile);
    echo "PDF written to: {$outputFile}\n";
    echo "Open the PDF and verify:\n";
    echo "  - Bottom borders are NOT cut off\n";
    echo "  - Left borders are NOT cut off\n";
    echo "  - No double-thickness borders\n";
    echo "  - Background color does not overlap borders\n";
    return;
}

$pdf->Output();

