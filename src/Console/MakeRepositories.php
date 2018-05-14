<?php

namespace luffyzhao\laravelTools\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class MakeRepositories extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo {name}';

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
     * Execute the console command.
     *
     * @return mixed
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
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function makeRepo($name)
    {
        $stubs = $this->getStub();
        foreach ($stubs as $key => $file) {
            $stub = $this->files->get($file);
            $path = str_replace('.stub', '.php', pathinfo($file, PATHINFO_BASENAME));
            $this->files->put($this->getPath($name).'/'.$path, $this->replaceModel($stub, $name));
        }
    }

    /**
     * [replaceModel description].
     *
     * @method replaceModel
     *
     * @param [type] $stub [description]
     * @param [type] $name [description]
     *
     * @return [type] [description]
     *
     * @author luffyzhao@vip.126.com
     */
    public function replaceModel($stub, $name)
    {
        return str_replace('DummyModel', $name, $stub);
    }

    /**
     * 模型是否存在.
     *
     * @method existsModel
     *
     * @param [type] $name [description]
     *
     * @return [type] [description]
     *                author
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
     * Get the fully-qualified model class name.
     *
     * @param string $model
     *
     * @return string
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
     * Determine if the class already exists.
     *
     * @param string $rawName
     *
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->isDirectory($this->getPath($rawName));
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/Repositories/Modules/'.str_replace('\\', '/', $name);
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return [
          __DIR__.'/../Repositories/stubs/Cache.stub',
          __DIR__.'/../Repositories/stubs/Eloquent.stub',
          __DIR__.'/../Repositories/stubs/Interfaces.stub',
          __DIR__.'/../Repositories/stubs/Provider.stub',
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the command.'],
        ];
    }
}
