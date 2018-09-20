<?php

namespace luffyzhao\laravelTools\Repositories\Facades;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use luffyzhao\laravelTools\Repositories\Exceptions\RepoException;

abstract class RepositoriesAbstract implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;


    /**
     * @var null|Builder
     */
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
     * @return Builder|null
     */
    protected function getQuery($replace = false){
        if(!$this->query){
            $this->query = $this->getModel()->newQuery();
        }
        if($replace){
            $query = $this->query;
            $this->query = null;
            return $query;
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
     * @param  int|string $id 主键ID
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
        return $this->getQuery(true)->findOrFail($id, $columns);
    }

    /**
     * 通过主键查找多个模型。
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array $ids 主键IDs
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @author luffyzhao@vip.126.com
     */
    public function findMany($ids, $columns = ['*'])
    {
        return $this->getQuery(true)->findMany($ids, $columns);
    }

    /**
     * 通过where条件查找一个模型.
     *
     * @method findWhere
     *
     * @param array $attributes Where条件
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @author luffyzhao@vip.126.com
     */
    public function findWhere(array $attributes, array $columns = ['*'])
    {
        return $this->getQuery(true)
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
    public function findValue(array $attributes, string $columns)
    {
        return $this->getQuery(true)->where(
            $this->parseWhere($attributes)
        )->value($columns);
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
        return $this->getQuery(true)->get($columns);
    }

    /**
     * 通过where条件查找多个模型.
     *
     * @method getWhere
     *
     * @param array $attributes Where条件
     * @param array $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @author luffyzhao@vip.126.com
     */
    public function getWhere(array $attributes, array $columns = ['*'])
    {
        return $this->getQuery(true)
            ->where(
                $this->parseWhere($attributes)
            )->get($columns);
    }

    /**
     * 分块处理
     * @method chunkById
     * @param array $attributes Where条件
     * @param int $count 每次获取 $count 条数据
     * @param callable $callback 回调
     * @param string $column 字段
     * @param string|null $alias 表别名
     *
     * @return bool
     *
     * @author luffyzhao@vip.126.com
     */
    public function chunkById(array $attributes, int $count, callable $callback, string $column = null, string $alias =
    null)
    {
        return $this->getQuery(true)
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
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->getQuery(true)
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
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->getQuery(true)
            ->updateOrCreate($this->parseWhere($attributes), $values);
    }

    /**
     * 查找与属性匹配的记录并分页.
     *
     * @method paginate
     *
     * @param array $attributes Where条件
     * @param int|null $perPage 每页多少条
     * @param array $columns 获取字段
     * @param string $pageName 分页input字段
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function paginate(array $attributes, int $perPage = null, array $columns = ['*'], $pageName = 'page', int $page = null) {
        return $this->getQuery(true)->where(
            $this->parseWhere($attributes)
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
     * @return \Illuminate\Contracts\Pagination\Paginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function simplePaginate(array $attributes, int $perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
        return $this->getQuery(true)
            ->where($this->parseWhere($attributes))
            ->select($columns)->simplePaginate($perPage);
    }

    /**
     * 查找与属性匹配的记录
     * @method limit
     * @param array $attributes
     * @param int $perPage
     * @param array $columns
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function limit(array $attributes, int $perPage = null, array $columns = ['*'])
    {
        return $this->getQuery(true)
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
     * @return \Illuminate\Database\Eloquent\Model|bool
     *
     * @author luffyzhao@vip.126.com
     */
    public function create(array $attributes = [])
    {
        $model = $this->getModel();
        $res = $model->fill($attributes)->save();

        if($res){
            return $model;
        }
        return false;
    }

    /**
     * 更新
     * @method update
     *
     * @param Model $model
     * @param array $values
     * @return Model | bool
     *
     * @author luffyzhao@vip.126.com
     */
    public function update(Model $model, array $values)
    {
        $model->fill($values)->save();

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

        return $this->getQuery(true)
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
    public function deleteWhere(array $attributes)
    {
        return $this->getQuery(true)
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
        $this->getQuery()->with($with);

        return $this;
    }

    /**
     * make别名
     * @method with
     * @param array $with
     * @return RepositoriesAbstract
     * @author luffyzhao@vip.126.com
     */
    public function with(array $with = array())
    {
        return $this->make($with);
    }

    /**
     * @param array $with
     * @return $this
     */
    public function withCount(array $with = array()){
        $this->getQuery()->withCount($with);

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
                $this->getQuery()->{$value}();
            } else {
                call_user_func_array([$this->getQuery(), $key], $value);
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
    public function join(array $relations)
    {
        foreach ($relations as $value) {
            if (is_string($value)) {
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
            if (is_numeric($relation)) {
                $relation = $type;
                $type = 'inner';
            }
            $relatedRelation = $currentModel->$relation();
            $relatedModel = $relatedRelation->getRelated();
            $relatedPrimaryKey = $relatedModel->getKeyName();
            $relatedTable = $relatedModel->getTable();

            if ($relatedRelation instanceof BelongsTo) {
                $keyRelated = $relatedRelation->getForeignKey();
                $relatedPrimaryKey = $relatedRelation->getOwnerKey();
                $this->getQuery()->join(
                    $relatedTable,
                    function ($join) use (
                        $relatedTable,
                        $keyRelated,
                        $currentTable,
                        $relatedPrimaryKey,
                        $relatedModel
                    ) {
                        $join->on($relatedTable.'.'.$relatedPrimaryKey, '=', $currentTable.'.'.$keyRelated);
                    },
                    null,
                    null,
                    $type
                );
            } else {
                if ($relatedRelation instanceof HasOne) {
                    $keyRelated = $relatedRelation->getQualifiedForeignKeyName();
                    $keyRelated = last(explode('.', $keyRelated));
                    $this->getQuery()->join(
                        $relatedTable,
                        function ($join) use (
                            $relatedTable,
                            $keyRelated,
                            $currentTable,
                            $relatedPrimaryKey,
                            $relatedModel,
                            $currentPrimaryKey
                        ) {
                            $join->on($relatedTable.'.'.$keyRelated, '=', $currentTable.'.'.$currentPrimaryKey);
                        },
                        null,
                        null,
                        $type
                    );
                } else {
                    throw new RepoException('relations instanceof HasOne or BelongsTo');
                }
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
    protected function parseWhere($attributes)
    {
        return $attributes instanceof Arrayable ? $attributes->toArray() : (array)$attributes;
    }
}
