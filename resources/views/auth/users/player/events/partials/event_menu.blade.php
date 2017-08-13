<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ev-navbar-collapse" aria-expanded="true">
  <i class="fa fa-trophy"></i>
</button>
<div class="navbar-collapse pull-left collapse" id="ev-navbar-collapse" aria-expanded="false" style="height: 1px;">
  <ul class="nav navbar-nav">
    @php ($ev_ref = $event->ref_guid)
    <li>
      <a href="{{ URL::to('/stadium/event/'. $ev_ref) }}" >
        <span class="fa fa-trophy"></span> {{$event->name}}
      </a>
    </li>
    @if (Utility::hasEventExpired($event) == false)
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Challenges <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          @foreach ($event->category as $cat)
            <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $cat->category_name }} </a>
              <ul class="dropdown-menu">
                @foreach ($cat->challenge as $cha)
                  <li><a href="{{ URL::to('/stadium/event/' . $ev_ref . '/challenge/' . $cha->ref_guid) }}">{{ $cha->challenge_name }}</a></li>
                @endforeach
              </ul>
            </li>
          @endforeach
        </ul>
      </li>
    @endif
  </ul>
</div>
