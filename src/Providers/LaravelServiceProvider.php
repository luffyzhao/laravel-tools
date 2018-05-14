<?php
namespace luffyzhao\laravelTools\Providers;

use Illuminate\Support\ServiceProvider;
use luffyzhao\laravelTools\Console\MakeSearchs;
use luffyzhao\laravelTools\Console\MakeExcels;
use luffyzhao\laravelTools\Console\MakeRepositories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\ExcelServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * 依赖注入
     * @method boot
     *
     * @author luffyzhao@vip.126.com
     */
    public function boot(){
        DB::listen(function ($sql) {
            Log::info($this->sqlBindings($sql));
        });
    }

    /**
     * 服务注册
     * @method register
     *
     * @author luffyzhao@vip.126.com
     */
    public function register(){
        // 注册excel类
        $this->app->register(ExcelServiceProvider::class);

        $this->registerLuffyCommand();
    }

    /**
     * 注册命令行命令
     * @method registerLuffyCommand
     *
     * @author luffyzhao@vip.126.com
     */
    protected function registerLuffyCommand(){
        $this->app->singleton('luffyzhao.make.excels', function () {
            return new MakeExcels;
        });

        $this->commands('luffyzhao.make.excels');

        $this->app->singleton('luffyzhao.make.searchs', function () {
            return new MakeSearchs;
        });

        $this->commands('luffyzhao.make.searchs');

        $this->app->singleton('luffyzhao.make.repositories', function () {
            return new MakeRepositories;
        });

        $this->commands('luffyzhao.make.repositories');
    }

    /**
     * 解析sql
     * @method sqlBindings
     * @param $sql
     *
     * @author luffyzhao@vip.126.com
     */
    protected function sqlBindings($sql){
        foreach ($sql->bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else {
                if (is_string($binding)) {
                    $sql->bindings[$i] = "'$binding'";
                }
            }
        }
        $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql);
        return vsprintf($query, $sql->bindings);
    }
}