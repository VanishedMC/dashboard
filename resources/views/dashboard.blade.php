<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user" content="{{ json_encode($user) }}">
  <meta name="api_token" content="{{ auth()->user()->api_token }}">
  <meta name="icon-url" content="{{ url('/img/logo-bg.png') }}">

  <title>Dashboard</title>

  <script src="/js/app.js?v={{ microtime() }}" defer></script>

  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" rel="stylesheet">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
  <div id="app">

  </div>
</body>

</html>