<div class="col-12">
    <form id="demoForm" action="/create-demo" method="POST" enctype="multipart/form-data" onsubmit="return showInstructions();">
        @csrf
        <input type="hidden" name="merchantVertical" id="merchantVertical" value="" />
        <input type="hidden" name="merchantSubtype" id="merchantSubtype" value="" />
        <div class="container">
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
                    <label for="demoEmail">Your Email</label>
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
                    value={{ old('brandColorOne') ? old('brandColorOne') : '#F7F8F9' }}>
                    <small id="brandColorOneHelp" class="form-text text-muted">Color wheel for
                    primary brand color</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                    <label for="brandColorTwo">Brand Color #2</label>
                    <input type="color" class="form-control bdr-brand-color-one" name="brandColorTwo" id="brandColorTwo"
                    aria-describedby="brandColorTwoHelp" placeholder="Enter Brand Color #2"
                    value={{ old('brandColorTwo') ? old('brandColorTwo') : '#00112C' }}>
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
            <h3 class="mb-1" style="cursor: pointer;"><a style="width: 100%; color: black;" data-toggle="collapse" href="#terminalConfig">Terminal Configuration+</a></h3>
            <div class="row collapse" id="terminalConfig">
                <div class="col-md-12">
                    <p>
                        This demo can feature several terminal capabilities, ranging from QR codes on terminals to input and display requests that facilitate
                        Unified Commerce journeys. If you have terminals you want to pair your demo with, please enter their full serial numbers as they appear in
                        the back office here. Currently the demo can support two terminals, "Fixed" and "Mobile" - if you only have one terminal, input it into the "Fixed"
                        configuration. Please note the demo is currently associated with merchant account: <br /> <b>{{ $posMerchantAccount }}</b> <br />
                        So you must board your terminal to a store under that merchant account to allow the demo to access it.
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="terminalPoiid">Fixed Terminal</label>
                        <input type="text" class="form-control bdr-brand-color-one" name="terminalPoiid" id="terminalPoiid"
                        aria-describedby="terminalPoiid" placeholder="POIID of Fixed (primary) terminal"
                        value={{ old('terminalPoiid') }}>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="terminalPoiidTwo">Mobile Terminal</label>
                        <input type="text" class="form-control bdr-brand-color-one" name="terminalPoiidTwo" id="terminalPoiidTwo"
                        aria-describedby="terminalPoiidTwo" placeholder="POIID of Mobile (second) terminal"
                        value={{ old('terminalPoiidTwo') }}>
                        <small id="terminalPoiidTwoHelp" class="form-text text-muted">Leave this blank if you only have one terminal</small>
                    </div>
                </div>
            </div>
            <h3 class="mb-1" style="cursor: pointer;"><a style="width: 100%; color: black;" data-toggle="collapse" href="#featureList">Advanced Configuration+</a></h3>
            <div class="row collapse" id="featureList">
                <div class="col-md-6">
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
                                name="enableEcom.enableTokenization" id="enableEcom.enableTokenization">
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
            </div>
            <!-- This means it is the manual flow, so show submit button -->
            @if ($view_name == "create-demo")
                <div class="row mt-2">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary txt-brand-color-one bkg-brand-color-two">Submit</button>
                    </div>
                </div>
            @else
                <div class="row mt-2">
                    <div class="col-md-12">
                        <a href="#step4" class="adyen-brand button circled scrolly stepaction-3">Proceed to Demo</a>
                    </div>
                </div>
            @endif
        </div>
    </form>
</div>