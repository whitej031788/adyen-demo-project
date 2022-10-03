<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Adyen Demo Integration</title>
    <link rel="icon" type="image/x-icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOIAAADfCAMAAADcKv+WAAAAbFBMVEU1tFT///8esEbV7NlJumQ8tlousk9yxoQnsUuj2K2S0Z/o9usisEgssk4UrkHY7tz5/fre8eJWvm7y+vSLz5m+5MbI6M5jwnhZv3Dp9uyn2rF8yowLrT20371qxH7O6tSu3biFzZOa1aaHzpa6uIxuAAAEWElEQVR4nO3d23qqMBAF4CEKokAE8Uw9te//jlu7W6stHsnAsJx10Vv8v0BSZZIhDz70+TfqtCfDLMqfII7fTJtibTAbTLKHiIOQWpYgiQ31VveMZ1uJnwliO1+l0EQ6KJPxApu4T2iLCJxIlJjxldsVgrgfyWSCTiQy60uzKwyRkmSDTiSy7/BEMgU8kcJ5ycyKRdwb4YllRjQihT14IsVjeCLZFTyRTAZPDHx4IsUDeOL5rYpJDEbwRDITeCL5+ESzgieeDCMs0UzhicEankg2hyeGW3jicfnHJZLJ4YnxBJ6YjOGJ3w8jMJFsCk/8+taITPyab5CJYR+fOIAnBgU+cYZPnOMTu/BEUiJClIgQJSJEiQh5NSL59YYSEwa1EutPGm1HlhvZLPGQbB6jEz3v3cITvZWBJ3p9TqMMojdjnHOEEDPGYRRC9NZ8wyiFOOVbOaQQU76FQwrR8/GJfA+jGGJPiUpUohLriRKVqERPifVEiUpUovcYMc0X0UNZ3HW8kAhiPl3uRkkYxg+eLvRWfpaANGI++ejaOEye+RxfdXaiielkbePk6UvIJy52YVzp8tKJWc8+P37/I5uYrx28/RNNXJqqI3iIYGLUdfMLtVziytUbarHEwtm7IqHEtOuuQEUmMfcdXlAkMQpcXk8iceFUKJGYurxLSSRx5GK9P4k8YuG62E8cceX8jbQ0YuS+OkQasev+SsKIS4baEFnEnKP6RRax53i9+Iwo4pClvkcUkaf2RRIx4inSkkQsOJ5EUcScqSZUEHHLVC8piDhnuogc4oKrIlQOccu1Y0oOka0gVA6R6wpyiHzbCMQQ2R5FOcQxz782JIjIV34uhsh1AUFEvq0gUog5PpHhx8XvSCEy7q6TQhziEztKrBAlKrF6lOgiSlRi9SjRRZSoxOpRoosoUYnVo0QXUaISq0eJLqJEJVaPEl3kFYhCdoQzEpPS5vJQxLOuq5jEnwaBuMRjg0BcIvk3eADE+I45teVEsh14IiURPJHiDTyRzMf1pQOASIktposUmrhHxia43JWBLXUSG4oSEaJEhCgRIUpEyAsRGauKm04NteFN54WITBveJaSG3TZN50jEbEx4yJE44u8S2FCOxB3b/sWmcyQuYe/UI3EDu2ociTn+KOLONz/Ed9Rh/CFmqIv/yUkpTX8UrpwQQTsvnxIZtxM3mtOzp7jO9Gk4p8Qp5jCenQPHcOakgJwRJ5DDeH4mI+QwnhM3iMv/r/NRWY7WbDi/iCng8v/7IN8J3q3658TpHdw4/j03HG5W/UvM0WackgPu0b44lnVimGIZS/tpYBnLu6JAfee40Nsme6qBncxc6lCUO+zf03Au95kqUB7IK93CViHGCnmt55uTpnbN53rnvs7ItB95q//itNt65C2i521mpt2T622i50V934btHct7iPtk/VFonmto23juJO6Tb5a7eWDtg12JG4/17yZ+S6Os064MHyW2Nf8AZstZ4yQhYC4AAAAASUVORK5CYII=">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <!-- Style Sheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.11.0/adyen.css">

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

    <script src="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.11.0/adyen.js"></script>

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
      })(window);
    </script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TQC4LW5HT9"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-TQC4LW5HT9');
    </script>
    <!-- End of Survicate code -->
  </body>
</html>
