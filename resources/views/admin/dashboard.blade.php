@extends('layouts.app')

@section('title', 'Upload a document')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Documents</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Filename</th>
                                <th scope="col">QRCode</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created at</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                            <tr>
                                <th scope="row">{{ $document->id }}</th>
                                <td>{{ $document->filename }}</td>
                                <td>{{ $document->qrcode }}</td>
                                <td>{{ $document->status }}</td>
                                <td>{{ date('M-d-Y H:i:s', strtotime($document->created_at)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection