<?php

namespace App\Http\Controllers\Api;

use App\Excels\Modules\Api\OrderExcel;
use App\Searchs\Modules\Api\Order\RepoSearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Modules\Order\Interfaces;

class OrderController extends Controller
{
    protected $repo;

    public function __construct(Interfaces $repo)
    {
        $this->repo = $repo;
    }

    /**
     * 测试repo:get用法
     * @method repo
     * @return \Illuminate\Http\JsonResponse
     * @author luffyzhao@vip.126.com
     */
    public function repo()
    {
        return $this->respondWithSuccess(
            $this->repo->with(['user'])->get(['*'])
        );
    }

    /**
     * 测试repo+search用法
     * @method repo
     * @param RepoSearch $search
     * @return \Illuminate\Http\JsonResponse
     * @author luffyzhao@vip.126.com
     */
    public function repoSearch(RepoSearch $search)
    {
        return $this->respondWithSuccess(
            $this->repo->with(['user'])->getWhere(
                $search,
                ['*']
            )
        );
    }

    /**
     * 测试repo+join
     * @method repoJoin
     * @return \Illuminate\Http\JsonResponse
     * @author luffyzhao@vip.126.com
     */
    public function repoJoin(){
        $join = [
            [
                'user' => 'left',
                'one' => 'inner'
            ]
        ];

//        $join = ['user.one'];
//
//        $join = [
//            [
//                'user', 'one'
//            ]
//        ];
        return $this->respondWithSuccess(
            $this->repo->with(['user'])->scope(['order'])->join($join)->get(['*'])
        );
    }

    /**
     * 测试repo+excel导出
     * @method repoExcel
     * @author luffyzhao@vip.126.com
     */
    public function repoExcel(){
        return (new OrderExcel($this->repo))->download('订单导出.xlsx');
    }
}
