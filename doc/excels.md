# Excels导出辅助插件

### 插件介绍

Excels导出辅助插件

### 创建 Excels
```
php artisan make:excel User
```

上面命令会创建一个 App\Excels\Modules\UserExcel::class 的类

### 编写Search

```php
<?php
namespace App\Excels\Modules;


use App\Excels\Facades\ExcelAbstract;
use App\Repositories\Modules\User\Interfaces;
use App\Searchs\Modules\User\ExcelSearch;

class CarExcel extends ExcelAbstract
{

    public function __construct(Interfaces $repo)
    {
        parent::__construct($repo);
    }




    /**
     * Excel标题列
     * @return {[type]} [description]
     */
    public function headings()
    {
        return ['ID','手机号码','姓名'];
    }


    /**
     * @param mixed $row
     *
     * @return array
     */
    public function map($row)
    {
        return [
            $row->id,
            $this->phone,
            $this->name
        ];
    }


    /**
     * 搜索参数
     * @return {[type]} [description]
     */
    protected function getAttributes()
    {
        return new ExcelSearch(request()->only([
            'phone',
            'name',
        ]));
    }


}
```

> 更多用法 请参考 [maatwebsite/excel](https://github.com/Maatwebsite/Laravel-Excel)