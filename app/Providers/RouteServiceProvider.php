<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
	    $request = Request::instance();
	    $host = $request->headers->get('host');
	    switch ($host) {
		    case env('DOMAIN_ADMIN_API'):
			    $this->mapAdminApiRoutes();
			    break;
		    case env('DOMAIN_API'):
			    $this->mapApiRoutes();
			    break;
		    default:
			    break;
	    }
    }

	/**
	 * Define the "web" routes for the application.
	 *
	 * These routes all receive session state, CSRF protection, etc.
	 *
	 * @return void
	 */
	protected function mapAdminApiRoutes()
	{
		//todo jwt时效（分钟）
		config(['jwt.ttl'=>720]);

		config(['auth.defaults'=>[
			'guard' => 'admin',
			'passwords' => 'users',
		]]);

		config(['l5-swagger.paths.annotations'=>[
			base_path('routes/AdminApi'),
			base_path('app/Http/Controllers/AdminApi')
		],
			'l5-swagger.paths.docs_json'=>'admin.json'
		]);

		Route::group([
			'namespace' => $this->namespace,
			'middleware' => 'admin',
		], function ()
		{
			load_routes(base_path('routes/AdminApi'));
		});
	}


	/**
	 * Define the "api" routes for the application.
	 *
	 * These routes are typically stateless.
	 *
	 * @return void
	 */
	protected function mapApiRoutes()
	{
		//todo jwt时效（分钟）
		config(['jwt.ttl'=>720]);

		config(['auth.defaults'=>[
			'guard' => 'user',
			'passwords' => 'users',
		]]);

		config(['l5-swagger.paths.annotations'=>[
			base_path('routes/Api'),
			base_path('app/Http/Controllers/Api')
		],'l5-swagger.paths.docs_json'=>'api.json']);

		Route::group([
			'namespace' => $this->namespace,
			'middleware' => 'user',
		], function ()
		{
			load_routes(base_path('routes/Api'));
		});
	}

	protected function mapDriverApiRoutes()
	{
		//todo jwt时效（分钟）
		config(['jwt.ttl'=>720]);

		config(['auth.defaults'=>[
			'guard' => 'driver',
			'passwords' => 'users',
		]]);

		config(['l5-swagger.paths.annotations'=>[
			base_path('routes/DriverApi'),
			base_path('app/Http/Controllers/DriverApi')
		],'l5-swagger.paths.docs_json'=>'driver.json']);

		Route::group([
			'namespace' => $this->namespace,
			'middleware' => 'driver',
		], function ()
		{
			load_routes(base_path('routes/DriverApi'));
		});
	}

	protected function mapBossApiRoutes()
	{
		//todo jwt时效（分钟）
		config(['jwt.ttl'=>720]);

		config(['auth.defaults'=>[
			'guard' => 'boss',
			'passwords' => 'users',
		]]);

		config(['l5-swagger.paths.annotations'=>[
			base_path('routes/BossApi'),
			base_path('app/Http/Controllers/BossApi')
		],'l5-swagger.paths.docs_json'=>'boss.json']);

		Route::group([
			'namespace' => $this->namespace,
			'middleware' => 'boss',
		], function ()
		{
			load_routes(base_path('routes/BossApi'));
		});
	}
}
