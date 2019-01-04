<?php
/**
 * Created by PhpStorm.
 * User: luffyzhao
 * Date: 2019/1/4
 * Time: 21:46
 */

namespace LTools\Excels;


use LTools\Contracts\Search\SearchInterface;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

abstract class ExcelAbstract
    implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    /**
     * 必须放个搜索
     *
     * @param SearchInterface $search
     */
    abstract public function __construct(SearchInterface $search);
    /**
     * 数据集合
     * @method collection
     *
     * @return \Illuminate\Support\Collection
     *
     * @author luffyzhao@vip.126.com
     */
    abstract public function collection();

}