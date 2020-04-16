<?php

namespace Single\Web;

/**
 * 控制器类
 */
class Controller
{
    /**
     * 视图实例
     * @var View
     */
    private $_view;

    /**
     * 构造函数，初始化视图实例，调用hook
     */
    public function __construct()
    {
        $this->_view = new View();
        $this->_init();
    }

    /**
     * 前置hook
     */
    protected function _init()
    {
    }
    /**
     * 渲染模板并输出
     * @param null|string $tpl 模板文件路径
     * 参数为相对于App/View/文件的相对路径，不包含后缀名，例如index/index
     * 如果参数为空，则默认使用$controller/$action.php
     * 如果参数不包含"/"，则默认使用$controller/$tpl
     * @return void
     */
    protected function display($tpl = '')
    {
        if ($tpl === '') {
            $trace = debug_backtrace();
            $controller = substr($trace[1]['class'], 0, -10);
            $action = substr($trace[1]['function'], 0, -6);
            $tpl = strtolower($controller) . '/' . $action;
        } elseif (strpos($tpl, '/') === false) {
            $trace = debug_backtrace();
            $controller = substr($trace[1]['class'], 0, -10);
            $tpl = strtolower($controller) . '/' . $tpl;
        }
        $this->_view->display($tpl);
    }
    /**
     * 为视图引擎设置一个模板变量
     * @param string $name 要在模板中使用的变量名
     * @param mixed $value 模板中该变量名对应的值
     * @return void
     */
    protected function assign($name, $value)
    {
        $this->_view->assign($name, $value);
    }
    /**
     * 将数据用json格式输出至浏览器，并停止执行代码
     * @param array $data 要输出的数据
     */
    protected function ajaxReturn($data)
    {
        echo json_encode($data);
        exit;
    }
    /**
     * 重定向至指定url
     * @param string $url 要跳转的url
     * @param void
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}
