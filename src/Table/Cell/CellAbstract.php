<?php

/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpUnused */

namespace EvoSys21\PdfLib\Table\Cell;

use EvoSys21\PdfLib\Factory;
use EvoSys21\PdfLib\Fpdf\Pdf;
use EvoSys21\PdfLib\Fpdf\PdfInterface;
use EvoSys21\PdfLib\Tools;
use EvoSys21\PdfLib\Validate;

/**
 * Pdf Table Cell Abstract Class
 *
 * @property string|int|float|null HEIGHT_LEFT_RW
 */
abstract class CellAbstract implements CellInterface
{
    protected array $propMap = [
        'ALIGN' => 'setAlign',
        'VERTICAL_ALIGN' => 'setAlignVertical',
        'COLSPAN' => 'setColSpan',
        'ROWSPAN' => 'setRowSpan',
        'HEIGHT' => 'setHeight',
        'PADDING' => 'setPadding',
        'PADDING_TOP' => 'setPaddingTop',
        'PADDING_RIGHT' => 'setPaddingRight',
        'PADDING_BOTTOM' => 'setPaddingBottom',
        'PADDING_LEFT' => 'setPaddingLeft',
        'BORDER_TYPE' => 'setBorderType',
        'BORDER_SIZE' => 'setBorderSize',
        'BORDER_COLOR' => 'setBorderColor',
        'BACKGROUND_COLOR' => 'setBackgroundColor',
    ];

    /**
     * Colspan
     *
     * @var int
     */
    protected $colSpan = 1;

    /**
     * Rowspan
     *
     * @var int
     */
    protected $rowSpan = 1;

    /**
     * @var float
     */
    protected $paddingTop = 0;

    /**
     * @var float
     */
    protected $paddingRight = 0;

    /**
     * @var float
     */
    protected $paddingBottom = 0;

    /**
     * @var float
     */
    protected $paddingLeft = 0;

    protected $backgroundColor = [255, 255, 255];

    /**
     * @var string|int
     */
    protected $borderType = '1';

    /**
     * @var float
     */
    protected $borderSize = 0.1;

    /**
     * @var string|array
     */
    protected $borderColor = [0, 0, 0];

    /**
     * @var string
     */
    protected $align = 'L';

    /**
     * @var string
     */
    protected $alignVertical = 'M';

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var array
     */
    protected $internValueSet = [];

    /**
     * @var float|int
     */
    protected $height = 0;

    /**
     * @var float|int
     */
    protected $cellWidth = 0;

    /**
     * @var float|int
     */
    protected $cellHeight = 0;

    /**
     * @var float|int
     */
    protected $cellDrawWidth = 0;

    /**
     * @var float|int
     */
    protected $cellDrawHeight = 0;

    /**
     * @var float|int
     */
    protected $contentWidth = 0;

    /**
     * @var float|int
     */
    protected $contentHeight = 0;

    /**
     * Default alignment is Middle Center
     *
     * @var string
     */
    protected $alignment = 'MC';

    /**
     * Pdf Interface
     *
     * @var Pdf
     */
    protected $pdf;

    /**
     * Pdf Interface
     *
     * @var PdfInterface
     */
    protected $pdfi;

    /**
     * If this cell will be skipped
     *
     * @var bool
     */
    protected $bSkip = false;

    /**
     * Whether the adjacent cell above has a bottom border (set by the table renderer).
     * Used to protect that border from being covered by this cell's fill.
     */
    protected bool $adjacentBorderTop = false;

    /**
     * Whether the adjacent cell to the left has a right border (set by the table renderer).
     * Used to protect that border from being covered by this cell's fill.
     */
    protected bool $adjacentBorderLeft = false;

    public function __construct($pdf)
    {
        if ($pdf instanceof PdfInterface) {
            $this->pdfi = $pdf;
            $this->pdf = $pdf->getPdfObject();
        } else {
            //it must be an instance of a pdf object
            $this->pdf = $pdf;
            $this->pdfi = Factory::pdfInterface($pdf);
        }
    }

