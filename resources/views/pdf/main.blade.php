<!DOCTYPE >
<html lang="{{ config('app.locale') }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
  </head>
  <body>
    <div class="wrapper">
      <header class="main-header">
        <div class="logo">
          <img class="logo-image" src="{{ asset('images/headerlogo60yrs.png') }}" alt="">
        </div>
        @section('header')
          <h1>@yield('title')</h1>
        @show
        <div class="contact">
          @section('contact')
            @include('pdf.address')
          @show
        </div>
      </header>
      <div class="main">
        @yield('content')
      </div>
      <footer>
      </footer>
    </div>
  </body>
</html>
