<?php

namespace luffyzhao\laravelTools\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeRepositories extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:repo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建仓库模型';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository command';

    /**
     * 执行handle
     * @method handle
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @author luffyzhao@vip.126.com
     */
    public function handle()
    {
        $name = $this->getNameInput();

        if ($this->alreadyExists($name)) {
            $this->error($this->type.' already exists!');

            return false;
        }

        $this->existsModel($name);

        $this->makeDirectory($this->getPath($name));

        $this->makeRepo($name);
    }

    /**
     * 生成仓库类
     * @method makeRepo
     * @param $name
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     *
     * @author luffyzhao@vip.126.com
     */
    protected function makeRepo($name)
    {
        $stubs = $this->getStub();
        foreach ($stubs as $key => $file) {
            $stub = $this->files->get($file);
            $this->files->put($this->getPath($name).'/'.$key.'.php', $this->replaceModel($stub, $name));
        }

    }

    /**
     * DummyModel字符替换
     * @method replaceModel
     * @param $stub
     * @param $name
     *
     * @return mixed
     *
     * @author luffyzhao@vip.126.com
     */
    public function replaceModel($stub, $name)
    {
        return str_replace('DummyModel', $name, $stub);
    }

    /**
     * 模型是否存在
     * @method existsModel
     * @param $name
     *
     * @author luffyzhao@vip.126.com
     */
    public function existsModel($name)
    {
        $class = 'Model\\'.$name;
        $parentModelClass = $this->parseModel($class);
        if (!class_exists($parentModelClass)) {
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $parentModelClass]);
            }
        }
    }

    /**
     * 仓库名称格式化
     * @method parseModel
     * @param $model
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (!Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace.$model;
        }

        return $model;
    }

    /**
     * 仓库是否存在
     * @method alreadyExists
     * @param string $rawName
     *
     * @return bool
     *
     * @author luffyzhao@vip.126.com
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->isDirectory($this->getPath($rawName));
    }

    /**
     * 获取仓库路径
     * @method getPath
     * @param string $name
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/Repositories/Modules/'.str_replace('\\', '/', $name);
    }

    /**
     * 创建目录
     * @method makeDirectory
     * @param string $path
     *
     * @return string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * 获取stub
     * @method getStub
     *
     * @return array|string
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getStub()
    {
        $data = [
            'Eloquent' => __DIR__.'/../Repositories/stubs/Eloquent.stub',
            'Interfaces' => __DIR__.'/../Repositories/stubs/Interfaces.stub',
        ];

        if($this->option('cache')){
            $data['Provider'] = __DIR__.'/../Repositories/stubs/Provider_cache.stub';
            $data['Cache'] = __DIR__.'/../Repositories/stubs/Cache.stub';
        }else{
            $data['Provider'] =__DIR__.'/../Repositories/stubs/Provider.stub';
        }

        return $data;
    }

    /**
     * 默认参数
     * @method getArguments
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, '仓库名称'],
        ];
    }

    /**
     * option参数
     * @method getOptions
     *
     * @return array
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getOptions()
    {
        return [
            ['cache', 'c', InputOption::VALUE_NONE, '是否缓存'],
        ];
    }
}
