<?php

namespace luffyzhao\laravelTools\Repositories\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

abstract class RepositoriesAbstract implements RepositoryInterface
{
    protected $model;

    /**
     * 获取Model.
     *
     * @method getModel
     *
     * @return Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * 获取数据表名.
     *
     * @method getTable
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * 通过主键查找一个模型.
     *
     * @method find
     *
     * @param int $id 主键ID
     * @param array $columns 获取字段
     *
     * @return Collection
     *
     * @author luffyzhao@vip.126.com
     */
    public function find($id, array $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * 通过主键查找多个模型。
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array $ids 主键IDs
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @author luffyzhao@vip.126.com
     */
    public function findMany($ids, $columns = ['*'])
    {
        return $this->model->findMany($ids, $columns);
    }

    /**
     * 通过where条件查找一个模型.
     *
     * @method findWhere
     *
     * @param array $attributes Where条件
     * @param array $columns 获取字段
     *
     * @return Illuminate\Support\Collection|static|null
     *
     * @author luffyzhao@vip.126.com
     */
    public function findWhere(array $attributes, array $columns = ['*'])
    {
        return $this->model->where($attributes)->firstOrFail($columns);
    }

    /**
     * 从查询的第一个结果获取单个列的值。
     *
     * @method findValue
     *
     * @param array $attributes Where条件
     * @param string $columns 获取字段
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function findValue(array $attributes, string $columns)
    {
        return $this->model->where($attributes)->value($columns);
    }

    /**
     * 获取全部模型.
     *
     * @method get
     *
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * @author luffyzhao@vip.126.com
     */
    public function get(array $columns)
    {
        return $this->model->get($columns);
    }

    /**
     * 通过where条件查找多个模型.
     *
     * @method getWhere
     *
     * @param array $attributes Where条件
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * @author luffyzhao@vip.126.com
     */
    public function getWhere(array $attributes, array $columns = ['*'])
    {
        return $this->model->where($attributes)->get($columns);
    }

    /**
     * 分块处理
     * @method chunkById
     * @param array $attributes Where条件
     * @param $count 每次获取$count条数据
     * @param callable $callback 回调
     * @param null $column 字段
     * @param null $alias 表别名
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function chunkById(array $attributes, $count, callable $callback, $column = null, $alias = null)
    {
        return $this->model->where($attributes)->chunkById($count, $callback, $column, $alias);
    }

    /**
     * 获取与属性匹配的第一个记录不存在就创建。
     *
     * @method firstOrCreate
     *
     * @param array $attributes Where条件
     * @param array $values 附加填充值
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    /**
     * 修改与属性匹配的记录不存在就创建。
     *
     * @method updateOrCreate
     *
     * @param array $attributes Where条件
     * @param array $values 附加填充值
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->model->updateOrCreate($attributes, $values);
    }

    /**
     * 查找与属性匹配的记录并分页.
     *
     * @method paginate
     *
     * @param array $attributes Where条件
     * @param [type] $perPage    每页多少条
     * @param array $columns 获取字段
     * @param string $pageName 分页input字段
     * @param [type] $page       当前页码
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function paginate(array $attributes, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->model->where($attributes)->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * 查找与属性匹配的记录并分页.（简单版）.
     *
     * @method simplePaginate
     *
     * @param array $attributes Where条件
     * @param null $perPage
     * @param array $columns 获取字段
     *
     * @param string $pageName
     * @param null $page
     * @return \Illuminate\Contracts\Pagination\Paginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function simplePaginate(array $attributes, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        return $this->model->where($attributes)->select($columns)->simplePaginate($perPage);
    }

    /**
     * 查找与属性匹配的记录
     * @method limit
     * @param array $attributes
     * @param null $perPage
     * @param array $columns
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function limit(array $attributes, $perPage = null, $columns = ['*'])
    {
        return $this->model->where($attributes)->select($columns)->limit($perPage)->get();
    }

    /**
     * 创建模型.
     *
     * @method create
     *
     * @param array $attributes 属性
     *
     * @return \Illuminate\Database\Eloquent\Model
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
     * @param Model $model
     * @param array $values
     *
     * @param array $attributes
     * @return Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function update(Model $model, array $values, array $attributes = [])
    {
        if (!empty($attributes)) {
            $model = $model->where($attributes);
        }

        $model->fill($values)->save(['touch' => false]);

        return $model;
    }

    /**
     * 根据条件更新
     * @method updateWhere
     * @param array $values
     * @param array $attributes
     *
     * @return bool|void
     *
     * @author luffyzhao@vip.126.com
     */
    public function updateWhere(array $values, array $attributes)
    {
        $model = $this->model->newInstance();
        $data = array_intersect_key($values, array_flip($this->model->getFillable()));
        return $model->where($attributes)->update($data);
    }

    /**
     * 删除数据模型
     * @method delete
     *
     * @param Model $model
     *
     * @return bool|mixed
     * @throws \Exception
     *
     * @author luffyzhao@vip.126.com
     */
    public function delete(Model $model)
    {
        if ($model->delete()) {
            return true;
        }

        return false;
    }

    /**
     * 使实体的一个新实例查询
     * @method make
     *
     * @param array $with
     *
     * @return $this
     *
     * @author luffyzhao@vip.126.com
     */
    public function make(array $with = array())
    {
        $this->model = $this->model->with($with);

        return $this;
    }

    /**
     * 添加一个获取多个作用域
     * @method scope
     *
     * @param array $scope
     * @return $this
     *
     * @author luffyzhao@vip.126.com
     */
    public function scope(array $scope)
    {
        foreach ($scope as $key => $value) {
            if (is_numeric($key)) {
                $this->model = $this->model->{$value}();
            } else {
                $this->model = call_user_func_array([$this->model, $key], $value);
            }
        }

        return $this;
    }

    /**
     * join
     * @method join
     * @param array $relations
     * @return $this
     * @author luffyzhao@vip.126.com
     */
    public function join(array $relations){
        foreach ($relations AS $key=>$value){
            $type = 'inner';
            if(!is_numeric($key)){
                $type = $value;
                $value = $key;
            }
            $relation = $this->getRelation($value);
            if ($relation instanceof BelongsTo) {
                $this->model = $this->model->join(
                    $relation->getRelated()->getTable(),
                    $this->model->getTable().'.'.$relation->getOwnerKey(),
                    '=',
                    $relation->getRelated()->getTable().'.'.$relation->getForeignKey(),
                    $type
                );
            }else if($relation instanceof BelongsToMany) {
                $this->model = $this->model->join(
                    $relation->getRelated()->getTable(),
                    $this->model->getTable().'.'.$relation->getOwnerKey(),
                    '=',
                    $relation->getRelated()->getTable().'.'.$relation->getForeignKey(),
                    $type
                );
            }else{
                $this->model = $this->model->join(
                    $relation->getRelated()->getTable(),
                    $relation->getQualifiedParentKeyName(),
                    '=',
                    $relation->getExistenceCompareKey(),
                    $type
                );
            }
        }
        return $this;
    }

    /**
     * getRelation
     * @method getRelation
     * @param $name
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function getRelation($name){
        if($this->model instanceof Model){
            return $this->model->$name();
        }else{
            return $this->model->getModel()->$name();
        }
    }
}
