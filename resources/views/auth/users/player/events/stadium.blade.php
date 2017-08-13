@extends('auth.users.player.baselayout')

@section('title')
    Stadium
@stop

@section('event_menu')
  @include('auth.users.player.events.partials.event_menu')
@stop

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      {{ $event->name }}
      <small>Stadium</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if(Session::has('message'))
      <div id="dm" class="alert alert-success errors col-md-10 col-md-offset-1">{{ Session::get('message') }}</div>
      @endif
      <div class="col-md-6">
        <div class="box box-primary">
          <category-list>
          </category-list>
        </div>
      </div>
      <div class="col-md-6">
        
        <countdown-timer dt="{{ $event->end_time }}">
        </countdown-timer>

        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Your Stats</h3>
          </div>
          <div class="box-body">
            <div class="col-sm-4 col-xs-6">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $stats->points or '0' }}</h5>
                <span class="description-text">Points</span>
              </div>
            </div>
            <div class="col-sm-4 col-xs-6">
              <div class="description-block border-right">
                <h5 class="description-header">
                  @if($stats->total_ques !=0)
                    {{ number_format(($stats->answered_ques/$stats->total_ques)*100, 2) }} %
                  @else
                    {{ 0 }} %
                  @endif
                  </h5>
                <span class="description-text">Completion</span>
              </div>
            </div>
            <div class="col-sm-4 col-xs-12">
              <div class="description-block border-right">
                <h5 class="description-header">{{ $stats->incorrect_attempts }} </h5>
                <span class="description-text">Incorrect Attempts</span>
              </div>
            </div>
          </div>
        </div>
        <div class="box box-info">
          <leader-board>
          </leader-board>
          <div class="box-footer text-center">
            <a href="{{ URL::to('/stadium/event/' . $event->ref_guid . '/leaderboard') }}" class="btn btn-sm btn-primary btn-flat">View Complete Board</a>
          </div>
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
