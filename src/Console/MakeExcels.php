<?php

namespace luffyzhao\laravelTools\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class MakeExcels extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:excel {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建导出excel辅助类';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Excel command';

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Excels\Modules';
    }

    /**
     * 获取stub文件.
     *
     * @method getStub
     *
     * @return string stub模板路径
     *
     * @author luffyzhao@vip.126.com
     */
    protected function getStub()
    {
        return __DIR__ .'/../Excels/stubs/Excel.stub';
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
