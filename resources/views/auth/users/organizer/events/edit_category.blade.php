@extends('auth.users.organizer.baselayout')

@section('title')
    Category Editor
@stop

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Category Editor
      <small>{!! config('appmeta.htmltitle') !!}</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      @if(Session::has('message'))
      <div id="dm" class="alert alert-success errors col-md-10 col-md-offset-1">{{ Session::get('message') }}</div>
      @endif
      <category-editor></category-editor>
    </div>
  </section>
</div>

@stop
