# Sql 写进日志-事件

### 介绍
把sql语句记录到日志里

### 使用
在 laravel 自带的 EventServiceProvider 类里 listen 添加
```
 'Illuminate\Database\Events\QueryExecuted' => [
         'luffyzhao\laravelTools\Listeners\QueryListeners'
   ]
```

### 生成事件

```
php artisan event:generate
```