    public function setProperties(array $values = []): CellInterface
    {
        $this->setInternValues($values, false);

        return $this;
    }

    /**
     * Sets the intern variable values
     *
     * @param array $values The values to be set
     * @param bool $checkSet If the values are already set, the values will NOT be set
     */
    protected function setInternValues(array $values = [], bool $checkSet = true)
    {
        foreach ($values as $key => $value) {
            if ($checkSet && $this->isInternValueSet($key)) {
                //property is already set, ignore the value
                continue;
            }

            $this->setInternValue($key, $value);
        }
    }

    /**
     * Returns true if the property is already set
     */
    protected function isInternValueSet(string $key): bool
    {
        return array_key_exists($key, $this->internValueSet);
    }

    /**
     * Marks the property as set
     */
    protected function markInternValueAsSet(string $key)
    {
        $this->internValueSet[$key] = true;
    }

    /**
     * Sets an intern value
     */
    protected function setInternValue($key, $value)
    {
        $this->markInternValueAsSet($key);

        if (isset($this->propMap[$key])) {
            call_user_func_array([
                $this,
                $this->propMap[$key],
            ], Tools::makeArray($value));

            return;
        }

        $method = 'set' . ucfirst($key);

        if (method_exists($this, $method)) {
            call_user_func_array([
                $this,
                $method,
            ], Tools::makeArray($value));

            return;
        }

        $this->properties[$key] = $value;
    }

    /**
     * Set image alignment.
     * It can be any combination of the 2 Vertical and Horizontal values:
     * Vertical values: TBM
     * Horizontal values: LRC
     */
    public function setAlign(string $alignment)
    {
        $this->alignment = strtoupper($alignment);
    }

    public function setColSpan(int $value): CellInterface
    {
        $this->colSpan = Validate::intPositive($value);

        return $this;
    }

    public function getColSpan(): int
    {
        return $this->colSpan;
    }

    public function setRowSpan(int $value): CellInterface
    {
        $this->rowSpan = Validate::intPositive($value);

        return $this;
    }

    public function getRowSpan(): int
    {
        return $this->rowSpan;
    }

    public function setCellWidth($value): CellInterface
    {
        $value = Validate::float($value, 0);

        $this->cellWidth = $value;

        if ($value > $this->getCellDrawWidth()) {
            $this->setCellDrawWidth($value);
        }

        return $this;
    }

    public function getCellWidth(): float
    {
        return $this->cellWidth;
    }

    public function setCellHeight($value): CellInterface
    {
        $value = Validate::float($value, 0);

        $this->cellHeight = $value;

        if ($value > $this->getCellDrawHeight()) {
            $this->setCellDrawHeight($value);
        }

        return $this;
    }

    public function getCellHeight(): float
    {
        return $this->cellHeight;
    }

    public function setCellDrawHeight($value): CellInterface
    {
        $value = Validate::float($value, 0);

        if ($this->getCellHeight() <= $value) {
            $this->cellDrawHeight = $value;
        }

        return $this;
    }

    public function getCellDrawHeight()
    {
        if ($this->height > 0) {
            return Validate::float($this->height, 0);
        }

        return $this->cellDrawHeight;
    }

    public function setCellDrawWidth($value): CellInterface
    {
        $value = Validate::float($value, 0);

        $this->cellDrawWidth = $value;
        $this->setCellWidth($value);

        return $this;
    }

    public function getCellDrawWidth()
    {
        return $this->cellDrawWidth;
    }

    public function setContentWidth($value): CellInterface
    {
        $this->contentWidth = Validate::float($value, 0);

        return $this;
    }

    public function getContentWidth()
    {
        return $this->contentWidth;
    }

    public function setContentHeight($value): CellInterface
    {
        $this->contentHeight = Validate::float($value, 0);

        return $this;
    }

