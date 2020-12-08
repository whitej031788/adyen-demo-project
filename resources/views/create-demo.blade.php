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
          <h2>General Merchant Info</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="merchantName">Merchant Name</label>
                <input type="text" class="form-control" name="merchantName" id="merchantName" aria-describedby="merchantNameHelp" placeholder="Enter Merchant Name" value={{ old('merchantName') }}>
                <small id="merchantNameHelp" class="form-text text-muted">Enter the name you want to appear in different demo areas</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="merchantLogoUrl">Merchant Logo URL</label>
                <input type="text" class="form-control" name="merchantLogoUrl" id="merchantLogoUrl" aria-describedby="merchantLogoUrlHelp" placeholder="Enter Merchant Logo URL" value={{ old('merchantLogoUrl') }}>
                <small id="merchantLogoUrlHelp" class="form-text text-muted">Put in a URL to the merchant's logo (google it)</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="brandColorOne">Brand Color #1</label>
                <input type="text" class="form-control" name="brandColorOne" id="brandColorOne" aria-describedby="brandColorOneHelp" placeholder="Enter Brand Color #1" value={{ old('brandColorOne') }}>
                <small id="brandColorOneHelp" class="form-text text-muted">Hex color or color wheel for primary brand color</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="brandColorTwo">Brand Color #2</label>
                <input type="text" class="form-control" name="brandColorTwo" id="brandColorTwo" aria-describedby="brandColorTwoHelp" placeholder="Enter Brand Color #2" value={{ old('brandColorTwo') }}>
                <small id="brandColorTwoHelp" class="form-text text-muted">Hex color or color wheel for secondary brand color</small>
              </div>
            </div>
          </div>
          <h2>Features</h2>
          <div class="row">
            <div class="col-md-6">
              <div class="form-check">
                <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul" value="enableMoto" id="enableMoto">
                <label class="form-check-label checkbox-lg" for="enableMoto">MOTO</label>
              </div>
              <ul class="list-group sub-options">
                <li class="list-group-item">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="enableMoto.hostedCallCentre" id="enableMoto.hostedCallCentre">
                    <label class="form-check-label" for="enableMoto.hostedCallCentre">Adyen Hosted Call Centre</label>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="enableMoto.customCallCenter" id="enableMoto.customCallCenter">
                    <label class="form-check-label" for="enableMoto.customCallCenter">Custom Call Center</label>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="enableMoto.pblMoto" id="enableMoto.pblMoto">
                    <label class="form-check-label" for="enableMoto.pblMoto">PBL for MOTO</label>
                  </div>
                </li>
              </ul>
            </div>
            <div class="col-md-6">
              <div class="form-check">
                <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul" value="enableEcom" id="enableEcom">
                <label class="form-check-label checkbox-lg" for="enableEcom">ECOM</label>
              </div>
              <ul class="list-group sub-options">
                <li class="list-group-item">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="enableEcom.pblMoto" id="enableEcom.pblMoto">
                    <label class="form-check-label" for="enableMoto.pblMoto">Drop-In</label>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="enableMoto.pblMoto" id="enableMoto.pblMoto">
                    <label class="form-check-label" for="enableMoto.pblMoto">Components</label>
                  </div>
                </li>
                <li class="list-group-item">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" value="enableMoto.pblMoto" id="enableMoto.pblMoto">
                    <label class="form-check-label" for="enableMoto.pblMoto">Tokenization</label>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  @else
    <div class="row">
      <div class="col-12">
        <p>
          It seems you already have a demo session created / active for <span id="merchantNameFiller" class="merchant-name"></span>. If you would like to discard this session and create a new one, click here (this cannot be reversed):
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
