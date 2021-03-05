@extends('layouts.main')

@section('content')
<div class="row">
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
    <div class="panel panel-primary">
      <div class="panel-heading">
      <p>If you have configuration from a previous demo, click the button below to upload an existing config file.</p>
      </div>
      <div class="panel-body">
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

        <form action="/create-demo" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="input-group mb-3">
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="configFile" id="configFile">
              <label class="custom-file-label" id="configFileLabel" for="configFile">Choose file</label>
            </div>
            <div class="input-group-append">
              <button type="submit" class="input-group-text btn btn-primary" id="uploadFile" disabled="true">Upload</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@if (!session('demo_session'))
<div class="row">
  <div class="col-12">
    <form action="/create-demo" method="POST" enctype="multipart/form-data">
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
        <div class="col-md-6">
          <div class="form-group">
            <label for="checkoutScreenshot">Checkout Screenshot</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="checkoutScreenshot" id="checkoutScreenshot">
              <label class="custom-file-label" id="checkoutScreenshotLabel" for="checkoutScreenshot">Choose file</label>
            </div>
            <small id="checkoutScreenshotHelp" class="form-text text-muted">Screenshot image of their checkout</small>
          </div>
        </div>
      </div>
      <h2>Features</h2>
      <div class="row">
        <div class="col-md-6">
          <div class="form-check">
            <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul" name="enableMoto" id="enableMoto">
            <label class="form-check-label checkbox-lg" for="enableMoto">MOTO</label>
          </div>
          <ul class="list-group sub-options">
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableMoto.hostedCallCentre" id="enableMoto.hostedCallCentre">
                <label class="form-check-label" for="enableMoto.hostedCallCentre">Adyen Hosted Call Centre</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableMoto.customCallCenter" id="enableMoto.customCallCenter">
                <label class="form-check-label" for="enableMoto.customCallCenter">Custom Call Center</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableMoto.pblMoto" id="enableMoto.pblMoto">
                <label class="form-check-label" for="enableMoto.pblMoto">PBL for MOTO</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableMoto.enableIvr" id="enableMoto.enableIvr">
                <label class="form-check-label" for="enableMoto.enableIvr">IVR Example</label>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-6">
          <div class="form-check">
            <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul" name="enableEcom" id="enableEcom">
            <label class="form-check-label checkbox-lg" for="enableEcom">ECOM</label>
          </div>
          <ul class="list-group sub-options">
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableEcom.enableDropIn" id="enableEcom.enableDropIn">
                <label class="form-check-label" for="enableEcom.enableDropIn">Drop-In</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableEcom.enableComponents" id="enableEcom.enableComponents">
                <label class="form-check-label" for="enableEcom.enableComponents">Components</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableEcom.enableTokenization" id="enableEcom.enableTokenization">
                <label class="form-check-label" for="enableEcom.enableTokenization">Tokenization</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableEcom.enableCashRegister" id="enableEcom.enableCashRegister">
                <label class="form-check-label" for="enableEcom.enableCashRegister">Cash Register (QR Code, PBL, TAPI)</label>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-6 mt-2">
          <div class="form-check">
            <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul" name="enableAfp" id="enableAfp">
            <label class="form-check-label checkbox-lg" for="enableAfp">Adyen for Platforms</label>
          </div>
          <ul class="list-group sub-options">
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableAfp.enableEcom" id="enableAfp.enableEcom">
                <label class="form-check-label" for="enableAfp.enableEcom">AFP Ecom</label>
              </div>
            </li>
            <li class="list-group-item">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="enableAfp.enablePos" id="enableAfp.enablePos">
                <label class="form-check-label" for="enableAfp.enablePos">AFP Pos</label>
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
