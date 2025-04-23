<?php

namespace BingoPdf;

use TCPDF;

class BingoPdf extends TCPDF
{
    private string $filePDF;
    public function __construct()
    {
        parent::__construct(
            'L',
            'mm',
            'A5',
            true,
            'UTF-8',
            false,
            false
        );

        $this->setCreator('Bingo PDF API');
        $this->setAuthor('Bingo PDF API');
        $this->setTitle('Cartelas Série');
        $this->setTitle('Bingo Quermesse 2025');
        $this->setPrintHeader(false);
        $this->setPrintFooter(false);
        $this->setAutoPageBreak(false);
        $this->setImageScale(1.25);
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $this->setMargins(0, 0, 0, true);
    }

    public function imageJpgPdf(
        string $file,
        ?int $xPos = 0,
        ?int $yPos = 0,
        float $width = 0,
        float $height = 0,
        string $pAlign = 'L',
    ) {
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $this->Image($file, $xPos, $yPos, $width, $height, 'JPG', '', 'LTR', 2, 300, $pAlign, false, false, 0, true, false, false, false);
    }

    public function imagePngPdf(
        string $file,
        ?int $xPos = null,
        ?int $yPos = null,
        int $width = 0,
        int $height = 0,
        string $pAlign = 'L',
    ) {
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $this->Image($file, $xPos, $yPos, $width, $height, 'PNG', '', 'LTR', 2, 300, $pAlign, false, false, 0, true, false, false, false);
    }

    public function barCodePdf(
        string $cod,
        int $xPos,
        int $yPos,
        int $width,
        int $height,
        string $type,
        ?float $xRes = null,
        array $style = []
    ) {
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $this->write1DBarcode($cod, $type, $xPos, $yPos, $width, $height, $xRes, $style, 'LTR');
    }

    public function qrCodePdf(
        string $cod,
        int $xPos,
        int $yPos,
        int $width,
        int $height,
        string $ecc,
        array $style = []
    ) {
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $this->write2DBarcode($cod, 'QRCODE,' . $ecc, $xPos, $yPos, $width, $height, $style, 'LTR', false);
    }

    public function textPdf(
        string $text,
        float $xPos,
        float $yPos,
        float $width,
        float $height,
        string $align,
        string $txtColor,
        string $bgColor,
        array $args
    ) {
        $args['font'] = ($args['font'] ?? 'helvetica');
        $args['style'] = ($args['style'] ?? '');
        $args['size'] = ($args['size'] ?? 12);
        $args['border'] = ($args['border'] ?? 0);
        $args['align'] = ($args['align'] ?? 'L');
        $args['fill'] = ($args['fill'] ?? false);
        $args['new'] = ($args['new'] ?? 0);
        $args['maxh'] = ($args['maxh'] ?? 0);
        $args['valign'] = ($args['valign'] ?? 'T');
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $color = self::hexToRGB($txtColor);
        $bg = self::hexToRGB($bgColor);
        $this->setTextColor($color[0], $color[1], $color[2]);
        $this->setFillColor($bg[0], $bg[1], $bg[2]);
        $this->setFont($args['font'], $args['style'], $args['size']);
        $this->MultiCell($width, $height, $text, $args['border'], $align, $args['fill'], $args['new'], $xPos, $yPos, true, 0, false, true, $args['maxh'], $args['valign'], false);
    }

    public function textBoxPdf(
        string $text,
        float $xPos,
        float $yPos,
        float $width,
        float $height,
        array $args
    ) {
        $args['font'] = ($args['font'] ?? 'helvetica');
        $args['style'] = ($args['style'] ?? '');
        $args['size'] = ($args['size'] ?? 12);
        $args['border'] = ($args['border'] ?? 0);
        $args['align'] = ($args['align'] ?? 'L');
        $args['fill'] = ($args['fill'] ?? false);
        $args['new'] = ($args['new'] ?? 0);
        $args['maxh'] = ($args['maxh'] ?? 0);
        $args['valign'] = ($args['valign'] ?? 'T');
        $this->setCellPaddings(0, 0, 0, 0);
        $this->setCellMargins(0, 0, 0, 0);
        $this->setFont($args['font'], $args['style'], $args['size']);
        $this->MultiCell($width, $height, $text, $args['border'], $args['align'], $args['fill'], $args['new'], $xPos, $yPos, true, 0, false, true, $args['maxh'], $args['valign'], false);
    }

    public function setTxtColor(string $txtColor)
    {
        $color = self::hexToRGB($txtColor);
        $this->setTextColor($color[0], $color[1], $color[2]);
    }

    public function setBgColor(string $bgColor)
    {
        $bg = self::hexToRGB($bgColor);
        $this->setFillColor($bg[0], $bg[1], $bg[2]);
    }

    public static function hexToRGB(string | int $hexColor): array | false
    {
        $regex = "/^#[0-9a-f]{6}$/i";
        if (is_int($hexColor)) {
            return [$hexColor, $hexColor, $hexColor];
        }
        if (strlen($hexColor) !== 7 or !preg_match($regex, $hexColor)) {
            return false;
        }
        $red = hexdec(substr($hexColor, 1, 2));
        $green = hexdec(substr($hexColor, 3, 2));
        $blue = hexdec(substr($hexColor, 5, 2));
        return [$red, $green, $blue];
    }

    public function setFilePDF(string $filePDF)
    {
        $this->filePDF = $filePDF;
    }

    public function render()
    {
        $pdf = $this->Output($this->filePDF, 'S');
        $this->_destroy(true);
        return $pdf;
    }
}
