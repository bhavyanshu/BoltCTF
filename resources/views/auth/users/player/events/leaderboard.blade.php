@extends('auth.users.player.baselayout')

@section('title')
    {{ $event->name }} - Leaderboard
@stop

@section('event_menu')
  @include('auth.users.player.events.partials.event_menu')
@stop

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      {{ $event->name }}
      <small>Leaderboard</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if(Session::has('message'))
      <div id="dm" class="alert alert-success errors col-md-10 col-md-offset-1">{{ Session::get('message') }}</div>
      @endif
      <div class="col-md-12">
        <div class="box box-info">
          <leader-board limit="100">
          </leader-board>
        </div>
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
