<?php

namespace Single;

/**
 * 总控类
 */
class Core
{
    /**
     * 控制器
     * @var string
     */
    private $c;
    /**
     * Action
     * @var string
     */
    private $a;
    /**
     * 单例
     * @var Core
     */
    private static $_instance;

    /**
     * 构造函数，初始化配置
     * @param array $conf
     */
    private function __construct($conf)
    {
        C($conf);
    }
    private function __clone()
    {
    }

    /**
     * 获取单例
     * @param array $conf
     * @return Core
     */
    public static function getInstance($conf)
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($conf);
        }
        return self::$_instance;
    }
    /**
     * 运行应用实例
     * @access public
     * @return void
     */
    public function run()
    {
        if (C('USE_SESSION') == true) {
            session_start();
        }
        C('APP_FULL_PATH', getcwd() . '/' . C('APP_PATH') . '/');
        includeIfExist(C('APP_FULL_PATH') . '/common.php');

        $pathMod = C('PATH_MOD');
        $pathMod = empty($pathMod) ? 'NORMAL' : $pathMod;
        
        if (strcmp(strtoupper($pathMod), 'NORMAL') === 0 || !isset($_SERVER['REQUEST_URI'])) {
            $this->c = isset($_GET['c']) ? $_GET['c'] : 'site';
            $this->a = isset($_GET['a']) ? $_GET['a'] : 'index';
        } else {
            $pathInfo = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
            $pathInfoArr = explode('/', trim($pathInfo, '/'));
            
            if (isset($pathInfoArr[0]) && $pathInfoArr[0] !== '') {
                $this->c = $pathInfoArr[0];
            } else {
                $this->c = 'site';
            }

            if (isset($pathInfoArr[1])) {
                $this->a = $pathInfoArr[1];
            } else {
                $this->a = 'index';
            }
        }

        $controllerClass = "app\\controllers\\" . ucfirst($this->c) . 'Controller';

        if (!class_exists($controllerClass)) {
            halt('控制器' . $this->c . '不存在');
        }

        $controller = new $controllerClass();

        $action = "{$this->a}Action";
        if (!method_exists($controller, $action)) {
            halt('方法' . $this->a . '不存在');
        }

        call_user_func([$controller, $action]);
    }

    /**
     * 自动加载函数
     * @param string $class 类名
     */
    public static function autoload($class)
    {
        if (substr($class, -10) == 'Controller') {
            includeIfExist(C('APP_FULL_PATH') . '/controllers/' . $class . '.php');
        } elseif (substr($class, -6) == 'Widget') {
            includeIfExist(C('APP_FULL_PATH') . '/widgets/' . $class . '.php');
        } else {
            includeIfExist(C('APP_FULL_PATH') . '/libs/' . $class . '.php');
        }
    }
}
