<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ReaderService;
use App\Models\Document;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Save document information to the database
     */
    private function save($filename, $qrcode) {
        $document = new Document;
        $document->filename = $filename;
        $document->qrcode = $qrcode;
        $document->save();
    }

    public function store(Request $request) {
        if (!$request->hasFile('document') || !$request->file('document')->isValid()) {  
            return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'No documents have been sent!']);
        }
          
        $validator = Validator::make($request->all(), ['document' => ['required','mimes:pdf','max:2048']]);
        if ($validator->fails()) {
            return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'Document must be PDF and max 2048Kb!']);
        }

        $reader = new ReaderService($request->document);

        if (is_null($reader->getQrcode())) {
            return view('home', [ 'feedbackType' => 'danger', 'feedbackText' => 'QRCode not detected!']);
        }

        $this->save($reader->getFilename(), $reader->getQrcode());

        return view('home', [ 'feedbackType' => 'success', 'feedbackText' => 'QRCode detected successfuly: '.$reader->getQrcode()]);
    }
}
