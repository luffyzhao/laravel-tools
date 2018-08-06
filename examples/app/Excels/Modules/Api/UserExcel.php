<?php

namespace App\Excels\Modules\Api;


use luffyzhao\laravelTools\Excels\Facades\ExcelAbstract;

class UserExcel extends ExcelAbstract
{

    /**
     * 获取where条件
     * @method getAttributes
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getAttributes()
    {
        return [];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [];
    }

    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row): array
    {
        return $row;
    }

}