<!DOCTYPE HTML>
<html>
   <head>
      <title>Adyen Demos</title>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
      <link rel="stylesheet" href="/css/main.css" />
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
                <a data-toggle="tooltip" data-placement="left" title="Choose a customer journey and configure your demo" href="#step1" class="mr-5 adyen-brand button circled scrolly">Journey</a>
                <a data-toggle="tooltip" data-placement="right" title="Manually configure your demo with features" href="/create-demo-manual" class="ml-5 adyen-brand button circled scrolly">Manual</a>
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
            @include('layouts.demo-inputs')
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
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
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