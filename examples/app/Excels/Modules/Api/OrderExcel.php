<?php

namespace App\Excels\Modules\Api;


use App\Searchs\Modules\Api\Order\RepoExcelSearch;
use luffyzhao\laravelTools\Excels\Facades\ExcelAbstract;

class OrderExcel extends ExcelAbstract
{

    /**
     * 获取where条件
     * @method getAttributes
     *
     * @return RepoExcelSearch
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getAttributes()
    {
        return new RepoExcelSearch(
            request()->all()
        );
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return ['订单编号'];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->order_no
        ];
    }

}