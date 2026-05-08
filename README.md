# Chinese Holidays Checker

[![Build Status](https://github.com/arreow/chinese-holidays/actions/workflows/tests.yml/badge.svg)](https://github.com/arreow/chinese-holidays/actions)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

一个专业的 PHP 库，用于准确判断中国法定节假日、工作日及日常周末。

本库包含 2020-2026 年（及后续更新）的中国大陆法定节假日及调休数据，使用 JSON 文件管理每年数据，易于维护和扩展。

## 功能特性

- ✅ 判断任意日期是否为 **法定节假日**
- ✅ 判断任意日期是否为 **工作日**（支持周末调休上班的识别）
- ✅ 判断任意日期是否为 **日常周末**（排除法定节假日及周末调休）
- ✅ 获取节假日详细信息（节假日名称、是否为休息日等）
- ✅ 高性能，按需加载年份数据，自动缓存
- ✅ PSR 标准，100% 单元测试覆盖
- ✅ 独立年份的 JSON 数据文件，易于开源协作更新

## 环境要求

- PHP >= 7.4
- ext-json

## 安装

使用 Composer 安装：

```bash
composer require chinese-holidays/holiday-checker
```

## 快速使用

```php
use ChineseHolidays\HolidayChecker;

$checker = new HolidayChecker();

// 1. 判断是否为法定节假日（休息日）
$checker->isHoliday('2024-10-01'); // true (国庆节)
$checker->isHoliday('2024-10-12'); // false (国庆调休上班)

// 2. 判断是否为工作日
$checker->isWorkday('2024-10-08'); // true (周二正常上班)
$checker->isWorkday('2024-10-12'); // true (周六调休上班)
$checker->isWorkday('2024-10-01'); // false (国庆节休息)

// 3. 判断是否为日常周末
$checker->isWeekend('2024-01-13'); // true (普通周六)
$checker->isWeekend('2024-10-12'); // false (周六调休上班，非日常周末)

// 4. 获取详细信息
$info = $checker->getHolidayInfo('2024-10-01');
/*
[
    'name' => '国庆节',
    'isOffDay' => true
]
*/
```

## 数据更新

所有节假日数据存放在 `data` 目录下的 JSON 文件中（如 `2024.json`）。每年国务院发布新的放假安排时，只需提交对应年份的 JSON 文件 PR 即可。

JSON 格式示例：

```json
{
    "year": 2024,
    "days": {
        "10-01": {"name": "国庆节", "isOffDay": true},
        "10-12": {"name": "国庆节调休", "isOffDay": false}
    }
}
```

## 贡献指南

请阅读 [CONTRIBUTING.md](CONTRIBUTING.md) 了解如何参与贡献。

## 许可证

本项目采用 MIT 许可证开源，详情请见 [LICENSE](LICENSE) 文件。