    public function getContentHeight()
    {
        return $this->contentHeight;
    }

    public function setSkipped(bool $value): CellInterface
    {
        $this->bSkip = $value;

        return $this;
    }

    public function getSkipped(): bool
    {
        return $this->bSkip;
    }

    public function setAdjacentBorderTop(bool $value): void
    {
        $this->adjacentBorderTop = $value;
    }

    public function setAdjacentBorderLeft(bool $value): void
    {
        $this->adjacentBorderLeft = $value;
    }

    /**
     * Returns true if this cell's border type includes the given side ('T', 'B', 'L', 'R').
     */
    public function borderIncludesSide(string $side): bool
    {
        $bt = $this->getBorderType();
        if ($bt === 1 || $bt === '1') {
            return true;
        }
        if ($bt === 0 || $bt === '0' || $bt === '') {
            return false;
        }

        return strpos((string) $bt, $side) !== false;
    }

    public function __get($property)
    {
        if (isset($this->properties[$property])) {
            return $this->properties[$property];
        }

        return null;
    }

    public function __set($property, $value)
    {
        $this->setInternValue($property, $value);

        return $this;
    }

    public function isPropertySet($property): bool
    {
        if (isset($this->properties[$property])) {
            return true;
        }

        return false;
    }

    public function setDefaultValues(array $values = []): CellInterface
    {
        $this->setInternValues($values);

        return $this;
    }

    /**
     * Renders the base cell layout - Borders and Background Color
     */
    public function renderCellLayout()
    {
        $x = $this->pdf->GetX();
        $y = $this->pdf->GetY();

        //border size BORDER_SIZE
        $this->pdf->SetLineWidth($this->getBorderSize());

        if (! $this->isTransparent()) {
            //fill color = BACKGROUND_COLOR
            [$r, $g, $b] = $this->getBackgroundColor();
            $this->pdf->SetFillColor($r, $g, $b);
        }

        //Draw Color = BORDER_COLOR
        [$r, $g, $b] = $this->getBorderColor();
        $this->pdf->SetDrawColor($r, $g, $b);

        $borderType = $this->getBorderType();
        $width = $this->getCellDrawWidth();
        $height = $this->getCellDrawHeight();
        $borderSize = $this->getBorderSize();

        // For any border type other than 0 (no border) or 1 (all borders with Cell()),
        // draw manually to avoid overwriting adjacent borders
        if ($borderType !== 0 && $borderType !== '0' && $borderType !== 1 && $borderType !== '1') {
            $borderStr = (string) $borderType;
            $hasTop = strpos($borderStr, 'T') !== false;
            $hasBottom = strpos($borderStr, 'B') !== false;
            $hasLeft = strpos($borderStr, 'L') !== false;
            $hasRight = strpos($borderStr, 'R') !== false;

            // Inset on sides that have a border on THIS cell, or where an adjacent
            // cell already drew a border on the shared edge (to avoid covering it).
            if (! $this->isTransparent()) {
                $insetLeft = ($hasLeft || $this->adjacentBorderLeft) ? $borderSize / 2 : 0;
                $insetRight = $hasRight ? $borderSize / 2 : 0;
                $insetTop = ($hasTop || $this->adjacentBorderTop) ? $borderSize / 2 : 0;
                $insetBottom = $hasBottom ? $borderSize / 2 : 0;
                $this->pdf->Rect(
                    $x + $insetLeft,
                    $y + $insetTop,
                    max(0, $width - $insetLeft - $insetRight),
                    max(0, $height - $insetTop - $insetBottom),
                    'F'
                );
            }

            // Manually draw each requested border side
            if ($hasTop) {
                $this->pdf->Line($x, $y, $x + $width, $y);
            }
            if ($hasBottom) {
                $this->pdf->Line($x, $y + $height, $x + $width, $y + $height);
            }
            if ($hasLeft) {
                $this->pdf->Line($x, $y, $x, $y + $height);
            }
            if ($hasRight) {
                $this->pdf->Line($x + $width, $y, $x + $width, $y + $height);
            }
        } else {
            // For 0 (no border) or 1 (all sides), use standard Cell()
            $this->pdf->Cell(
                $this->getCellDrawWidth(),
                $this->getCellDrawHeight(),
                '',
                $borderType,
                0,
                '',
                ! $this->isTransparent()
            );
        }

        $this->pdf->SetXY($x, $y);
    }

