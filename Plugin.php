<?php namespace Jcc\Jwt;

use Backend;
use Illuminate\Foundation\AliasLoader;
use System\Classes\PluginBase;
use App;
use Tymon\JWTAuth\JWTGuard;
use Illuminate\Contracts\Debug\ExceptionHandler;
/**
 * jwt Plugin Information File
 */
class Plugin extends PluginBase
{
    public $middlewareAliases = [
        'api.jwt.refresh' => \Jcc\Jwt\Middleware\RefreshTokenMiddleware::class
    ];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'jwt',
            'description' => 'No description provided yet...',
            'author'      => 'jcc',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        //用auth()函数 可以用auth('api')
        $this->app->singleton(\Illuminate\Contracts\Auth\Factory::class, function($app){
            return app('auth');//返回单例避免没有 extend('jwt')
        });
//
//        App::register('\Jcc\Jwt\Classes\JWTAuthServiceProvider');
//
//        $facade = AliasLoader::getInstance();
//        $facade->alias('JWTAuth', '\Tymon\JWTAuth\Facades\JWTAuth');
//        $facade->alias('JWTFactory', '\Tymon\JWTAuth\Facades\JWTFactory');
//
        App::singleton('auth', function ($app) {
            return new \Illuminate\Auth\AuthManager($app);
        });


        $this->aliasMiddleware();


    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        app('auth')->extend('jwt', function ($app, $name, array $config) {
            $guard = new JWTGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
        if(request()->header('NEED_LARAVEL_HANDLER')){
            $this->app->bind(
                ExceptionHandler::class,
                \Jcc\Jwt\Exceptions\LaravelHandler::class
            );
        }

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Jcc\Jwt\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'jcc.jwt.some_permission' => [
                'tab' => 'jwt',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'jwt' => [
                'label'       => 'jwt',
                'url'         => Backend::url('jcc/jwt/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['jcc.jwt.*'],
                'order'       => 500,
            ],
        ];
    }

    protected function aliasMiddleware()
    {
        $router = $this->app['router'];

        $method = method_exists($router, 'aliasMiddleware') ? 'aliasMiddleware' : 'middleware';

        foreach ($this->middlewareAliases as $alias => $middleware) {
            $router->$method($alias, $middleware);
        }
    }
}
