@extends('layouts.app')

@section('content')
<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-6 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3">Send us a PDF</h1>
            <p class="col-lg-10 fs-4">Upload a PDF document and we'll identify the QRCode inside it.</p>
        </div>
        <div class="col-md-10 mx-auto col-lg-6">
            <form action="/" method="POST" class="p-4 p-md-5 border rounded-3 bg-light" enctype="multipart/form-data">
                @csrf
                <div class="form-floating mb-3">
                    <input type="file" class="form-control-file" id="document" name="document">
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Submit</button>
            </form>

            @if(isset($feedbackText))
                <div class="w-100">
                    <div class="alert alert-{{ $feedbackType }} text-center" role="alert">
                        {{ $feedbackText }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection