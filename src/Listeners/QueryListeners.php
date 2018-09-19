<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2018/8/4
 * Time: 22:30
 */

namespace luffyzhao\laravelTools\Listeners;


use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class QueryListeners
{

    public function handle(QueryExecuted $queryExecuted){
        Log::info($this->sqlBindings($queryExecuted));
    }

    /**
     * 解析sql
     * @method sqlBindings
     * @param  QueryExecuted $queryExecuted
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function sqlBindings(QueryExecuted $queryExecuted)
    {
        foreach ($queryExecuted->bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $queryExecuted->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else {
                if (is_string($binding)) {
                    $queryExecuted->bindings[$i] = "'$binding'";
                }
            }
        }
        $query = str_replace(array('%', '?'), array('%%', '%s'), $queryExecuted->sql);

        return vsprintf("[ SQL ] [ Driver: %s] %s [ RunTime: %u ms]", [
            $queryExecuted->connectionName,
            vsprintf($query, $queryExecuted->bindings),
            $queryExecuted->time]);
    }

}