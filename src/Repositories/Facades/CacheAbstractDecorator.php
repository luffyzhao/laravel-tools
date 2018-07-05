<?php
/**
 * 现在没有用到这个缓存
 * 待之后要用时直接开启
 */
namespace luffyzhao\laravelTools\Repositories\Facades;

use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

abstract class CacheAbstractDecorator implements RepositoryInterface
{
    protected $repo;
    protected $cache;

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
        return $this->repo->getModel();
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
        return $this->repo->getTable();
    }

    /**
     * 通过主键查找一个模型.
     *
     * @method find
     *
     * @param int   $id      主键ID
     * @param array $columns 获取字段
     *
     * @return Illuminate\Support\Collection|static|null
     *
     * @author luffyzhao@vip.126.com
     */
    public function find($id, array $columns = ['*'])
    {
        $cacheKey = $this->getCache('find', [
          $id,
          $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->find($id, $columns);

        $this->cache->put($cacheKey, $model);

        return $model;
    }

    /**
     * 通过主键查找多个模型。
     *
     * @param \Illuminate\Contracts\Support\Arrayable|array $ids     主键IDs
     * @param array                                         $columns 获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @author luffyzhao@vip.126.com
     */
    public function findMany($ids, $columns = ['*'])
    {
        $cacheKey = $this->getCache('findMany', [
          $ids,
          $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->findMany($ids, $columns);
        $this->cache->put($cacheKey, $model);

        return $model;
    }

    /**
     * 通过where条件查找一个模型.
     *
     * @method findWhere
     *
     * @param array $attributes Where条件
     * @param array $columns    获取字段
     *
     * @return Illuminate\Support\Collection|static|null
     *
     * @author luffyzhao@vip.126.com
     */
    public function findWhere(array $attributes, array $columns = ['*'])
    {
        $cacheKey = $this->getCache('findWhere', [
          $attributes,
          $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->findWhere($attributes, $columns);

        $this->cache->put($cacheKey, $model);

        return $model;
    }

    /**
     * 从查询的第一个结果获取单个列的值。
     *
     * @method findValue
     *
     * @param array  $attributes Where条件
     * @param string $columns    获取字段
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function findValue(array $attributes, string $columns)
    {
        $cacheKey = $this->getCache('findValue', [
          $attributes,
          $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->findValue($attributes, $columns);

        $this->cache->put($cacheKey, $model);

        return $model;
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
        $cacheKey = $this->getCache('get', [
          $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->get($columns);

        $this->cache->put($cacheKey, $model);

        return $model;
    }

    /**
     * 通过where条件查找多个模型.
     *
     * @method getWhere
     *
     * @param array $attributes Where条件
     * @param array $columns    获取字段
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     *
     * @author luffyzhao@vip.126.com
     */
    public function getWhere(array $attributes, array $columns = ['*'])
    {
        $cacheKey = $this->getCache('getWhere', [
          $attributes,
          $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->getWhere($attributes, $columns);

        $this->cache->put($cacheKey, $model);

        return $model;
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
    public function chunkById(array $attributes, $count, callable $callback, $column = null, $alias = null){
        $this->repo->chunkById($attributes, $count, $callback, $column, $alias);
    }

    /**
     * 获取与属性匹配的第一个记录不存在就创建。
     *
     * @method firstOrCreate
     *
     * @param array $attributes Where条件
     * @param array $values     附加填充值
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        $cacheKey = $this->getCache('firstOrCreate', [
          $attributes,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $this->cache->flush();

        return $this->repo->firstOrCreate($attributes, $values);
    }

    /**
     * 修改与属性匹配的记录不存在就创建。
     *
     * @method updateOrCreate
     *
     * @param array $attributes Where条件
     * @param array $values     附加填充值
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        $this->cache->flush();
        return $this->repo->updateOrCreate($attributes, $values);
    }

    /**
     * 查找与属性匹配的记录并分页.
     *
     * @method paginate
     *
     * @param array  $attributes Where条件
     * @param [type] $perPage    每页多少条
     * @param array  $columns    获取字段
     * @param string $pageName   分页input字段
     * @param [type] $page       当前页码
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @author luffyzhao@vip.126.com
     */
    public function paginate(array $attributes, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        if(!$page){
            $page = request()->input($pageName);
        }
        $cacheKey = $this->getCache('paginate', [
          $attributes,
          $perPage,
          $columns,
          $pageName,
          $page,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->paginate($attributes, $perPage, $columns, $pageName, $page);

        $this->cache->put($cacheKey, $model);

        return $model;
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
        if(!$page){
            $page = request()->input($pageName);
        }
        $cacheKey = $this->getCache('simplePaginate', [
          $attributes,
          $perPage,
          $columns,
          $pageName,
          $page
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->simplePaginate($attributes, $perPage, $columns, $pageName, $page);

        $this->cache->put($cacheKey, $model);

        return $model;
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
    public function limit(array $attributes, $perPage = null, $columns = ['*']){
        $cacheKey = $this->getCache('limit', [
            $attributes,
            $perPage,
            $columns,
        ]);

        if (!is_string($cacheKey)) {
            return $cacheKey;
        }

        $model = $this->repo->limit($attributes, $perPage, $columns);
        $this->cache->put($cacheKey, $model);

        return $model;
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
        $this->cache->flush();

        return $this->repo->create($attributes);
    }

    /**
     * 更新模型.
     *
     * @method update
     *
     * @param Model $model
     *
     * @param array $values 更新数据
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @author luffyzhao@vip.126.com
     */
    public function update(Model $model, array $values, array $attributes = [])
    {
        $this->cache->flush();

        return $this->repo->update($model, $values, $attributes);
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
        $this->cache->flush();

        return $this->repo->updateWhere($values, $attributes);
    }

    /**
     * 删除数据模型.
     *
     * @method delete
     *
     * @param Model $model 删除模型
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function delete(Model $model)
    {
        $this->cache->flush();

        return $this->repo->delete($model);
    }

    /**
     * Make a new instance of the entity to query on.
     *
     * @param array $with
     */
    public function make(array $with = array())
    {
        $this->repo = $this->repo->make($with);

        return $this;
    }

    /**
     * 添加一个获取多个作用域
     *
     * @method scope
     *
     * @param array $scope [description]
     *
     * @return [type] [description]
     *
     * @author luffyzhao@vip.126.com
     */
    public function scope(array $scope)
    {
        $this->repo = $this->repo->scope($scope);

        return $this;
    }

    /**
     * 获取缓存.
     *
     * @method getCacheKey
     *
     * @param [type] $method  请求方法
     * @param array  $parames 参数
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getCache($method, array $parames = [])
    {
        $cacheKey = md5(get_class($this).'::'.$method.'('.json_encode($parames).')');
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        return $cacheKey;
    }
}
