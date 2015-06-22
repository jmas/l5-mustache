<?php namespace Mayconbordin\L5Mustache;

use Illuminate\Support\ServiceProvider;

class L5MustacheServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	/**
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('l5-mustache.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'l5-mustache'
        );
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app;

		$app->extend('view.engine.resolver', function($resolver, $app)
		{
			$resolver->register('mustache', function() use($app)
			{
				return $app->make('Mayconbordin\L5Mustache\MustacheEngine');
			});
			return $resolver;
		});

		$app->extend('view', function($env, $app)
		{
			$env->addExtension('mustache', 'mustache');
			return $env;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['l5-mustache'];
	}

}
