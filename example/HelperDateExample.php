<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Hcg\HelperDate\HelperDate;

// 时间戳转日期
echo HelperDate::timeToDate(time());