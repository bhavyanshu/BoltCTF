<?php

namespace BoltCTF\Providers;

use Illuminate\Support\ServiceProvider;
use Response;

class ResponseStatusProvider extends ServiceProvider
{
  /**
  * Bootstrap the application services.
  *
  * @return void
  */
  public function boot()
  {
    Response::macro('isEmpty', function($status = 204) {
      return Response::json([
        'success'  => true,
        'data' => 'No data available'
      ], $status);
    });

    Response::macro('success', function($data) {
      return Response::json([
        'success'  => true,
        'data' => $data
      ]);
    });

    Response::macro('error', function($message, $status = 400) {
      return Response::json([
        'success'  => false,
        'message' => $message
      ], $status);
    });
  }

  /**
  * Register the application services.
  *
  * @return void
  */
  public function register()
  {
    //
  }
}
