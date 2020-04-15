<?php

namespace single;

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
     * @var SinglePHP
     */
    private static $_instance;

    /**
     * 构造函数，初始化配置
     * @param array $conf
     */
    private function __construct($conf)
    {
        Common::C($conf);
    }
    private function __clone()
    {
    }

    /**
     * 获取单例
     * @param array $conf
     * @return SinglePHP
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
        if (Common::C('USE_SESSION') == true) {
            session_start();
        }
        Common::C('APP_FULL_PATH', getcwd() . '/' . Common::C('APP_PATH') . '/');
        Common::includeIfExist(Common::C('APP_FULL_PATH') . '/common.php');
        $pathMod = Common::C('PATH_MOD');
        $pathMod = empty($pathMod) ? 'NORMAL' : $pathMod;
        spl_autoload_register(array('SinglePHP', 'autoload'));
        if (strcmp(strtoupper($pathMod), 'NORMAL') === 0 || !isset($_SERVER['PATH_INFO'])) {
            $this->c = isset($_GET['c']) ? $_GET['c'] : 'Index';
            $this->a = isset($_GET['a']) ? $_GET['a'] : 'Index';
        } else {
            $pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
            $pathInfoArr = explode('/', trim($pathInfo, '/'));
            if (isset($pathInfoArr[0]) && $pathInfoArr[0] !== '') {
                $this->c = $pathInfoArr[0];
            } else {
                $this->c = 'Index';
            }
            if (isset($pathInfoArr[1])) {
                $this->a = $pathInfoArr[1];
            } else {
                $this->a = 'Index';
            }
        }
        if (!class_exists($this->c . 'Controller')) {
            Common::halt('控制器' . $this->c . '不存在');
        }
        $controllerClass = $this->c . 'Controller';
        $controller = new $controllerClass();
        if (!method_exists($controller, $this->a . 'Action')) {
            Common::halt('方法' . $this->a . '不存在');
        }
        call_user_func(array($controller, $this->a . 'Action'));
    }

    /**
     * 自动加载函数
     * @param string $class 类名
     */
    public static function autoload($class)
    {
        if (substr($class, -10) == 'Controller') {
            Common::includeIfExist(Common::C('APP_FULL_PATH') . '/Controller/' . $class . '.class.php');
        } elseif (substr($class, -6) == 'Widget') {
            Common::includeIfExist(Common::C('APP_FULL_PATH') . '/Widget/' . $class . '.class.php');
        } else {
            Common::includeIfExist(Common::C('APP_FULL_PATH') . '/Lib/' . $class . '.class.php');
        }
    }
}
