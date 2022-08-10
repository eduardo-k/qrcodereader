<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
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

    /**
     * Convert a PDF file to PNG
     * 
     * @param UploadedFile $pdf
     * @return void
     */
    private function convertPDFtoPNG(UploadedFile $pdf) {
        $imagick = new Imagick();
        $imagick->setResolution(100,100);
        $imagick->readImage($pdf);
        $imagick->setImageFormat('png32');
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $imagick->writeImage($this->tempImageFile);
        $imagick->clear();
    }

    /**
     * Open the PNG temp file and try read QR Code
     * 
     * @return string|null
     */
    private function detectQRCode() {
        $qrcode = new QrReader($this->tempImageFile);
        return (empty($qrcode->text())) ? null : $qrcode->text();
    }

    /**
     * Delete the PNG temp file
     * 
     * @return void
     */
    private function deletePNG() {
        if (File::exists($this->tempImageFile)) {
            File::delete($this->tempImageFile);
        }
    }

    /**
     *  Read a file in PDF format, identifying a QR Code and translating it
     * 
     * @param UploadedFile $document
     * @return string
     */
    public function readDocument(UploadedFile $document) {
        $this->convertPDFtoPNG($document);
        $qrcode = $this->detectQrCode();
        $this->deletePNG();
        return $qrcode;
    }
}