<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Zxing\QrReader;
use \Imagick;

class ReaderService
{
    const MEMORY_LIMIT = '248M';
    const PATH_DOCUMENTS = 'documents/';
    const TEMP_IMAGE_FILE = self::PATH_DOCUMENTS.'tempImageFile.png';

    protected $filename;
    protected $qrcode = null;

    public function __construct($document = null)
    {
        ini_set('memory_limit', self::MEMORY_LIMIT);
        if (!is_null($document))
            $this->readDocument($document);
    }

    private function createFilename(string $originalName, string $extension) {
        return md5($originalName . strToTime('now')) . "." . $extension;
    }

    private function convertPDFtoPNG() {
        $imagick = new Imagick();
        $imagick->setResolution(100,100);
        $imagick->readImage(public_path(self::PATH_DOCUMENTS.$this->filename));
        $imagick->setImageFormat('png32');
        $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
        $imagick->writeImage(public_path(self::TEMP_IMAGE_FILE));
        $imagick->clear();
    }

    private function detectQRCode() {
        $qrcode = new QrReader(public_path(self::TEMP_IMAGE_FILE));
        if (!empty($qrcode->text()))
            $this->qrcode = $qrcode->text();
    }

    private function deletePNG() {
        if (File::exists(public_path(self::TEMP_IMAGE_FILE))) {
            File::delete(public_path(self::TEMP_IMAGE_FILE));
        }
    }

    private function savePDF($document) {
        $this->filename = $this->createFilename($document->getClientOriginalName(), $document->extension());
        $document->move(public_path(self::PATH_DOCUMENTS), $this->filename);
    }

    public function readDocument($document) {
        $this->savePDF($document);
        $this->convertPDFtoPNG();
        $this->detectQrCode();
        $this->deletePNG();
    }

    public function getQrcode() {
        return $this->qrcode;
    }

    public function getFilename() {
        return $this->filename;
    }
}