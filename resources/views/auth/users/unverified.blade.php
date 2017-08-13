<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{!! config('appmeta.name') !!}</title>
	{!! Html::style('css/app.css') !!}
</head>
<body>
<div class="container spark-screen">
    <div class="row" style="padding:10px;">
			@if(Session::has('message'))
			<br>
			<div class="alert alert-info errors">{{ Session::get('message') }}</div>
			@endif
      <h1>{!! config('appmeta.htmltitle') !!}</h1>
			<h2>Hello, {{Auth::user()->username}}</h2>
			<p>Kindly verify your email address to use this service. An email has been sent to you with your confirmation link.</p>
		</div>
	</div>
</body>
</html>
