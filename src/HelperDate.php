<?php

declare (strict_types=1);

namespace Hcg\HelperDate;

/**
 * 时间助手类
 *
 * @author hcg
 * @email 532508307@qq.com
 */
class HelperDate
{
    /**
     * 时间戳转日期
     * @param int $time 时间戳
     * @param string $format 格式
     * @return false|string
     * @author hcg<532508307@qq.com>
     */
    public static function timeToDate(int $time, string $format = 'Y-m-d H:i:s')
    {
        return empty($time) ? '' : date($format, $time);
    }
}
