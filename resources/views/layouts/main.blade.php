<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Jamie Adyen Integration</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Style Sheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/3.20.0/adyen.css">

    <link href="/css/fontawesome.min.css" rel="stylesheet">
    <link href="/css/adyen-demo.css" rel="stylesheet">

    <script type="text/javascript">
      // The demo_session is a JSON respresentation of what the demo is meant to show
      // If it is defined, it should be available in all views, and thus JS files
      var demoSession = {!! json_encode(session('demo_session')) !!};
      demoSession = JSON.parse(demoSession);

      // Adyen config mostly comes from the .env file and then is rendered in the blade views
      // Not all properties are needed for different demo settings
      var adyenConfig = {};
      adyenConfig.merchantAccount = '{{isset($merchantAccount) ? $merchantAccount : ""}}';
      adyenConfig.clientKey = '{{isset($clientKey) ? $clientKey : ""}}';
      adyenConfig.terminalPooid = '{{isset($terminalPooid) ? $terminalPooid : ""}}';
      adyenConfig.terminalPooidTwo = '{{isset($terminalPooidTwo) ? $terminalPooidTwo : ""}}';

      for (const property in adyenConfig) {
        if (!adyenConfig[property]) {
          delete adyenConfig[property];
        }
      }
    </script>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg">
      <a href="#" class="navbar-brand">
        <img src="" alt="" class="merchant-logo">
      </a>
      @if ($view_name == 'standard-ecom')
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="nav navbar-nav ml-auto">
          <li class="nav-item">
             <a class="nav-link txt-brand-color-one" href="#">
               <i class="fas fa-phone pr-1"></i> Call us free on 0800 123 4567
             </a>
          </li>
          <li class="nav-item pl-3">
             <a class="nav-link txt-brand-color-one" href="#">
               <i class="fas fa-cart-plus"></i>
             </a>
          </li>
        </ul>
      </div>
      @endif
    </nav>

    <div class="container pb-3 pt-3" id="main-container">
      @yield('content')
    </div>
    <script src="https://pay.google.com/gp/p/js/pay.js"></script>

    <script src="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/3.20.0/adyen.js"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="/js/{{$view_name}}.js" type="module"></script>
    <script src="/js/app.js"></script>
  </body>
</html>
