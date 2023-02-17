@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row">
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
                                        <button type="submit" class="input-group-text btn btn-primary" id="uploadFile"
                                                disabled="true">Upload
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
                </div>
                <div class="col-12">
                    <form action="/create-demo" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h2>General Merchant Info</h2>
                        <div class="panel-heading">
                            <p>Please fill out this basic info on your merchant. This will help us create a more branded experience for the merchant</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchantName">Merchant Name</label>
                                    <input type="text" class="form-control bdr-brand-color-one" name="merchantName" id="merchantName"
                                        aria-describedby="merchantNameHelp" placeholder="Enter Merchant Name"
                                        value={{ old('merchantName') }}>
                                    <small id="merchantNameHelp" class="form-text text-muted">Enter the name you want to
                                        appear in different demo areas</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchantName">Your Email</label>
                                    <input type="email" class="form-control bdr-brand-color-one" name="demoEmail" id="demoEmail"
                                        aria-describedby="demoEmailHelp" placeholder="Enter Your Email"
                                        value={{ old('demoEmail') }}>
                                    <small id="demoEmailHelp" class="form-text text-muted">Enter your email. This can be used for demonstrating pay by link and other features</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchantLogoUrl">Merchant Logo URL</label>
                                    <input type="text" class="form-control bdr-brand-color-one" name="merchantLogoUrl" id="merchantLogoUrl"
                                        aria-describedby="merchantLogoUrlHelp" placeholder="Enter Merchant Logo URL"
                                        value={{ old('merchantLogoUrl') }}>
                                    <small id="merchantLogoUrlHelp" class="form-text text-muted"><a href="https://www.google.com/search?q=find+image+url+path" target="_blank">Put in a URL to the
                                        merchant's logo</a></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="brandColorOne">Brand Color #1</label>
                                    <input type="color" class="form-control bdr-brand-color-one" name="brandColorOne" id="brandColorOne"
                                        aria-describedby="brandColorOneHelp" placeholder="Enter Brand Color #1"
                                        value={{ old('brandColorOne') ? old('brandColorOne') : '#ffffff' }}>
                                    <small id="brandColorOneHelp" class="form-text text-muted">Color wheel for
                                        primary brand color</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="brandColorTwo">Brand Color #2</label>
                                    <input type="color" class="form-control bdr-brand-color-one" name="brandColorTwo" id="brandColorTwo"
                                        aria-describedby="brandColorTwoHelp" placeholder="Enter Brand Color #2"
                                        value={{ old('brandColorTwo') ? old('brandColorTwo') : '#000000' }}>
                                    <small id="brandColorTwoHelp" class="form-text text-muted">Color wheel for
                                        secondary brand color</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="checkoutScreenshot">Checkout Screenshot</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="checkoutScreenshot"
                                            id="checkoutScreenshot">
                                        <label class="custom-file-label bdr-brand-color-one" id="checkoutScreenshotLabel"
                                            for="checkoutScreenshot">Choose file</label>
                                    </div>
                                    <small id="checkoutScreenshotHelp" class="form-text text-muted">Screenshot image of
                                        their checkout</small>
                                    <img src="" id="screenshotThumb" width="100%" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="merchantLogoUrl">Checkout Amount</label>
                                    <input type="number" step='0.01' class="form-control bdr-brand-color-one" name="checkoutAmount" id="checkoutAmount"
                                        aria-describedby="checkoutAmountHelp" placeholder="Enter basket amount, IE 44.98"
                                        value={{ old('checkoutAmount') }}>
                                    <small id="checkoutAmountHelp" class="form-text text-muted">Put the basket amount here so it can show in the demo</small>
                                </div>
                            </div>
                        </div>
                        <h2>Your Merchant's Story</h2>
                        <h2>Features</h2>
                        <div class="row" id="featureList">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="allowedPaymentMethods">Allowed Payment Methods</label>
                                    <input type="text" class="form-control bdr-brand-color-one" name="allowedPaymentMethods" id="allowedPaymentMethods"
                                        aria-describedby="allowedPaymentMethods" placeholder="Comma delimited list of restrictive payment methods"
                                        value={{ old('allowedPaymentMethods') }}>
                                    <small id="allowedPaymentMethodsHelp" class="form-text text-muted">Leave this blank if you are happy to show all configured payment methods</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="technicalDemo" name="technicalDemo">
                                <label class="form-check-label" for="technicalDemo">Is this a technical demo?</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul"
                                        name="enableMoto" id="enableMoto" checked="checked">
                                    <label class="form-check-label checkbox-lg" for="enableMoto">MOTO</label>
                                </div>
                                <ul class="list-group sub-options" style="display: block;">
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableMoto.hostedCallCentre" id="enableMoto.hostedCallCentre" disabled>
                                            <label class="form-check-label" for="enableMoto.hostedCallCentre">Adyen Hosted
                                                Call Centre</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableMoto.customCallCenter" id="enableMoto.customCallCenter" checked="checked">
                                            <label class="form-check-label" for="enableMoto.customCallCenter">Custom Call
                                                Center</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enableMoto.pblMoto"
                                                id="enableMoto.pblMoto" checked="checked">
                                            <label class="form-check-label" for="enableMoto.pblMoto">PBL for MOTO</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enableMoto.enableIvr"
                                                id="enableMoto.enableIvr" disabled>
                                            <label class="form-check-label" for="enableMoto.enableIvr">IVR Example</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul"
                                        name="enableEcom" id="enableEcom" checked="checked">
                                    <label class="form-check-label checkbox-lg" for="enableEcom">ECOM</label>
                                </div>
                                <ul class="list-group sub-options" style="display: block;">
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enableEcom.enableDropIn"
                                                id="enableEcom.enableDropIn" checked="checked">
                                            <label class="form-check-label" for="enableEcom.enableDropIn">Drop-In</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableEcom.enableComponents" id="enableEcom.enableComponents" disabled>
                                            <label class="form-check-label"
                                                for="enableEcom.enableComponents">Components</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableEcom.enableTokenization" id="enableEcom.enableTokenization" disabled>
                                            <label class="form-check-label"
                                                for="enableEcom.enableTokenization">Tokenization</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableEcom.enableCashRegister" id="enableEcom.enableCashRegister" checked="checked">
                                            <label class="form-check-label" for="enableEcom.enableCashRegister">Cash
                                                Register (QR Code, PBL, TAPI)</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableEcom.adyenGiving" id="enableEcom.adyenGiving" checked="checked">
                                            <label class="form-check-label" for="enableEcom.adyenGiving">Adyen Giving</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableEcom.costEstimate" id="enableEcom.costEstimate">
                                            <label class="form-check-label" for="enableEcom.costEstimate">Cost Estimate</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                name="enableEcom.advancedGiftCard" id="enableEcom.advancedGiftCard">
                                            <label class="form-check-label" for="enableEcom.advancedGiftCard">Advanced Gift Card Flow</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul"
                                        name="enableAfp" id="enableAfp" disabled>
                                    <label class="form-check-label checkbox-lg" for="enableAfp">Adyen for Platforms</label>
                                </div>
                                <ul class="list-group sub-options">
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enableAfp.enableEcom"
                                                id="enableAfp.enableEcom">
                                            <label class="form-check-label" for="enableAfp.enableEcom">AFP Ecom</label>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="enableAfp.enablePos"
                                                id="enableAfp.enablePos">
                                            <label class="form-check-label" for="enableAfp.enablePos">AFP Pos</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul"
                                        name="enableHotelCheckin" id="enableHotelCheckin">
                                    <label class="form-check-label checkbox-lg" for="enableHotelCheckin">Hotel Adjustments</label>
                                </div>
                            </div>

                            <div class="col-md-6 mt-2">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input checkbox-lg expand-under-ul"
                                        name="enableinCar" id="enableinCar">
                                    <label class="form-check-label checkbox-lg" for="enableinCar">In Car Payments</label>
                                </div>
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
