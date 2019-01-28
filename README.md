BugFree
=========

[![Latest Stable Version](https://poser.pugx.org/jyl/bug-free-php/v/stable)](https://packagist.org/packages/jyl/bug-free-php)
[![Total Downloads](https://poser.pugx.org/jyl/bug-free-php/downloads)](https://packagist.org/packages/jyl/bug-free-php)
[![License](https://poser.pugx.org/jyl/bug-free-php/license)](https://packagist.org/packages/jyl/bug-free-php)

佛祖保佑，永无bug！：） just for fun !
自动在PHP代码头部加上神注释，默认支持utf8和GBK编码的php文件。

## 安装
    composer create-project jyl/bug-free-php path 

## test
    ./vendor/bin/phpunit
    
## example shell
    chmod +x bugFree.sh
    ./bugFree.sh

## example php-cli
    php bugFree path/filePath    // 单文件、目录
    php bugFree path/filePath path/filePath    // 多文件、目录
    php bugFree path -del    // 删除佛祖保佑
    
## example
    use BugFree\Utils as Utils;
    
    Utils\AddNote::setDel(false);    // 设置属性 true-佛祖保佑 false-删除佛祖保佑
    Utils\AddNote::bugfree([$file]);    // $file 文件路径或目录  

## ps
更多用法查看tests示例

## TODO

- [x] 单文件
- [x] 多文件
- [x] 目录

## 感谢
本扩展包根据 https://github.com/leon0204/bugFreePhp 修改 。
