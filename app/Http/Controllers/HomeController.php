<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ReaderService;
use App\Models\Document;

class HomeController extends Controller
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
     * @return string
     */
    private function createFilename($document) {
        return md5($document->getClientOriginalName() . strToTime('now')) . "." . $document->extension();
    }

    private function savePDF($document, $filename) {
        $document->move(public_path(self::PATH_DOCUMENTS), $filename);
    }

    /**
     * Save document information to the database
     * 
     * @return void
     */
    private function save($filename, $qrcode) {
        $document = new Document;
        $document->filename = $filename;
        $document->qrcode = $qrcode;
        $document->save();
    }

    /**
     * A PDF has been uploaded. So read it and try to get the QRCode
     */
    public function store(Request $request) {
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
    }
}
