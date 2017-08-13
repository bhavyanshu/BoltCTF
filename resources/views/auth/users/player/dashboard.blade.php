@extends('auth.users.player.baselayout')

@section('title')
    Dashboard
@stop

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Player Dashboard
      <small>{!! config('appmeta.htmltitle') !!}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if(Session::has('message'))
      <div id="dm" class="alert alert-success errors col-md-10 col-md-offset-1">{{ Session::get('message') }}</div>
      @endif
      <div class="col-md-6">
        <player-events>
        </player-events>
      </div>
    </div>
  </section>
</div>

@section('scripts')
  <script type="text/javascript">
    setTimeout(function() { $("#dm").slideUp('slow') }, 5000);
  </script>
@stop
@stop
