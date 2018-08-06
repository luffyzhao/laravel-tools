# laravel 开发辅助工具

### 配置

#### 添加服务提供商

将下面这行添加至 config/app.php 文件 providers 数组中：

```php
'providers' => [
  ...
  App\Plugins\Auth\Providers\LaravelServiceProvider::class
 ]
```

### 插件及文档

- [redisToken认证](./doc/auths-redis-token.md)
- [Repository 模式](./doc/reppositories.md)
- [表单搜索辅助插件](./doc/search.md)
- [Excels导出辅助插件](./doc/excels.md)
- [Sign 加签](./doc/sign.md)
- [Sql 写进日志-事件](./doc/sqlToLog.md)
- [Controller Traits](./doc/ControllerTraits.md)

### 更新日志

- [CHANGELOG](./doc/CHANGELOG.md)

### 最后想说说
第一次把自己东西写个文件放在git上，欢迎`pr`和`star`。会一直更新和优化下去。很多地方是之前赶项目时写的。现在有空会一个个优化。