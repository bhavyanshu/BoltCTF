@extends('non-auth.baselayout')
@section('title') {{config('appmeta.name')}} - {{config('appmeta.summary')}} @stop

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Main content -->
  <section class="content">
    <!-- Jumbotron -->
    <div class="row">
      <div class="col-md-8 col-md-offset-2 login-box-body">
        <div class="col-md-6 col-md-offset-3">
          <h2>Sign In</h2>
          @if(Session::has('message'))
          <br>
          <div class="alert alert-info errors">{{ Session::get('message') }}</div>
          @endif
          {!! Form::open(array('url' => '/login','class'=>'form')) !!}

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <br>{!! Form::label('email', 'E-Mail Address') !!}
          {!! Form::text('email', old('email'), array('class' => 'form-control','placeholder' => 'example@gmail.com')) !!}
          @if ($errors->has('email'))
              <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
          </div>

          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          {!! Form::label('password', 'Password') !!}
          {!! Form::password('password', array('class' => 'form-control')) !!}
          @if ($errors->has('password'))
              <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
          @endif
          </div>
          <div class="checkbox" style="padding-left:20px;">
          {!! Form::checkbox('remember', null, null) !!} Remember Me
          </div>
          <button type="submit" class="btn btn-primary">
            Login  <i class="glyphicon glyphicon-log-in"></i>
          </button>
          {!! Html::link('user/password/reset', 'Forgot password?',array('class'=>'btn btn-link'))!!}
          <br>
          {!! Form::close() !!}
          <br>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<script>
var parsleyOptions = {
    successClass: 'has-success',
    errorClass: 'has-error',
    classHandler : function( _el ){
        return _el.$element.closest('.form-group');
    }
};

$('.register-form').parsley(parsleyOptions);
</script>
@stop
