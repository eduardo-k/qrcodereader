<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $documents = Document::all();
        return view('admin.dashboard', ['documents' => $documents]); 
    }
}
