<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Services\ReaderService;

class ReaderServiceTest extends TestCase
{
    /**
     * A success test reading a QRCode inside a PDF
     *
     * @return void
     */
    public function test_returns_success_decode_qrcode()
    {
        $file = __DIR__ . "/qrcodes/single_qrcode.pdf";
        $document = new UploadedFile($file, 'single_qrcode.pdf', 'pdf', null, true);

		$reader = new ReaderService();
        $qrcode = $reader->readDocument($document);
		$this->assertSame("http://9288776200", $qrcode);
    }

    /**
     * A test that returns null in a PDF without QRCode
     *
     * @return void
     */
    public function test_returns_empty_in_a_empty_pdf()
    {
        $file = __DIR__ . "/qrcodes/no_qrcode.pdf";
        $document = new UploadedFile($file, 'no_qrcode.pdf', 'pdf', null, true);

		$reader = new ReaderService();
        $qrcode = $reader->readDocument($document);
		$this->assertSame(null, $qrcode);
    }
}
