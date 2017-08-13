@extends('auth.users.player.baselayout')

@section('title')
    {{ $challenge->challenge_name }} - Stadium
@stop

@section('event_menu')
  @include('auth.users.player.events.partials.event_menu')
@stop

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Stadium
      <small>{{ $challenge->challenge_name }}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if(Session::has('message'))
      <div id="dm" class="alert alert-success errors col-md-10 col-md-offset-1">{{ Session::get('message') }}</div>
      @endif
      <div class="col-md-12">
        <questions-list>
        </questions-list>
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
