<?php

namespace single;

/**
 * 日志类
 * 使用方法：Log::fatal('error msg');
 * 保存路径为 App/Log，按天存放
 * fatal和warning会记录在.log.wf文件中
 */
class Log
{
    /**
     * 打日志，支持SAE环境(DEPRECATE)
     * @param string $msg 日志内容
     * @param string $level 日志等级
     * @param bool $wf 是否为错误日志
     */
    public static function write($msg, $level = 'DEBUG', $wf = false)
    {
        $msg = date('[ Y-m-d H:i:s ]') . "[{$level}]" . $msg . "\r\n";
        $logPath = Common::C('APP_FULL_PATH') . '../runtime/log/';

        if (!file_exists($logPath)) {
            mkdir($logPath, 0755, true);
        }

        $filename = date('Ymd') . '.log';

        if ($wf) {
            $filename .= '.wf';
        }
        file_put_contents($logPath . $filename, $msg, FILE_APPEND);
    }

    /**
     * 打印fatal日志
     * @param string $msg 日志信息
     */
    public static function fatal($msg)
    {
        self::write($msg, 'FATAL', true);
    }

    /**
     * 打印warning日志
     * @param string $msg 日志信息
     */
    public static function warn($msg)
    {
        self::write($msg, 'WARN', true);
    }

    /**
     * 打印notice日志
     * @param string $msg 日志信息
     */
    public static function notice($msg)
    {
        self::write($msg, 'NOTICE');
    }

    /**
     * 打印debug日志
     * @param string $msg 日志信息
     */
    public static function debug($msg)
    {
        self::write($msg, 'DEBUG');
    }

    /**
     * 打印sql日志
     * @param string $msg 日志信息
     */
    public static function sql($msg)
    {
        self::write($msg, 'SQL');
    }
}
