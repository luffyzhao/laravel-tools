<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:33
 */

namespace LTools\Providers;


use Illuminate\Support\ServiceProvider;
use LTools\Auths\Cache\CacheGuard;
use LTools\Auths\Cache\TokenHandle;
use LTools\Console\Commands\Backup\Restore;
use LTools\Console\Commands\Backup\Run;
use LTools\Sign\SignManager;

class LaravelServiceProvider extends ServiceProvider
{

    /**
     * 依赖注入.
     *
     * @method boot
     *
     * @author luffyzhao@vip.126.com
     */
    public function boot()
    {
        $path = realpath(__DIR__.'/../../config/ltool.php');
        $this->publishes([$path => config_path('ltools.php')], 'config');
        $this->mergeConfigFrom($path, 'ltools');
    }

    /**
     * 服务注册.
     *
     * @method register
     *
     * @author luffyzhao@vip.126.com
     */
    public function register()
    {
        $this->registerSign();

        $this->extendAuthGuard();

        $this->registerCommand();
    }

    /**
     * 注册加签
     * @method registerSign
     *
     * @author luffyzhao@vip.126.com
     */
    protected function registerSign()
    {
        $this->app->singleton(
            'ltools.sign',
            function ($app) {
                return new SignManager($app['request']);
            }
        );
    }

    /**
     * Extend Laravel's Auth.
     *
     * @return void
     */
    protected function extendAuthGuard()
    {
        $this->app['auth']->extend('ltools.token', function ($app, $name, array $config) {

            $token = new TokenHandle($app['request'], $app['config']['ltools']['token-cache']);

            $guard = new CacheGuard(
                $app['auth']->createUserProvider($config['provider']),
                $token
            );
            $app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }

    /**  */
    protected function registerCommand(){
        $this->app->singleton('luffyzhao.tools.backup.run', function ($app) {
            return new Run();
        });
        $this->commands('luffyzhao.tools.backup.run');

        $this->app->singleton('luffyzhao.tools.backup.restore', function ($app) {
            return new Restore();
        });
        $this->commands('luffyzhao.tools.backup.restore');
    }
}