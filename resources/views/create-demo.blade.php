@extends('layouts.main')

@section('content')
  <div class="row mt-5">
    <div class="col-12">
      <h1 class="text-center">Adyen Demo Tool</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <p>
        This tool allows you to fully configure a demo that can be used with merchants.
        Select the features you want to show the merchant below, then then hit "Submit".
        Your demo will be configured based on what you select, and you will be sent to
        the demo page.
      </p>
    </div>
  </div>
  @if (!session('demo_session'))
    <div class="row">
      <div class="col-12">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>
      <div class="col-12">
        <form action="/create-demo" method="POST">
          @csrf
          <div class="form-group">
            <label for="merchantName">Merchant Name</label>
            <input type="text" class="form-control" name="merchantName" id="merchantName" aria-describedby="merchantNameHelp" placeholder="Enter Merchant Name" value={{ old('merchantName') }}>
            <small id="merchantNameHelp" class="form-text text-muted">Enter the name you want to appear in different demo areas</small>
          </div>
          <div class="form-group">
            <label for="merchantLogoUrl">Merchant Logo URL</label>
            <input type="text" class="form-control" name="merchantLogoUrl" id="merchantLogoUrl" aria-describedby="merchantLogoUrlHelp" placeholder="Enter Merchant Logo URL" value={{ old('merchantLogoUrl') }}>
            <small id="merchantLogoUrlHelp" class="form-text text-muted">Put in a URL to the merchant's logo (google it)</small>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  @else
    <div class="row">
      <div class="col-12">
        <p>
          It seems you already have a demo session created / active for <span id="merchantName" class="merchant-name"></span>. If you would like to discard this session and create a new one, click here (this cannot be reversed):
        </p>
        <form action="/delete-demo" method="POST">
          @csrf
          <button type="submit" class="btn btn-primary">Delete Current Demo</button>
        </form>
      </div>
      <div class="col-12">
        <p>If you want to return to your existing demo session, click here:</p>
        <a href="/"><button type="button" class="btn btn-primary">Return To Existing Demo</button></a>
      </div>
    </div>
  @endif
@endsection
