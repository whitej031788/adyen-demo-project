@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-12">
                <h1 class="text-center adyen-brand font-weight-bold">Adyen Demo Tool</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="h5">
                    This tool allows you to fully configure a demo that can be used with merchants.
                    Select the features you want to show the merchant below, then then hit "Submit".
                    Your demo will be configured based on what you select, and you will be sent to
                    the demo page.
                </p>
            </div>
            <div class="col-12">
            <hr />
            </div>
        </div>
        @if (!session('demo_session') || $editMode === 'true')
            <div class="row">
                <div class="col-12">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if ($editMode === 'false')
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <p>If you have configuration from a previous demo, click the button below to upload an existing
                                configuration file; <b>most people can simply proceed to the below</b>.</p>
                        </div>
                        <div class="panel-body">
                            <form action="/create-demo" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="configFile" id="configFile">
                                        <label class="custom-file-label bdr-brand-color-one" id="configFileLabel" for="configFile">Choose
                                            file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text btn btn-primary txt-brand-color-one bkg-brand-color-two" id="uploadFile"
                                                disabled="true">Upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                </div>
                @include('layouts.demo-inputs')
            </div>
        @else
            <div class="row">
            <div class="col-12">
                    <p>
                        It seems you already have a demo session created / active for <span id="merchantNameFiller"
                                                                                            class="merchant-name"></span>.
                        Please select how you would like to proceed:
                    </p>
                    <form action="/edit-demo" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-2">Edit Current Demo</button>
                    </form>
                    <a href="/">
                        <button type="button" class="btn btn-primary mt-2">Return To Existing Demo</button>
                    </a>
                    <form action="/delete-demo" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary mt-5">Delete Current Demo (cannot be reversed)</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
