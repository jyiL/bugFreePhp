<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/1/24
 * Time: 11:04 AM
 */

declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use BugFree\Utils as Utils;

class BugFreeTest extends TestCase
{
    /**
     * 单文件
     */
    public function testSingleFile()
    {
        $file = dirname(__FILE__).'/demo1/demo1.php';

        Utils\AddNote::setDel(false);

        Utils\AddNote::bugfree([$file], false);

        $this->assertEquals(substr_count(file_get_contents($file),'佛'), 1);

        Utils\AddNote::setDel(true);

        Utils\AddNote::bugfree([$file], false);

        $this->assertEquals(substr_count(file_get_contents($file),'佛'), 0);
    }

    /**
     * 多文件
     *
     * @depends testSingleFile
     */
    public function testMultipleFiles()
    {
        $fileArr[] = dirname(__FILE__).'/demo1/demo1.php';
        $fileArr[] = dirname(__FILE__).'/demo1/demo2.php';

        Utils\AddNote::setDel(false);

        Utils\AddNote::bugfree($fileArr, false);

        array_map(function($val) {
            $this->assertEquals(substr_count(file_get_contents($val),'佛'), 1);
        }, $fileArr);

        Utils\AddNote::setDel(true);

        Utils\AddNote::bugfree($fileArr, false);

        array_map(function($val) {
            $this->assertEquals(substr_count(file_get_contents($val),'佛'), 0);
        }, $fileArr);
    }

    /**
     * 单目录
     *
     * @depends testMultipleFiles
     */
    public function testSingleDirectory()
    {
        $fileArr = dirname(__FILE__).'/demo1';

        Utils\AddNote::setDel(false);

        Utils\AddNote::bugfree([$fileArr], false);

        $this->assertDir($fileArr);

        Utils\AddNote::setDel(true);

        Utils\AddNote::bugfree([$fileArr], false);

        $this->assertDir($fileArr, 0);
    }

    /**
     * 多目录
     *
     * @depends testSingleDirectory
     */
    public function testMultipleDirectories()
    {
        $fileArr[] = dirname(__FILE__).'/demo1';
        $fileArr[] = dirname(__FILE__).'/demo2';

        Utils\AddNote::setDel(false);

        Utils\AddNote::bugfree($fileArr, false);

        array_map(function($val) {
            $this->assertDir($val);
        }, $fileArr);

        Utils\AddNote::setDel(true);

        Utils\AddNote::bugfree($fileArr, false);

        array_map(function($val) {
            $this->assertDir($val, 0);
        }, $fileArr);
    }

    /**
     * 文件目录
     *
     * @depends testMultipleDirectories
     */
    public function testFilesDirectories()
    {
        $fileArr[] = dirname(__FILE__).'/demo1/demo1.php';
        $fileArr[] = dirname(__FILE__).'/demo2';

        Utils\AddNote::setDel(false);

        Utils\AddNote::bugfree($fileArr, false);

        array_map(function($val) {
            if (is_file($val)) {
                $this->assertEquals(substr_count(file_get_contents($val),'佛'), 1);
            } else if (is_dir($val)) {
                $this->assertDir($val);
            }
        }, $fileArr);

        Utils\AddNote::setDel(true);

        Utils\AddNote::bugfree($fileArr, false);

        array_map(function($val) {
            if (is_file($val)) {
                $this->assertEquals(substr_count(file_get_contents($val),'佛'), 0);
            } else if (is_dir($val)) {
                $this->assertDir($val, 0);
            }
        }, $fileArr);
    }

    /**
     * 断言目录
     *
     * @param string $dir
     * @param integer $actual
     */
    private function assertDir($dir, $actual = 1)
    {
        $handle = opendir($dir);

        while( ($file = readdir($handle)) !== false )
        {
            if( $file == '.' || $file == '..' )
                continue;

            $file = $dir.DIRECTORY_SEPARATOR.$file;
            if (is_file($file) && substr($file,-4) === '.php') {
                // 文件
                $this->assertEquals(substr_count(file_get_contents($file),'佛'), $actual);
            } else if (is_dir($file)) {
                //递归查询
                $this->assertDir($file);
            }
        }

        closedir($handle); //关闭目录
    }
}