    protected function isTransparent(): bool
    {
        return Tools::isFalse($this->getBackgroundColor());
    }

    public function copyProperties(CellAbstract $source)
    {
        $this->rowSpan = $source->getRowSpan();
        $this->colSpan = $source->getColSpan();

        $this->paddingTop = $source->getPaddingTop();
        $this->paddingRight = $source->getPaddingRight();
        $this->paddingBottom = $source->getPaddingBottom();
        $this->paddingLeft = $source->getPaddingLeft();

        $this->borderColor = $source->getBorderColor();
        $this->borderSize = $source->getBorderSize();
        $this->borderType = $source->getBorderType();

        $this->backgroundColor = $source->getBackgroundColor();

        $this->alignVertical = $source->getAlignVertical();
    }

    public function processContent()
    {
    }

    public function setPadding($top = 0, $right = 0, $bottom = 0, $left = 0): CellInterface
    {
        $this->setPaddingTop($top);
        $this->setPaddingRight($right);
        $this->setPaddingBottom($bottom);
        $this->setPaddingLeft($left);

        return $this;
    }

    public function setPaddingBottom($paddingBottom): CellInterface
    {
        $this->paddingBottom = Validate::float($paddingBottom, 0);

        return $this;
    }

    public function getPaddingBottom()
    {
        return $this->paddingBottom;
    }

    public function setPaddingLeft($paddingLeft): CellInterface
    {
        $this->paddingLeft = Validate::float($paddingLeft, 0);

        return $this;
    }

    public function getPaddingLeft()
    {
        return $this->paddingLeft;
    }

    public function setPaddingRight($paddingRight): CellInterface
    {
        $this->paddingRight = Validate::float($paddingRight, 0);

        return $this;
    }

    public function getPaddingRight()
    {
        return $this->paddingRight;
    }

    public function setPaddingTop($paddingTop): CellInterface
    {
        $this->paddingTop = Validate::float($paddingTop, 0);

        return $this;
    }

    public function getPaddingTop()
    {
        return $this->paddingTop;
    }

    public function setBorderSize($borderSize): CellInterface
    {
        $this->borderSize = Validate::float($borderSize, 0);

        return $this;
    }

    public function getBorderSize(): float
    {
        return $this->borderSize;
    }

    public function setBorderType($borderType): CellInterface
    {
        $this->borderType = $borderType;

        return $this;
    }

    public function getBorderType(): string
    {
        return $this->borderType;
    }

    public function setBorderColor($r, ?int $g = null, ?int $b = null): CellInterface
    {
        $this->borderColor = Tools::getColor($r, $g, $b);

        return $this;
    }

    public function getBorderColor()
    {
        return $this->borderColor;
    }

    public function setAlignVertical(string $alignVertical): CellInterface
    {
        $this->markInternValueAsSet('VERTICAL_ALIGN');
        $this->alignVertical = Validate::alignVertical($alignVertical);

        return $this;
    }

    public function getAlignVertical(): string
    {
        return $this->alignVertical;
    }

    public function setBackgroundColor($r, ?int $g = null, ?int $b = null): CellInterface
    {
        $this->backgroundColor = Tools::getColor($r, $g, $b);

        return $this;
    }

    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    public function split($rowHeight, $maxHeight): array
    {
        return [$this, 0];
    }

    public function getDefaultValues(): array
    {
        return [];
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }
}
