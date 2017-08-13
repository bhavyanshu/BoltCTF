@extends('auth.users.player.baselayout')

@section('title')
    {{ $event->name }} - Event has ended!
@stop

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      {{ $event->name }}
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <h2 class="text-center">The event has ended! <a href="{{ URL::to('/stadium/event/' . $event->ref_guid . '/leaderboard') }}">Check out the leaderboard</a></h2>
      </div>
    </div>
  </section>
</div>

@stop
