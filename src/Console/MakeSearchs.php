<?php

namespace luffyzhao\laravelTools\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeSearchs extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:search {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建搜索辅助类';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Search command';

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Searchs\Modules';
    }

    /**
     * 获取stub文件.
     *
     * @method getStub
     *
     * @return [type] [description]
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getStub()
    {
        return __DIR__ .'/../Searchs/stubs/Search.stub';
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
