#!/usr/bin/env php
<?php

declare(strict_types = 1);

require 'vendor/autoload.php';

use BugFree\Utils as Utils;

printf(file_get_contents('./src/Configs/fozuHeard.txt') . PHP_EOL);

fwrite(STDOUT, "请输入文件类型[utf-8/gbk](Default utf-8 press Enter): ");

$fileType = trim(fgets(STDIN));

if (!checkFileType($fileType)) {
    do {
        if (!checkFileType($fileType)) fwrite(STDERR, "input error! 请输入正确的文件类型 " . PHP_EOL . "请输入文件类型[utf-8/gbk](Default utf-8 press Enter): ");
        $fileType = trim(fgets(STDIN));
    } while(!checkFileType($fileType));
}

fwrite(STDOUT, "请输入是否佛祖保佑[y/n]: ");

$del = trim(fgets(STDIN));

if (!checkDel($del)) {
    do {
        if (!checkDel($del)) fwrite(STDERR, "input error! Please only input string y/n " . PHP_EOL . "请输入是否佛祖保佑[y/n]: ");
        $del = trim(fgets(STDIN));
    } while(!checkDel($del));
}

$del = in_array($del, ['y', 'Y', 'YES', 'yes']) ? false : true;

fwrite(STDOUT, "请选择佛祖保佑的文件类型: ". PHP_EOL . 
"        1、单文件/目录"  . PHP_EOL . 
"        2、多文件/目录" . PHP_EOL . 
"请输入数字(Default 1 press Enter): ");

$number = trim(fgets(STDIN));

if (!$number = checkNumber($number)) {
    do {
        if (!$number) {
            fwrite(STDERR, "input error! Please only input number 1~2 " . PHP_EOL . 
        "请选择佛祖保佑的文件类型: ". PHP_EOL . 
    "        1、单文件/目录"  . PHP_EOL . 
    "        2、多文件/目录" . PHP_EOL . 
    "请输入数字(Default 1 press Enter): ");
        }
        $number = trim(fgets(STDIN));
    } while(!$number = checkNumber($number));
}

$argvArr = [];

switch ($number) {
    case "1":    // 单文件/目录
        fwrite(STDOUT, "请输入文件或目录: ");
        $filePath = trim(fgets(STDIN));

        if (!checkFileEmpty($filePath)) {
            do {
                if (!checkFileEmpty($filePath)) fwrite(STDERR, "input error! 请输入目录地址" . PHP_EOL . 
            "请输入文件或目录: ");
                $filePath = trim(fgets(STDIN));
            } while(!checkFileEmpty($filePath));
        }

        if (!checkFilePath($filePath)) {
            do {
                if (!checkFilePath($filePath)) fwrite(STDERR, "input error! {$filePath}--目录不存在" . PHP_EOL . 
            "请输入文件或目录: ");
                $filePath = trim(fgets(STDIN));
            } while(!checkFilePath($filePath));
        }

        $argvArr = [$filePath];
        break;
    case "2":    // 多文件/目录
        fwrite(STDOUT, "请输入文件或目录以逗号分割,例[demo1.php,demo2.php,/demo]: ");
        $filePath = trim(fgets(STDIN));

        if (!checkFileEmpty($filePath)) {
            do {
                if (!checkFileEmpty($filePath)) fwrite(STDERR, "input error! 目录不能为空,请输入目录地址" . PHP_EOL . 
                "请输入文件或目录以逗号分割,例[demo1.php,demo2.php,/demo]: ");
                $filePath = trim(fgets(STDIN));
            } while(!checkFileEmpty($filePath));
        }

        $argvArr = explode(',', $filePath);

        foreach($argvArr as $key => $val) {
            if (!checkFilePath($val)) {
               unset($argvArr[$key]);
            }
        }
        break;
    default:
        break;    
}

if ($argvArr) {
    Utils\AddNote::setDel($del);
    Utils\AddNote::setTargetCharset($fileType);
    $res = Utils\AddNote::bugfree($argvArr, false);
}

fwrite(STDOUT, PHP_EOL . "保佑成功!!! ");

/** 
 * 检测数字
 * 
 * @param string $number
 * 
 * @return mixed
 */
function checkNumber($number)
{
    if (empty($number)) return "1";

    if (!in_array($number, ["1", "2"])) return false;

    return $number;
}

/** 
 * 检测数字
 * 
 * @param string $fileType
 * 
 * @return mixed
 */
function checkFileType($fileType)
{
    if (empty($fileType)) return "utf-8";

    if (!in_array($fileType, ["utf-8", "gbk"])) return false;

    return $fileType;
}

/** 
 * 检测是否保佑
 * 
 * @param string $del
 * 
 * @return boolean
 */
function checkDel($del)
{
    if (!in_array($del, ["y", "n", "Y", "N", "yes", "no", "YES", "NO"])) return false;

    return true;
}

/**
 * 检测目录参数是否存在
 *
 * @param string $filePath
 * 
 * @return boolean
 */
function checkFileEmpty($filePath)
{
    if (empty(filePath)) return false;

    return true;
}

/**
 * 检测目录路径是否存在
 *
 * @param string $filePath
 * 
 * @return boolean
 */
function checkFilePath($filePath)
{
    if (!file_exists($filePath)) return false;

    return true;
}

?> 
