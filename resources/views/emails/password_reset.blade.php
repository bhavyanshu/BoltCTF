@extends('emails.baselayout')
@section('content')
  <h2>Reset your password</h2>
  <div>
      Please follow the link below to reset your password.<br>
      <a href="{{ URL::to('user/password/reset/'.$token) }}">{{ URL::to('user/password/reset/'.$token) }}</a>.<br/>
  </div>
@endsection
