# 表单搜索辅助插件

### 插件介绍

把表单提交的一些参数传换成 `where` 语句.

### 创建 Search
生成一个UserController::index控制器使用的搜索辅助类
```
php artisan make:search User\IndexSearch
```

上面命令会创建一个 App\Searchs\Modules\User\IndexSearch::class 的类

> 创建Search时，建议根据 Controller\ActionSearch 的格式创建。

### 编写Search

```php
<?php

namespace App\Searchs\Modules\User;

use luffyzhao\laravelTools\Searchs\Facades\SearchAbstract;

class IndexSearch extends SearchAbstract
{
    protected $relationship = [
        'phone' => '=',
        'name'  => 'like',
        'date' => 'between'
    ];
        
    public function getNameAttribute($value)
    {
        return $value . '%';
    }
    
    public function getDateAttribute($value){
        return function ($query){
            $query->where('date', '>', '2018-05-05')->where('status', 1);
        };
    }
}
```

### 使用Search

```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Modules\User\Interfaces;
use App\Searchs\Modules\User\IndexSearch;

class HomeController extends Controller
{

    protected $repo = null;

    public function __construct(Interfaces $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request){
        return $this->respondWithSuccess(
            $this->repo->getWhere(
                new IndexSearch(
                    $request->only(['phone', 'name', 'date'])
                ), 
                ['*']
            )
          );
    }
}
```

### 生成的sql

请求参数：
```
phone=18565215214&name=成龙&date=2018-08-21
```    

生成的sql

```sql
WHERE (phone = 18565215214) AND (name like '成龙%') AND (date > '2018-05-05' AND status = 1)
```


