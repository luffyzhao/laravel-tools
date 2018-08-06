<?php

namespace luffyzhao\laravelTools\Repositories\Facades;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
     * Create a new instance of the given model.
     * @method newModel
     * @return $this
     * @author luffyzhao@vip.126.com
     */
    public function newModel(){
        if($this->model instanceof Model){
            $this->model = $this->model->newInstance();
        }else{
            $this->model = $this->model->getModel()->newInstance();
        }
        return $this;
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
        $res = $this->model->findOrFail($id, $columns);
        $this->newModel();
        return $res;
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
        $res = $this->model->findMany($ids, $columns);
        $this->newModel();
        return $res;
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
    public function findWhere($attributes, array $columns = ['*'])
    {
        $res = $this->model->where($this->parseWhere($attributes))->firstOrFail($columns);
        $this->newModel();
        return $res;
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
    public function findValue($attributes, string $columns)
    {
        $res = $this->model->where($this->parseWhere($attributes))->value($columns);
        $this->newModel();
        return $res;
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
        $res = $this->model->get($columns);
        $this->newModel();
        return $res;
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
    public function getWhere($attributes, array $columns = ['*'])
    {
        $res = $this->model->where($this->parseWhere($attributes))->get($columns);
        $this->newModel();
        return $res;
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
    public function chunkById($attributes, $count, callable $callback, $column = null, $alias = null)
    {
        $res = $this->model->where($this->parseWhere($attributes))->chunkById($count, $callback, $column, $alias);
        $this->newModel();
        return $res;
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
    public function firstOrCreate($attributes, array $values = [])
    {
        $res = $this->model->firstOrCreate($this->parseWhere($attributes), $values);
        $this->newModel();
        return $res;
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
    public function updateOrCreate($attributes, array $values = [])
    {
        $res = $this->model->updateOrCreate($this->parseWhere($attributes), $values);
        $this->newModel();
        return $res;
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
    public function paginate($attributes, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $res = $this->model->where($this->parseWhere($attributes))->paginate($perPage, $columns, $pageName, $page);
        $this->newModel();
        return $res;
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
    public function simplePaginate($attributes, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $res = $this->model->where($this->parseWhere($attributes))->select($columns)->simplePaginate($perPage);
        $this->newModel();
        return $res;
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
    public function limit($attributes, $perPage = null, $columns = ['*'])
    {
        $res = $this->model->where($this->parseWhere($attributes))->select($columns)->limit($perPage)->get();
        $this->newModel();
        return $res;
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
        $res = $this->model->create($this->parseWhere($attributes));
        $this->newModel();
        return $res;
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
            $model = $model->where($this->parseWhere($attributes));
        }

        $model->fill($values)->save(['touch' => false]);
        $this->newModel();
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
        return $model->where($this->parseWhere($attributes))->update($data);
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
        $res = $model->delete();
        $this->newModel();
        return $res;
    }

    /**
     * 通过查询删除模型
     * @method deleteWhere
     * @param array $attributes
     * @return mixed|void
     * @author luffyzhao@vip.126.com
     */
    public function deleteWhere($attributes)
    {
        $res = $this->model->where($this->parseWhere($attributes))->delete();
        $this->newModel();
        return $res;
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
     * make别名
     * @method with
     * @param array $with
     * @return RepositoriesAbstract
     * @author luffyzhao@vip.126.com
     */
    public function with(array $with = array()){
        return $this->make($with);
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
                    $relation->getRelated()->getTable().$relation->getForeignKey(),
                    $this->model->getTable().'.',
                    '=',
                    $relation->getRelated()->getTable().'.'.$relation->getOwnerKey(),
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
            }else if($relation instanceof  HasOne){
                $this->model = $this->model->join(
                    $relation->getRelated()->getTable(),
                    $relation->getQualifiedParentKeyName(),
                    '=',
                    $relation->getExistenceCompareKey(),
                    $type
                );
            }else{
                throw new RelationNotFoundException( $value."  的关联类型不支付 join ");
            }
        }
        return $this;
    }

    /**
     * 格式化where条件
     * @method parseWhere
     * @param $attributes
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function parseWhere($attributes){
        return $attributes instanceof Arrayable ? $attributes->toArray() : (array) $attributes;
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
