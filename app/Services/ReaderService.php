<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Zxing\QrReader;
use \Imagick;

class ReaderService
{
    const MEMORY_LIMIT = '512M';
    private string $tempImageFile = 'documents/tempImageFile.png';

    public function __construct()
    {
        $this->tempImageFile = public_path($this->tempImageFile);
        ini_set('memory_limit', self::MEMORY_LIMIT);
    }

    private function convertPDFtoPNG($pdf) {
        $imagick = new Imagick();
        $imagick->setResolution(100,100);
        $imagick->readImage($pdf);
        $imagick->setImageFormat('png32');
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $imagick->writeImage($this->tempImageFile);
        $imagick->clear();
    }

    private function detectQRCode() {
        $qrcode = new QrReader($this->tempImageFile);
        return (empty($qrcode->text())) ? null : $qrcode->text();
    }

    private function deletePNG() {
        if (File::exists($this->tempImageFile)) {
            File::delete($this->tempImageFile);
        }
    }

    public function readDocument($document) {
        $this->convertPDFtoPNG($document);
        $qrcode = $this->detectQrCode();
        $this->deletePNG();
        return $qrcode;
    }
}