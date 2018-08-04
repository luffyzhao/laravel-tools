<?php

namespace luffyzhao\laravelTools\Providers;

use Illuminate\Support\ServiceProvider;
use luffyzhao\laravelTools\Console\MakeSearchs;
use luffyzhao\laravelTools\Console\MakeExcels;
use luffyzhao\laravelTools\Console\MakeRepositories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use luffyzhao\laravelTools\Sign\SignManager;
use Maatwebsite\Excel\ExcelServiceProvider;

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
        // 注册excel类
        $this->app->register(ExcelServiceProvider::class);

        $this->registerLuffyCommand();

        $this->registerSign();
    }

    /**
     * 注册命令行命令.
     *
     * @method registerLuffyCommand
     *
     * @author luffyzhao@vip.126.com
     */
    protected function registerLuffyCommand()
    {
        $this->app->singleton('luffyzhao.make.excels', function ($app) {
            return new MakeExcels($app['files']);
        });

        $this->commands('luffyzhao.make.excels');

        $this->app->singleton('luffyzhao.make.searchs', function ($app) {
            return new MakeSearchs($app['files']);
        });

        $this->commands('luffyzhao.make.searchs');

        $this->app->singleton('luffyzhao.make.repositories', function ($app) {
            return new MakeRepositories($app['files']);
        });

        $this->commands('luffyzhao.make.repositories');
    }

    /**
     * 注册加签
     * @method registerSign
     *
     * @author luffyzhao@vip.126.com
     */
    protected function registerSign(){
        $this->app->singleton('luffyzhao.sign', function ($app) {
            return new SignManager;
        });
    }
    
}
