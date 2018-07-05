<?php

namespace luffyzhao\laravelTools\Searchs\Facades;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use luffyzhao\laravelTools\Searchs\Exceptions\SearchException;

abstract class SearchAbstract implements Arrayable
{
    /**
     * 关系映射.
     *
     * @var [type]
     */
    protected $relationship = [];
    /**
     * 搜索数据.
     *
     * @var [type]
     */
    protected $attributes = [];

    /**
     * @var array
     * @author luffyzhao@vip.126.com
     */
    protected $data = [];
    
    /**
     * 条件.
     *
     * @var [type]
     */
    protected $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'like', 'like binary', 'not like', 'ilike',
        '&', '|', '^', '<<', '>>',
        'rlike', 'regexp', 'not regexp',
        '~', '~*', '!~', '!~*', 'similar to',
        'not similar to', 'not ilike', '~~*', '!~~*',
        'in', 'null', 'no null', 'date', 'month', 'day', 'year', 'raw', 'between', 'closure'
    ];
    
    /**
     * 要解析的条件.
     *
     * @var [type]
     */
    protected $parses = [
        'in', 'null', 'no null', 'date', 'month', 'day', 'year', 'raw', 'between',
    ];

    /**
     * 构造函数
     * SearchAbstract constructor.
     * @param array $attributes
     * @throws SearchException
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;

        $this->data = $this->handle();
    }

    /**
     * 获取属性真实值
     * @method getAttr
     * @param $key
     * @param $default
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getAttr($key, $default)
    {
        $method = 'get' . Str::camel($key) . 'Attribute';

        if (method_exists($this, $method)) {
            $default = $this->$method($default, $this->attributes);
        }
        
        return $default;
    }

    /**
     * 执行
     * @method handle
     *
     * @return array
     * @throws SearchException
     *
     * @author luffyzhao@vip.126.com
     */
    protected function handle()
    {
        $attributes = [];
        if (!empty($this->relationship)) {
            foreach ($this->relationship as $key => $value) {
                if (isset($this->attributes[$key])) {
                    $attr = $this->getAttr($key, $this->attributes[$key]);
                    if (false !== $attr) {
                        $attributes[] = $this->validate($key, $value, $attr);
                    }
                }
            }
        }
        
        return $attributes;
    }

    /**
     * 验证
     * @method validate
     * @param $key
     * @param $value
     * @param $attr
     *
     * @return array
     * @throws SearchException
     *
     * @author luffyzhao@vip.126.com
     */
    protected function validate($key, $value, $attr)
    {
        if (!in_array($value, $this->operators)) {
            throw new SearchException('搜索条件超出可选范围！');
        }
        if (in_array($value, $this->parses)) {
            $attr = $this->parse($key, $value, $attr);
        }
        
        if ($attr instanceof \Closure) {
            return [$attr];
        } else {
            return [$key, $value, $attr];
        }
    }
    
    /**
     * 解析条件.
     *
     * @method parse
     *
     * @param  [type] $key   字段
     * @param  [type] $value 条件
     * @param  [type] $attr  值
     *
     * @return \Closure
     *
     * @author luffyzhao@vip.126.com
     */
    protected function parse($key, $value, $attr)
    {
        if ($attr instanceof \Closure) {
            return $attr;
        }
        
        return function ($query) use ($key, $value, $attr) {
            switch ($value) {
                case 'in':
                    $query->whereIn($key, $attr);
                    break;
                case 'null':
                    $query->whereNull($key);
                    break;
                case 'no null':
                    $query->whereNotNull($key);
                    break;
                case 'date':
                    $query->whereDate($key, $attr);
                    break;
                case 'month':
                    $query->whereMonth($key, $attr);
                    break;
                case 'day':
                    $query->whereDay($key, $attr);
                    break;
                case 'year':
                    $query->whereYear($key, $attr);
                    break;
                case 'column':
                    $query->whereColumn($key, $attr);
                    break;
                case 'exists':
                    $query->whereColumn($attr);
                    break;
                case 'raw':
                    $query->whereRaw($attr);
                    break;
                case 'between':
                    $query->whereBetween($key, $attr);
                    break;
            }
        };
    }

    /**
     * 格式化时间
     * @method formatDatetime
     * @param $dateTime
     * @param string $format
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function formatDatetime($dateTime, $format = 'Y-m-d'){
        return (new \DateTime($dateTime))->format($format);
    }
    /**
     * 转数组.
     *
     * @method toArray
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    public function toArray()
    {
        return $this->data;
    }
}
