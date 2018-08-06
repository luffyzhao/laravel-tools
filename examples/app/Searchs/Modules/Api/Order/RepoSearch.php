<?php

namespace App\Searchs\Modules\Api\Order;

use luffyzhao\laravelTools\Searchs\Facades\SearchAbstract;

class RepoSearch extends SearchAbstract
{
    protected $relationship = [
        'order_no' => 'like'
    ];

    public function getOrderNoAttribute($value){
        return $value . "%";
    }
}
