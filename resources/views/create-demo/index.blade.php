<!DOCTYPE HTML>
<!--
   Helios by HTML5 UP
   html5up.net | @ajlkn
   Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
   -->
<html>
   <head>
      <title>Adyen Demos</title>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
      <link rel="stylesheet" href="assets/css/main.css" />
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
      <noscript>
         <link rel="stylesheet" href="assets/css/noscript.css" />
      </noscript>
   </head>
   <body class="homepage is-preload">
      <img src="/img/Adyen_Corporate_Logo.svg.png" style="position: fixed; top: 15px; left: 15px; width: 100px;" />
      <div id="page-wrapper">
        <!-- Header -->
        <div id="header">
          <!-- Inner -->
          <div class="inner">
              <header>
                <h1>Welcome to <span><i class="adyen-brand"><b class="adyen-brand" style="color: #0abf53;"></b>MADE</i></h1>
                <small>Merchant Augmented Demo Experience</small>
                <hr />
                <p>Choose your demo journey now</p>
              </header>
              <footer>
                <a href="#step1" class="adyen-brand button circled scrolly">Start</a>
              </footer>
          </div>
        </div>
        <div class="wrapper one-hundred-height-viewport" id="step1" >
          <header>
              <h2 class="text-center">Sector or Vertical</h2>
          </header>
        </div>
        <div class="wrapper one-hundred-height-viewport hide-with-sizes" id="step2">
          <header>
              <h2 class="text-center" id="step2title"></h2>
          </header>
        </div>
        <div class="wrapper hide-with-sizes" id="step3">
          <header>
              <h2 class="text-center" id="step3title">Merchant Settings</h2>
          </header>
          <div class="row">
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
                            <!-- <input type="text" class="form-control bdr-brand-color-one" name="merchantName" id="merchantName"
                            aria-describedby="merchantNameHelp" placeholder="Enter Merchant Name"
                            value={{ old('merchantName') }}> -->
                            <input type="text" class="form-control bdr-brand-color-one" name="merchantName" id="merchantName"
                            aria-describedby="merchantNameHelp" placeholder="Enter Merchant Name"
                            value="Spotify">
                            <small id="merchantNameHelp" class="form-text text-muted">Enter the name you want to
                            appear in different demo areas</small>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                            <label for="demoEmail">Your Email</label>
                            <!-- <input type="email" class="form-control bdr-brand-color-one" name="demoEmail" id="demoEmail"
                            aria-describedby="demoEmailHelp" placeholder="Enter Your Email"
                            value={{ old('demoEmail') }}> -->
                            <input type="email" class="form-control bdr-brand-color-one" name="demoEmail" id="demoEmail"
                            aria-describedby="demoEmailHelp" placeholder="Enter Your Email"
                            value="jamie.white@adyen.com">
                            <small id="demoEmailHelp" class="form-text text-muted">Enter your email. This can be used for demonstrating pay by link and other features</small>
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                            <label for="merchantLogoUrl">Merchant Logo URL</label>
                            <!-- <input type="text" class="form-control bdr-brand-color-one" name="merchantLogoUrl" id="merchantLogoUrl"
                            aria-describedby="merchantLogoUrlHelp" placeholder="Enter Merchant Logo URL"
                            value={{ old('merchantLogoUrl') }}> -->
                            <input type="text" class="form-control bdr-brand-color-one" name="merchantLogoUrl" id="merchantLogoUrl"
                            aria-describedby="merchantLogoUrlHelp" placeholder="Enter Merchant Logo URL"
                            value="https://storage.googleapis.com/pr-newsroom-wp/1/2018/11/Spotify_Logo_CMYK_Green.png">
                            <small id="merchantLogoUrlHelp" class="form-text text-muted"><a href="https://www.google.com/search?q=find+image+url+path" target="_blank">Put in a URL to the
                            merchant's logo</a></small>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="form-group">
                            <label for="brandColorOne">Brand Color #1</label>
                            <!-- <input type="color" class="form-control bdr-brand-color-one" name="brandColorOne" id="brandColorOne"
                            aria-describedby="brandColorOneHelp" placeholder="Enter Brand Color #1"
                            value={{ old('brandColorOne') ? old('brandColorOne') : '#ffffff' }}> -->
                            <input type="color" class="form-control bdr-brand-color-one" name="brandColorOne" id="brandColorOne"
                            aria-describedby="brandColorOneHelp" placeholder="Enter Brand Color #1"
                            value="#1DB954">
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
                      <!-- <div class="col-md-6">
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
                      </div> -->
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
                    </div>
                    <div class="row mt-2">
                      <div class="col-md-12">
                          <a href="#step4" class="adyen-brand button circled scrolly stepaction-3">Proceed to Demo</a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
          </div>
        </div>
        <div class="wrapper one-hundred-height-viewport hide-with-sizes" id="step4">
          <div class="container">
            <header>
                <h4 id="demoInstructions"></h4>
            </header>
            <div class="row mt-2">
              <div class="col-md-12">
                  <button type="button" class="adyen-brand button circled" onclick="document.getElementById('demoForm').submit();">Start Demo</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Scripts -->
      <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
      <!-- <script src="assets/js/jquery.min.js"></script> -->
      <script src="assets/js/jquery.dropotron.min.js"></script>
      <script src="assets/js/browser.min.js"></script>
      <script src="assets/js/breakpoints.min.js"></script>
      <script src="assets/js/util.js"></script>
      <script src="assets/js/main.js"></script>
      <script src="assets/js/jquery.scrolly.min.js"></script>
      <script src="js/create-demo-new.js"></script>
      <script src="assets/js/jquery.scrollex.min.js"></script>
   </body>
</html>