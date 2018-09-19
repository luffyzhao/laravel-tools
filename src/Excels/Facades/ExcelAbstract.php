<?php

namespace luffyzhao\laravelTools\Excels\Facades;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use luffyzhao\laravelTools\Repositories\Facades\RepositoryInterface;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;


abstract class ExcelAbstract implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;
    /**
     * @var RepositoryInterface
     * @author luffyzhao@vip.126.com
     */
    protected $repo;

    public function __construct(RepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * 数据集合
     * @method collection
     *
     * @return \Illuminate\Support\Collection
     *
     * @author luffyzhao@vip.126.com
     */
    public function collection()
    {
        $collection = collect([]);
        $this->repo->chunkById($this->getAttributes(), $this->getChunkCount(), function (Collection $resules) use (&$collection){
            $collection = $resules->merge($collection);
        }, $this->getChunkColumn(), $this->getChunkAlias());

        return $collection;
    }

    /**
     * 获取表别名
     * @method getChunkAlias
     *
     * @return null
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getChunkAlias(){
        return null;
    }

    /**
     * 获取数据库字段
     * @method getChunkColumn
     *
     * @return null
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getChunkColumn(){
        return null;
    }

    /**
     * 获取每次从数据库中取用多少条
     * @method getChunkCount
     *
     * @return int
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getChunkCount(){
        return 100;
    }
    /**
     * 获取where条件
     * @method getAttributes
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    abstract protected function getAttributes();

}