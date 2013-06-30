<?php
namespace Flatten;

use Illuminate\Support\ServiceProvider;

/**
 * Register the Flatten package with the Laravel framework
 */
class FlattenServiceProvider extends ServiceProvider
{

  /**
   * Register Flatten's classes with Laravel
   */
  public function register()
  {
    $this->app['config']->package('anahkiasen/flatten', __DIR__.'/../config');

    $this->app = Flatten::bind($this->app);

    $this->commands('flatten.commands.build');
  }

  /**
   * Boot Flatten
   */
  public function boot()
  {
    // Cancel if Flatten shouldn't run here
    if (!$this->app['flatten']->shouldRun()) {
      return false;
    }

    // Launch startup event
    $this->app['flatten.events']->onApplicationBoot();

    // Bind closing event
    $app = $this->app;
    $this->app->finish(function() use ($app) {
      return $app['flatten.events']->onApplicationDone();
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides()
  {
    return array('flatten');
  }

}