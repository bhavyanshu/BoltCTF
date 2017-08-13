@extends('non-auth.baselayout')
@section('title') {{config('appmeta.name')}} - {{config('appmeta.summary')}} @stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {!! config('appmeta.htmltitle') !!}
      <small>{{config('appmeta.summary')}}</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Jumbotron -->
    <div class="jumbotron">
      <div class="row">
        <div class="col-md-12">
          <div class="col-md-6">
            <p class="lead">{{config('appmeta.description')}}</p>
            @php
              $specials = config('appmeta.special_mentions');
            @endphp
            <p>Powered by: </p>
            @foreach($specials as $s)
              {{ $loop->first ? '' : ', ' }}
              {!! Html::link($s['link'], $s['name'], array('target' => '_blank')) !!}
            @endforeach
						<div class="col-md-12">
							<h3>Contribute</h3>
							<p>This is an open source project developed by {{config('appmeta.author')}} - <a href="{{config('appmeta.author_link')}}">Website</a> | <a href="https://twitter.com/pytacular">Twitter</a>. <br>Would you like to contribute to this project?</p>
							<p><a class="btn btn-primary" href="#" role="button">View details &raquo;</a></p>
						</div>
					</div>
					</div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
@stop
