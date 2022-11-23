<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 22:18
 */

namespace LTools\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class RepositoryAbstract
{
    /**
     * @var Model
     */
    protected $model;


    /**
     * 通过主键查找一个模型.
     *
     * @method find
     *
     * @param int|string $id 主键ID
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @author luffyzhao@vip.126.com
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * 通过主键查找一个模型.
     *
     * @method find
     *
     * @param int|string $id 主键ID
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @author luffyzhao@vip.126.com
     */
    public function lock($id, array $columns = ['*'])
    {
        return $this->model->lockForUpdate()->findOrFail($id, $columns);
    }

    /**
     * 获取全部模型.
     *
     * @method get
     *
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @author luffyzhao@vip.126.com
     */
    public function get(array $columns)
    {
        return $this->model->get($columns);
    }


    /**
     * 查找与属性匹配的记录并分页.
     *
     * @method paginate
     *
     * @param array $attributes Where条件
     * @param int $perPage 每页多少条
     * @param array $columns 获取字段
     * @param string $pageName 分页input字段
     * @param int $page
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function paginate(
        array $attributes,
        int   $perPage = null,
        array $columns = ['*'],
              $pageName = 'page',
        int   $page = null
    )
    {
        $perPage = request()->has('per_page') ? request()->input('per_page') : $perPage;
        return $this->model->where(
            $attributes
        )->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * 查找与属性匹配的记录并分页.（简单版）.
     *
     * @method simplePaginate
     *
     * @param array $attributes Where条件
     * @param int $perPage
     * @param array $columns 获取字段
     *
     * @param string $pageName
     * @param null $page
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function simplePaginate(
        array $attributes,
        int   $perPage = null,
              $columns = ['*'],
              $pageName = 'page',
              $page = null
    )
    {
        return $this->model
            ->where($attributes)->simplePaginate($perPage, $columns, $pageName, $page);
    }

    /**
     * 创建模型.
     *
     * @method create
     *
     * @param array $attributes 属性
     *
     * @return \Illuminate\Database\Eloquent\Model|bool
     *
     * @author luffyzhao@vip.126.com
     */
    public function create(array $attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * 更新
     * @method update
     *
     * @param       $id
     * @param array $values
     *
     * @return Model | bool
     *
     * @throws \Throwable
     * @author luffyzhao@vip.126.com
     */
    public function update($id, array $values)
    {
        return $this->find($id)->fill($values)->saveOrFail();

    }

    /**
     * 删除数据模型
     * @method delete
     *
     * @param $id
     *
     * @return bool|mixed
     * @throws \Exception
     *
     * @author luffyzhao@vip.126.com
     */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * 获取第一条
     * @param array $attributes
     * @param array $columns
     *
     * @return Model
     */
    public function first(array $attributes, array $columns = ['*'])
    {
        return $this->model->where($attributes)->firstOrFail($columns);
    }

    /**
     * @param $ids
     * @param callable $callback
     * @return array
     */
    public function batches($ids, callable $callback)
    {
        $idArr = explode(',', $ids);
        $response = [];
        if (!empty($idArr)) {
            foreach ($idArr as $id) {
                $response[] = DB::transaction(function ()use ($id, $callback){
                    return $callback($id);
                });
            }
        }
        return $response;
    }
}