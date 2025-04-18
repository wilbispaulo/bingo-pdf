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

    public function barCodePdf(string $cod, int $xOffSet, array $args)
    {
        $this->setCellPaddings($args['pl'], $args['pt'], $args['pr'], $args['pb']);
        $this->setCellMargins($args['ml'], $args['mt'], $args['mr'], $args['mb']);
        $this->write1DBarcode($cod, $args['type'], $args['xpos'] + $xOffSet, $args['ypos'], $args['w'], $args['h'], $args['xres'], $args['style'], '');
    }

    public function qrCodePdf(
        string $cod,
        int $xPos,
        int $yPos,
        int $width,
        int $height,
        string $ecc,
        array $args
    ) {
        $this->setCellPaddings($args['pl'], $args['pt'], $args['pr'], $args['pb']);
        $this->setCellMargins($args['ml'], $args['mt'], $args['mr'], $args['mb']);
        $this->write2DBarcode($cod, 'QRCODE,' . $ecc, $xPos, $yPos, $width, $height, $args, 'LTR', false);
    }

    public function textPdf(string $text, array $args)
    {
        // var_dump($args);
        $color = self::hexToRGB($args['color']);
        $bg = self::hexToRGB($args['bg']);
        $this->setTextColor($color[0], $color[1], $color[2]);
        $this->setFillColor($bg[0], $bg[1], $bg[2]);
        $this->setCellPaddings($args['pl'], $args['pt'], $args['pr'], $args['pb']);
        $this->setCellMargins($args['ml'], $args['mt'], $args['mr'], $args['mb']);
        $this->setFont($args['font'], $args['style'], $args['size']);
        $this->MultiCell($args['w'], $args['h'], $text, $args['border'], $args['align'], $args['fill'], $args['new'], $args['xpos'], $args['ypos'], true, 0, false, true, $args['maxh'], $args['valign'], false);
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
