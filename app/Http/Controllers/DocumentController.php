<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use App\Services\ReaderService;
use App\Models\Document;

class DocumentController extends Controller
{
    const PATH_DOCUMENTS = 'documents/';

    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Create a unique filename
     * 
     * @param UploadedFile $document
     * @return string
     */
    private function createFilename(UploadedFile $document) {
        return md5($document->getClientOriginalName() . strToTime('now')) . "." . $document->extension();
    }

    /**
     * Save uploaded file to the documents folder
     * 
     * @param UploadedFile $document
     * @param string $filename
     * @return void
     */
    private function savePDF(UploadedFile $document, string $filename) {
        $document->move(public_path(self::PATH_DOCUMENTS), $filename);
    }

    /**
     * Save document information to the database
     * 
     * @param string $filename
     * @param string $qrcode
     * @return void
     */
    private function save(string $filename, string $qrcode) {
        $document = new Document;
        $document->filename = $filename;
        $document->qrcode = $qrcode;
        $document->save();
    }

    /**
     * A PDF has been uploaded. So read it and try to get the QRCode
     */
    public function store(Request $request) {
        try {
            if (!$request->hasFile('document') || !$request->file('document')->isValid()) {  
                return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'No documents have been sent!']);
            }
            
            $validator = Validator::make($request->all(), ['document' => ['required','mimes:pdf','max:2048']]);
            if ($validator->fails()) {
                return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'Document must be PDF and max 2048Kb!']);
            }

            $reader = new ReaderService();
            $qrcode = $reader->readDocument($request->file('document'));

            if (is_null($qrcode)) {
                return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'QRCode not detected!']);
            }

            $filename = $this->createFilename($request->document);
            $this->savePDF($request->document, $filename);
            $this->save($filename, $qrcode);

            return view('home', [ 'feedbackType' => 'success', 'feedbackText' => 'QRCode detected successfuly: '.$qrcode]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'Some error occurred! Please, contact admin']);
        }
    }
}
