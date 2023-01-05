<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TQC4LW5HT9"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-TQC4LW5HT9');
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Adyen Demo Integration</title>
    <link rel="icon" type="image/x-icon" href="/img/adyen-favicon.ico">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Style Sheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.27.0/adyen.css">

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
      adyenConfig.paypalID = '{{isset($paypalID) ? $paypalID : ""}}';
      adyenConfig.pusherId = '{{isset($pusherId) ? $pusherId : ""}}';
      adyenConfig.pusherKey = '{{isset($pusherKey) ? $pusherKey : ""}}';
      adyenConfig.pusherCluster = '{{isset($pusherCluster) ? $pusherCluster : ""}}';

      for (const property in adyenConfig) {
        if (!adyenConfig[property]) {
          delete adyenConfig[property];
        }
      }
    </script>
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-light shadow-sm border-bottom">
      <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}">
            <img src="" alt="" class="merchant-logo">
          </a>
          <button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
              <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <!-- Left Side Of Navbar -->
              <ul class="navbar-nav mr-auto">

              </ul>

              <!-- Right Side Of Navbar -->
              <ul class="navbar-nav ml-auto">
                  <!-- Authentication Links -->
                  @if ($view_name != "create-demo")
                    @guest
                        <li class="nav-item">
                            <a class="nav-link txt-brand-color-one" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link txt-brand-color-one" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown bkg-brand-color-one">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                  document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                  @endif
              </ul>
          </div>
      </div>
    </nav>

    <div class="container-fluid p-0" id="main-container">
      <div class="row">
        <div class="col-12" id="content-container">
          @yield('content')
        </div>
        <div class="col-3 bg-white rounded p-3" id="technical-container" style="display: none;">
          <h4>Method / URL</h4>
          <div id="apiUrlOrMethod" class="col-md-12 p-0 bg-white"></div>
          <h4>Request</h4>
          <div class="col-md-12 bg-white">
            <pre id="apiRequest"></pre>
          </div>
          <h4>Response</h4>
          <div class="col-md-12 bg-white">
            <pre id="apiResponse"></pre>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid p-0" style="width: 800px; height: 1000px;">
      <iframe src="http://localhost:8001" height="1000" width="800" />
    </div>
    <script src="https://pay.google.com/gp/p/js/pay.js"></script>

    <script src="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.27.0/adyen.js"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="/js/main.js" type="text/javascript"></script>
    <script src="/js/ui-demo-utils.js" type="text/javascript"></script>
    <script src="/js/{{$view_name}}.js" type="module"></script>
    <script src="/js/app.js"></script>
    <!-- Start of Survicate (www.survicate.com) code -->
    <script type='text/javascript'>
      (function(w) {
        var s = document.createElement('script');
        s.src = 'https://survey.survicate.com/workspaces/9ab8bb359e7769f6b6442974e8782898/web_surveys.js';
        s.async = true;
        var e = document.getElementsByTagName('script')[0];
        e.parentNode.insertBefore(s, e);

        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();
      })(window);
    </script>
    <!-- End of Survicate code -->
    <script>
      // If this is a technical demonstration, let's show the box on the left
      if (demoSession.technicalDemo && demoSession.technicalDemo === "on") {
        $("#content-container").addClass("col-9");
        $("#content-container").removeClass("col-12");
        $('#technical-container').show();
      }
    </script>
  </body>
</html>
