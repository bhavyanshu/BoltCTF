@extends('emails.baselayout')
@section('content')
  <h2>Verify Your Email Address</h2>
  <div>
      <h3> {!! config('appmeta.name') !!} </h3>
      Please follow the link below to verify your email address
      <a href="{{ URL::to('user/verify/' . $confirmcode) }}">{{ URL::to('user/verify/' . $confirmcode) }}</a>.<br/>
  </div>
@endsection
