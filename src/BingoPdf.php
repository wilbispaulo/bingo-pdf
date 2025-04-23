<?php

namespace BingoPdf;

use TCPDF;

class BingoPdf extends TCPDF
{
    private string $filePDF;
    public function __construct()
    {
        parent::__construct(
            'P',
            'mm',
            'A4',
            true,
            'UTF-8',
            false,
            true
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
    }

    public function imagePdf(
        string $file,
        int $xPos,
        int $yPos,
        int $width,
        int $height,
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
        int $xPos,
        int $yPos,
        int $width,
        int $height,
        string $align,
        string $txtColor,
        string $bgColor,
        array $args
    ) {
        // var_dump($args);
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
        int $xPos,
        int $yPos,
        int $width,
        int $height,
        array $args
    ) {
        // var_dump($args);
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

    public function render()
    {
        $this->Output($this->filePDF, 'D');
        $this->_destroy(true);
    }
}
