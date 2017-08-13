@extends('emails.baselayout')
@section('content')
  <h2>Reset your password</h2>
  <div>
      Please click on the link below to reset your password.<br>
      {{ URL::to('user/password/reset/'.$token) }}.<br/>
  </div>
@endsection
