<?php

namespace single;

use single\Db;
use single\Log;


class Common
{
    /**
     * 获取和设置配置参数 支持批量定义
     * 如果$key是关联型数组，则会按K-V的形式写入配置
     * 如果$key是数字索引数组，则返回对应的配置数组
     * @param string|array $key 配置变量
     * @param array|null $value 配置值
     * @return array|null
     */
    public static function C($key, $value = null)
    {
        static $_config = array();
        $args = func_num_args();
        if ($args == 1) {
            if (is_string($key)) {  //如果传入的key是字符串
                return isset($_config[$key]) ? $_config[$key] : null;
            }
            if (is_array($key)) {
                if (array_keys($key) !== range(0, count($key) - 1)) {  //如果传入的key是关联数组
                    $_config = array_merge($_config, $key);
                } else {
                    $ret = array();
                    foreach ($key as $k) {
                        $ret[$k] = isset($_config[$k]) ? $_config[$k] : null;
                    }
                    return $ret;
                }
            }
        } else {
            if (is_string($key)) {
                $_config[$key] = $value;
            } else {
                static::halt('传入参数不正确');
            }
        }
        return null;
    }

    /**
     * 调用Widget
     * @param string $name widget名
     * @param array $data 传递给widget的变量列表，key为变量名，value为变量值
     * @return void
     */
    public static function W($name, $data = array())
    {
        $fullName = $name . 'Widget';
        if (!class_exists($fullName)) {
            static::halt('Widget ' . $name . '不存在');
        }
        $widget = new $fullName();
        $widget->invoke($data);
    }

    /**
     * 终止程序运行
     * @param string $str 终止原因
     * @param bool $display 是否显示调用栈，默认不显示
     * @return void
     */
    public static function halt($str, $display = false)
    {
        Log::fatal($str . ' debug_backtrace:' . var_export(debug_backtrace(), true));
        header("Content-Type:text/html; charset=utf-8");
        if ($display) {
            echo "<pre>";
            debug_print_backtrace();
            echo "</pre>";
        }
        echo $str;
        exit;
    }

    /**
     * 获取数据库实例
     * @return Db
     */
    public static function M()
    {
        $dbConf = static::C(array('DB_HOST', 'DB_PORT', 'DB_USER', 'DB_PWD', 'DB_NAME', 'DB_CHARSET'));
        return Db::getInstance($dbConf);
    }

    /**
     * 如果文件存在就include进来
     * @param string $path 文件路径
     * @return void
     */
    public static function includeIfExist($path)
    {
        if (file_exists($path)) {
            include $path;
        }
    }
}