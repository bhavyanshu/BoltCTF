@extends('non-auth.baselayout')
@section('title') {!! config('appmeta.name') !!} - {{config('appmeta.summary')}} @stop

@section('content')
<div class="content-wrapper">
  <section class="content">
    <div class="row padding-left-right">
      <div class="col-md-8">
        <div class="jumbotron">
          <div class="row">
            <div class="col-md-12">
              <div class="col-md-7">
                <h1>{!! config('appmeta.htmltitle') !!}</h1>
                <p class="lead">
                  {{config('appmeta.description')}}
                </p>
                @php
                  $specials = config('appmeta.special_mentions');
                @endphp
                <p>Powered by: </p>
                @foreach($specials as $s)
                  {{ $loop->first ? '' : ', ' }}
                  {!! Html::link($s['link'], $s['name'], array('target' => '_blank')) !!}
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 register-box-body">
        <h2>Sign-Up</h2>
          @if(Session::has('message'))
          <br>
          <div class="alert alert-info errors">{{ Session::get('message') }}</div>
          @endif
          {!! Form::open(array('url' => '/register','class'=>'form register-form','data-parsley-validate')) !!}
          <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
          <br>{!! Form::label('username', 'Username') !!}
          {!! Form::text('username', null, array(
            'class' => 'form-control',
            'placeholder' => '','required',
            'data-parsley-required-message' => 'This is required',
            'data-parsley-minlength-message' => 'This cannot be less than 5 characters',
            'data-parsley-pattern-message' => 'This can only contain aphabets, numbers & (_) character',
            'data-parsley-trigger' => 'change focusout',
            'data-parsley-pattern' => '/^[a-zA-Z0-9_]*$/',
            'data-parsley-minlength' => '5')) !!}
          @if ($errors->has('username'))
              <span class="alert-danger">
                  <strong>{{ $errors->first('username') }}</strong>
              </span>
          @endif
          </div>

          <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <br>{!! Form::label('email', 'E-Mail Address') !!}
          {!! Form::text('email', null, array(
            'class' => 'form-control',
            'type' => 'email',
            'placeholder' => 'example@gmail.com','required',
            'data-parsley-required-message' => 'This is required',
            'data-parsley-type-message' => 'Please enter a valid email address',
            'data-parsley-trigger' => 'change focusout',
            'data-parsley-type' => 'email')) !!}
          @if ($errors->has('email'))
              <span class="alert-danger">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
          </div>

          <div class="row"><div class="form-group col-xs-6">
          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
          <br>{!! Form::label('password', 'Password') !!}
          {!! Form::password('password', array(
            'class' => 'form-control','required',
            'id'                            => 'inputPassword',
            'data-parsley-required-message' => 'Password is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-minlength'        => '6',
            'data-parsley-maxlength'        => '20')) !!}
          @if ($errors->has('password'))
              <span class="alert-danger">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
          @endif
          </div></div>

          <div class="form-group col-xs-6">
          <br>{!! Form::label('password_confirmation','Retype Password',['class'=>'control-label']) !!}
          {!! Form::password('password_confirmation',array(
            'class'=>'form-control','required',
            'id'                            => 'inputPasswordConfirm',
            'data-parsley-required-message' => 'Password confirmation is required',
            'data-parsley-trigger'          => 'change focusout',
            'data-parsley-equalto'          => '#inputPassword',
            'data-parsley-equalto-message'  => 'Not same as Password')) !!}
          </div></div>
          <br>
          {!! Form::submit('Sign Up' , array('class' => 'btn btn-primary')) !!}
          {!! Html::link('/login', 'Already a user? Sign In',array('class'=>'btn btn-link'))!!}
          {!! Form::close() !!}
          <br>
      </div>
    </div>
  </section>
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
