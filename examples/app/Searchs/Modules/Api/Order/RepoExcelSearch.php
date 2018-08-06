<?php

namespace App\Searchs\Modules\Api\Order;

use luffyzhao\laravelTools\Searchs\Facades\SearchAbstract;

class RepoExcelSearch extends SearchAbstract
{
    protected $relationship = [
        'orders.order_no' => '='
    ];
}
