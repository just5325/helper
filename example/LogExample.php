<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Hcg\Log\Log;

$log_data = '日志'.time();
$log_data = ['a'=>'日志'.time()];

// 日志文件目录
$log_dir = './log/';

// 写入日志
echo Log::log($log_data, $log_dir);