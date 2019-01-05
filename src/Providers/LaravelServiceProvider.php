<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/5
 * Time: 21:33
 */

namespace LTools\Providers;


use Illuminate\Support\ServiceProvider;
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
            'lTools.sign',
            function ($app) {
                return new SignManager($app['request']);
            }
        );
    }
}