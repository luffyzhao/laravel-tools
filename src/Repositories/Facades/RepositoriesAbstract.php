<?php

namespace luffyzhao\laravelTools\Repositories\Facades;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use luffyzhao\laravelTools\Repositories\Exceptions\RepoException;

abstract class RepositoriesAbstract implements RepositoryInterface
{
    protected $model;

    protected $query;

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
     * @method newQuery
     * @return \Illuminate\Database\Eloquent\Builder
     * @author luffyzhao@vip.126.com
     */
    public function newQuery(){
        if(!$this->query){
            $this->query = $this->model->newQuery();
        }
        return $this->query;
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
        $res = $this->newQuery()->findOrFail($id, $columns);
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
        $res = $this->newQuery()->findMany($ids, $columns);
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
     * @return \Illuminate\Database\Eloquent\Builder|Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function findWhere($attributes, array $columns = ['*'])
    {
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->firstOrFail($columns);
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
        return $this->newQuery()->where($this->parseWhere($attributes))->value($columns);
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
        return $this->newQuery()->get($columns);
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
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->get($columns);
    }

    /**
     * 分块处理
     * @method chunkById
     * @param array $attributes Where条件
     * @param int $count 每次获取$count条数据
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
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->chunkById($count, $callback, $column, $alias);
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
        return $this->newQuery()
            ->firstOrCreate($this->parseWhere($attributes), $values);
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
        return $this->newQuery()
            ->updateOrCreate($this->parseWhere($attributes), $values);
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
        return $this->newQuery()->where($this->parseWhere($attributes))->paginate($perPage, $columns, $pageName, $page);
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
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->select($columns)->simplePaginate($perPage);
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
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->select($columns)->limit($perPage)->get();
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
        return $this->getModel()->create($this->parseWhere($attributes));
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
        return $model;
    }

    /**
     * 根据条件更新
     * @method updateWhere
     * @param array $values
     * @param array $attributes
     *
     * @return int
     *
     * @author luffyzhao@vip.126.com
     */
    public function updateWhere(array $values, array $attributes)
    {
        $data = array_intersect_key($values, array_flip($this->getModel()->getFillable()));
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->update($data);
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
        return $model->delete();
    }

    /**
     * 通过查询删除模型
     * @method deleteWhere
     * @param array $attributes
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    public function deleteWhere($attributes)
    {
        return $this->newQuery()
            ->where($this->parseWhere($attributes))
            ->delete();
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
        $this->newQuery()->with($with);

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
                $this->newQuery()->{$value}();
            } else {
                call_user_func_array([$this->newQuery(), $key], $value);
            }
        }

        return $this;
    }

    /**
     * join
     * @method join
     * @param array $relations
     * @throws \Exception
     * @return $this
     * @author luffyzhao@vip.126.com
     */
    public function join(array $relations){
        foreach ($relations as $value){
            if(is_string($value)){
                $value = explode('.', $value);
            }
            $this->performJoin($value);
        }
        return $this;
    }

    /**
     *
     * @method performJoin
     * @param $relations
     * @throws RepoException
     * @throws RepoException
     * @author luffyzhao@vip.126.com
     */
    private function performJoin(array $relations)
    {
        $table = $this->getTable();
        $primaryKey = $this->getModel()->getKeyName();

        $currentModel = $this->getModel();
        $currentPrimaryKey = $primaryKey;
        $currentTable = $table;

        foreach ($relations as $relation => $type) {
            if(is_numeric($relation)){
                $relation = $type;
                $type = 'inner';
            }
            $relatedRelation = $currentModel->$relation();
            $relatedModel = $relatedRelation->getRelated();
            $relatedPrimaryKey = $relatedModel->getKeyName();
            $relatedTable = $relatedModel->getTable();

            if ($relatedRelation instanceof BelongsTo) {
                $keyRelated = $relatedRelation->getForeignKey();
                $this->newQuery()->join($relatedTable , function ($join) use ($relatedTable, $keyRelated, $currentTable, $relatedPrimaryKey, $relatedModel) {
                    $join->on($relatedTable . '.' . $relatedPrimaryKey, '=', $currentTable . '.' . $keyRelated);
                }, null, null, $type);
            }else if($relatedRelation instanceof HasOne){
                $keyRelated = $relatedRelation->getQualifiedForeignKeyName();
                $keyRelated = last(explode('.', $keyRelated));
                $this->newQuery()->join($relatedTable, function ($join) use ($relatedTable, $keyRelated, $currentTable, $relatedPrimaryKey, $relatedModel, $currentPrimaryKey) {
                    $join->on($relatedTable . '.' . $keyRelated, '=', $currentTable . '.' . $currentPrimaryKey);
                }, null, null, $type);
            } else {
                throw new RepoException('relations instanceof HasOne or BelongsTo');
            }

            $currentModel = $relatedModel;
            $currentPrimaryKey = $relatedPrimaryKey;
            $currentTable = $relatedTable;
        }
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
}
