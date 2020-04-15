<?php

namespace single;

/**
 * ExtException类，记录额外的异常信息
 */
class Exception extends \Exception
{
    /**
     * @var array
     */
    protected $extra;

    /**
     * @param string $message
     * @param array $extra
     * @param int $code
     * @param null $previous
     */
    public function __construct($message = "", $extra = array(), $code = 0, $previous = null)
    {
        $this->extra = $extra;
        parent::__construct($message, $code, $previous);
    }

    /**
     * 获取额外的异常信息
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
