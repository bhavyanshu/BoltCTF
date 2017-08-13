<?php

namespace BoltCTF\Http\Controllers\Auth;

use BoltCTF\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;


class ForgotPasswordController extends Controller
{
  /**
   * Handles redirection after login
   *
   * @var $redirectTo
   */
  protected $redirectTo = '/dashboard';

  use ResetsPasswords;

  /**
   * Create a new password controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('guest');
  }

  /**
   * Display the form to request a password reset link.
   *
   * @return \Illuminate\Http\Response
   */
  public function showLinkRequestForm()
  {
      if (property_exists($this, 'linkRequestView')) {
          return view($this->linkRequestView);
      }

      if (view()->exists('non-auth.password_reset.askemailform')) {
          return view('non-auth.password_reset.askemailform');
      }

      return view('non-auth.password_reset.askemailform');
  }
}
