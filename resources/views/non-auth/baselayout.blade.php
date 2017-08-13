<!DOCTYPE html>
<html>
    <head>
      <meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
      <title>@yield('title')</title>

      {!! Html::style('css/bootstrap.min.css') !!}
      {!! Html::style('css/plugins.min.css') !!}
      {!! Html::style('adminlte/dist/css/AdminLTE.min.css') !!}
      {!! Html::style('adminlte/dist/css/skins/skin-black.min.css') !!}
      {!! Html::style('css/app.min.css') !!}

      {!! Html::script('js/plugins.min.js') !!}
      {!! Html::script('adminlte/dist/js/app.min.js') !!}
      {!! Html::script('js/utils.min.js') !!}
    </head>
    <body class="layout-top-nav skin-black-light">
    <div class="wrapper">
      <header class="main-header">
        <nav class="navbar-inverse navbar-static-top">
          <div class="container">
            <div class="navbar-header">
              <a href="/" class="navbar-brand"><b>{!! config('appmeta.htmltitle') !!}</b></a>
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
              </button>
            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">
              <ul class="nav navbar-nav navbar-left">
                <li><a href="/about">About</a></li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li><a href="/login">Login</a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>

      @yield('content')

      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <a href="/about">About {!! config('appmeta.htmltitle') !!}</a>
        </div>
        <strong>{!! config('appmeta.copyright_year') !!} <a href="https://bhavyanshu.me">Bhavyanshu Parasher</a></strong>
      </footer>
  </div>
  </body>
</html>
