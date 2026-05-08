<?php

require __DIR__ . '/../vendor/autoload.php';

use ChineseHolidays\HolidayChecker;
use ChineseHolidays\Exceptions\HolidayException;

$checker = new HolidayChecker();

$datesToTest = [
    '2024-01-08', // 普通周一
    '2024-01-13', // 普通周六
    '2024-02-10', // 春节（休息）
    '2024-02-18', // 春节调休（上班）
    '2024-10-01', // 国庆节（休息）
    '2024-10-12', // 国庆节调休（上班）
];

echo "====== 中国法定节假日判断库测试示例 ======\n\n";

foreach ($datesToTest as $date) {
    try {
        echo "日期: {$date}\n";
        
        $isHoliday = $checker->isHoliday($date);
        $isWorkday = $checker->isWorkday($date);
        $isWeekend = $checker->isWeekend($date);
        $info = $checker->getHolidayInfo($date);

        echo " - 是否为法定节假日: " . ($isHoliday ? '✅ 是' : '❌ 否') . "\n";
        echo " - 是否为工作日: " . ($isWorkday ? '✅ 是' : '❌ 否') . "\n";
        echo " - 是否为日常周末: " . ($isWeekend ? '✅ 是' : '❌ 否') . "\n";
        
        if ($info) {
            echo " - 节假日/调休说明: " . $info['name'] . "\n";
        }
        
        echo str_repeat('-', 40) . "\n";
        
    } catch (HolidayException $e) {
        echo "发生错误: " . $e->getMessage() . "\n";
    }
}
