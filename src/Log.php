<?php

declare (strict_types=1);

namespace Hcg\Log;

/**
 * 日志助手类
 *
 * @author hcg
 * @email 532508307@qq.com
 */
class Log
{
    /**
     * 写入日志
     * @param string|array $log 日志数据
     * @param string $log_dir 日志文件目录
     * @return array
     * @author hcg<532508307@qq.com>
     */
    public static function log($log, string $log_dir = '')
    {
        // 处理目录
        $log_dir .= date('Ym');
        !is_dir($log_dir) && mkdir($log_dir, 0755, true);

        // 处理日志数据
        if(is_array($log) || is_object($log)) $log = json_encode($log, JSON_UNESCAPED_UNICODE);

        // 定义日志文件
        $log_file = $log_dir.'/'.date('d').'.log';

        // 写入日志
        file_put_contents($log_file,$log.PHP_EOL, FILE_APPEND);
        
        return ['log_file' => $log_file];
    }
}
