<?php
/**
 * Created by PhpStorm.
 * User: luffy
 * Date: 2018/9/21
 * Time: 10:47
 */

namespace LTools\Console\Commands\Backup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Restore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'restore your databases';


    /**
     * @var array 外键
     */
    protected $foreignKey = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '1024M');
        $backups = $this->getPaths(database_path('back-up'));
        if (count($backups) === 0) {
            $this->warn('找不到备份数据！');
        } else {
            $dir = $this->askVersion($backups);
            $this->restorePath($dir);
        }
    }

    /**
     * 还原
     * go
     * @param $dir
     * @author luffyzhao@vip.126.com
     */
    protected function restorePath($dir)
    {

        $tables = $this->getPaths($dir);
        foreach ($tables as $table) {
            if (basename($table) === 'migrations') {
                continue;
            }
            $this->restoreTable($table);
        }
    }

    /**
     * 关闭外键约束
     * @author luffyzhao@vip.126.com
     */
    protected function disableForeignKey()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
    }

    /**
     * restoreTable
     * @param $table
     * @author luffyzhao@vip.126.com
     */
    protected function restoreTable($table)
    {
        $this->disableForeignKey();
        DB::beginTransaction();
        try {
            $this->truncate(basename($table));
            $files = $this->getFiles($table);

            foreach ($files as $file) {
                $string = file_get_contents($file);
                $array = json_decode($string, true);
                if ($array !== false) {
                    DB::table(basename($table))->insert($array);
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

    }

    /**
     * @param $table
     * @author luffyzhao@vip.126.com
     */
    protected function truncate($table)
    {
        DB::statement('truncate table `' . $table . '`;');
    }

    /**
     * 询问要还原的版本号
     * askVersion
     * @param array $backups
     * @return mixed
     * @author luffyzhao@vip.126.com
     */
    protected function askVersion(array $backups)
    {
        $askString = "请选择您要的还原的备份数据:";
        foreach ($backups as $key => $item) {
            $askString .= "\n    [{$key}] {$item} ";
        }
        $index = $this->ask($askString);
        if (!isset($backups[$index])) {
            return $this->askVersion($backups);
        }
        return $backups[$index];
    }

    /**
     * 获取目录下所有的文件
     * getFiles
     * @param $dir
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getFiles($dir): array
    {
        if (substr($dir, -1) !== DIRECTORY_SEPARATOR) {
            $dir .= '/';
        }
        if (!is_dir($dir)) {
            return [];
        }
        $fileArr = [];
        foreach (scandir($dir, 1) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_file($dir . $item)) {
                $fileArr[] = $dir . $item;
            }
        }
        return $fileArr;
    }

    /**
     * 获取目录下所有子目录
     * getPaths
     * @param $dir
     * @return array
     * @author luffyzhao@vip.126.com
     */
    protected function getPaths($dir): array
    {
        if (substr($dir, -1) !== DIRECTORY_SEPARATOR) {
            $dir .= '/';
        }
        if (!is_dir($dir)) {
            return [];
        }
        $dirArr = [];
        foreach (scandir($dir, 1) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            if (is_dir($dir . $item)) {
                $dirArr[] = $dir . $item;
            }
        }
        return $dirArr;
    }
}
