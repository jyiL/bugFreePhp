<?php

declare(strict_types = 1);

namespace BugFree\Utils;

/**
 * Class AddNote
 * @package BugFree\Utils
 */

class AddNote
{
    static private $del = false;

    static private $fileType = 'UTF-8';

    static private $targetCharset = 'UTF-8';

    /**
     * 用佛祖保佑文件
     *
     * @param array $filepath 添加注释文件路径 支持文件目录，单文件，多文件(数组)
     * @param boolean $prompt 是否开启提示
     */
    static public function bugfree($filepath, $prompt = true)
    {
        if ($prompt) self::printfString('开始保佑');

        if ($prompt) self::printfString(self::getFoZuTxt(), false);

        try {
            // 判断单文件还是多文件
            if (count($filepath) > 1) {
                // 多文件
                self::manyFiles($filepath);
            } else {
                // 单文件
                self::dealFiles(array_shift($filepath));
            }

        } catch (\Exception $e) {
            printf($e->getMessage().PHP_EOL);
            printf($e->getLine().PHP_EOL);
            exit;
        }

        if ($prompt) self::printfString('保佑完成');
    }

    /**
     * 多文件佛祖保佑
     *
     * @param array $fileArr
     */
    static private function manyFiles($fileArr)
    {
        array_map(function($val) {
            self::dealFiles($val);
        }, $fileArr);
    }

    /**
     *  处理文件/目录
     *
     * @param mixed $fileArr
     */
    static private function dealFiles($fileArr)
    {
        if (is_file($fileArr)) {
            // 文件
            self::foZuBaoYou($fileArr);
        } else {
            // 目录
            self::dirFoZuBaoYou($fileArr);
        }
    }

    /**
     * 佛祖保佑
     *
     * @param string $filepath
     */
    static private function dirFoZuBaoYou($dirPath)
    {
        $handle = opendir($dirPath);

        while( ($file = readdir($handle)) !== false )
        {
            if( $file == '.' || $file == '..' )
                continue;

            $file = $dirPath.DIRECTORY_SEPARATOR.$file;
            if (is_file($file) && substr($file,-4) === '.php') {
                // 文件
                self::foZuBaoYou($file);
            } else if (is_dir($file)) {
                //递归查询
                self::dirFoZuBaoYou($file);
            }
        }

        closedir($handle); //关闭目录
    }

    /**
     * 佛祖保佑
     *
     * @param string $filepath
     */
    static private function foZuBaoYou($filePath)
    {
        // 读取待保佑文件
        $originStr = file_get_contents($filePath);

        $foZuTxt = self::getFoZuTxt();

        $newAdd = $originStr;

        switch (self::$del) {
            case false:
                if (!self::checkFoZu($originStr)) {
                    $originArr = explode('php',$originStr);
                    $originArr[1] = PHP_EOL.$foZuTxt.$originArr[1];
                    $newAdd = implode('php',$originArr);
                }
                break;
            case true:
                if (self::checkFoZu($originStr))
                    $newAdd = str_replace($foZuTxt.PHP_EOL, '', $originStr);
                break;
            default:
                $newAdd = $originStr;
                break;
        }

        file_put_contents(self::characet($filePath), $newAdd);
    }

    /**
     * 转换字符集编码
     * 
     * @param $data
     * 
     * @return string
     */
    static private function characet($data)
    {

        if (!empty($data)) {
            if (strcasecmp(self::$fileType, self::$targetCharset) != 0) {
                $data = mb_convert_encoding($data, self::$targetCharset, self::$fileType);
                //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }

        return $data;
    }

    /**
     * 检测是否存在佛祖保护
     *
     * @param string $txt
     *
     * @return boolean
     */
    static private function checkFoZu($txt)
    {
        return substr_count($txt,'佛');
    }

    /**
     * 获取佛祖文本
     *
     * @return string
     */
    static private function getFoZuTxt()
    {
        $_fozuFilePath = '';
        switch (strtoupper(self::$targetCharset)) {
            case 'UTF-8':
                $_fozuFilePath = dirname(__DIR__).'/Configs/fozu_utf8.txt';
                break;
            case 'GBK':
                $_fozuFilePath = dirname(__DIR__).'/Configs/fozu_gbk.txt';
                break;
            default:    
                $_fozuFilePath = dirname(__DIR__).'/Configs/fozu_utf8.txt';
                break;
        }

        return file_get_contents($_fozuFilePath);
    }

    /**
     * 打印文字
     *
     * @param string $text
     * @param boolean $flag
     */
    static private function printfString($text, $flag = true)
    {
        if ($flag)
            printf("--------------------{$text}--------------------".PHP_EOL);
        else
            printf("{$text}".PHP_EOL);
    }

    /**
     * 设置属性
     *
     * @param boolean $del
     */
    static public function setDel(bool $del)
    {
        self::$del = $del;
    }

    /**
     * 设置文本编码属性
     *
     * @param string $targetCharset
     */
    static public function setTargetCharset(string $targetCharset)
    {
        self::$targetCharset = $targetCharset;
    }
}