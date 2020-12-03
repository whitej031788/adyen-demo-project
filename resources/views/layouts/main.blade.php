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

    <link rel="stylesheet"
        type="text/css"
        href="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/3.18.2/adyen.css"
        crossorigin="anonymous">

      <link href="/css/adyen-demo.css" rel="stylesheet">

    <script type="text/javascript">
      // The demo_session is a JSON respresentation of what the demo is meant to show
      // If it is defined, it should be available in all views, and thus JS files

      var demoSession = JSON.parse('{!! json_encode(session('demo_session')) !!}');
    </script>
  </head>
  <body>

    <div class="container">
      @yield('content')
    </div>
    <script src="https://pay.google.com/gp/p/js/pay.js"></script>

    <script src="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/3.18.2/adyen.js"
         crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="/js/{{$view_name}}.js" type="module"></script>
  </body>
</html>
