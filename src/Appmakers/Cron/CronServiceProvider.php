<?php namespace Appmakers\Cron;

use Config, Route;
use Illuminate\Support\ServiceProvider;

class CronServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('appmakers/cron');

		// Register a route for the ticker
		$url = Config::get('cron::cron.route');
		if ($url !== false && is_string($url)) {
			Route::get($url, function() {
				Exec::tick();
			});
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}