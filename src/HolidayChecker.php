<?php

namespace ChineseHolidays;

use ChineseHolidays\Exceptions\HolidayException;
use DateTime;
use Exception;

/**
 * 中国法定节假日判断核心类
 */
class HolidayChecker
{
    /**
     * @var string 节假日数据目录路径
     */
    protected $dataDir;

    /**
     * @var array 已加载的年份数据缓存
     */
    protected $loadedData = [];

    /**
     * 构造函数
     *
     * @param string|null $dataDir 自定义数据目录路径，若为null则使用默认data目录
     */
    public function __construct(?string $dataDir = null)
    {
        $this->dataDir = $dataDir ?: dirname(__DIR__) . '/data';
    }

    /**
     * 判断给定日期是否为法定节假日（休息日）
     *
     * @param string $date 日期字符串，格式如 'Y-m-d'
     * @return bool 是否为法定节假日
     * @throws HolidayException 当日期格式不正确或缺少对应年份数据时抛出
     */
    public function isHoliday(string $date): bool
    {
        $dayInfo = $this->getHolidayInfo($date);
        
        // 如果在数据中且 isOffDay 为 true，则是法定节假日
        if ($dayInfo !== null && $dayInfo['isOffDay'] === true) {
            return true;
        }

        return false;
    }

    /**
     * 判断给定日期是否为工作日
     *
     * @param string $date 日期字符串，格式如 'Y-m-d'
     * @return bool 是否为工作日
     * @throws HolidayException 当日期格式不正确或缺少对应年份数据时抛出
     */
    public function isWorkday(string $date): bool
    {
        $dayInfo = $this->getHolidayInfo($date);
        
        // 如果在数据中，根据 isOffDay 判断（false 为调休工作日）
        if ($dayInfo !== null) {
            return !$dayInfo['isOffDay'];
        }

        // 不在数据中，判断是否为常规周一至周五
        $dayOfWeek = $this->getDayOfWeek($date);
        return $dayOfWeek >= 1 && $dayOfWeek <= 5;
    }

    /**
     * 判断给定日期是否为日常周末（且不是调休工作日）
     *
     * @param string $date 日期字符串，格式如 'Y-m-d'
     * @return bool 是否为日常周末
     * @throws HolidayException 当日期格式不正确或缺少对应年份数据时抛出
     */
    public function isWeekend(string $date): bool
    {
        $dayInfo = $this->getHolidayInfo($date);
        
        // 如果在数据中，说明是法定节假日或调休工作日，不能算是“日常”周末
        if ($dayInfo !== null) {
            return false;
        }

        // 不在数据中，判断是否为常规周六周日
        $dayOfWeek = $this->getDayOfWeek($date);
        return $dayOfWeek === 6 || $dayOfWeek === 7;
    }

    /**
     * 获取指定日期的节假日信息
     *
     * @param string $date 日期字符串，格式如 'Y-m-d'
     * @return array|null 节假日信息（含 name 和 isOffDay），如果不是特殊日期则返回 null
     * @throws HolidayException 当日期格式不正确或缺少对应年份数据时抛出
     */
    public function getHolidayInfo(string $date): ?array
    {
        $parsedDate = $this->parseDate($date);
        $year = $parsedDate->format('Y');
        $monthDay = $parsedDate->format('m-d');

        $yearData = $this->loadYearData($year);

        return $yearData['days'][$monthDay] ?? null;
    }

    /**
     * 获取给定日期是星期几
     *
     * @param string $date 日期字符串，格式如 'Y-m-d'
     * @return int 1 (周一) 到 7 (周日)
     * @throws HolidayException 当日期格式不正确时抛出
     */
    protected function getDayOfWeek(string $date): int
    {
        return (int) $this->parseDate($date)->format('N');
    }

    /**
     * 解析并验证日期字符串
     *
     * @param string $date 日期字符串
     * @return DateTime 解析后的 DateTime 对象
     * @throws HolidayException 当日期格式不正确时抛出
     */
    protected function parseDate(string $date): DateTime
    {
        try {
            $dateTime = new DateTime($date);
            return $dateTime;
        } catch (Exception $e) {
            throw new HolidayException("无效的日期格式: {$date}");
        }
    }

    /**
     * 加载指定年份的节假日数据
     *
     * @param string|int $year 年份
     * @return array 解析后的年份数据
     * @throws HolidayException 当缺少对应年份数据或解析失败时抛出
     */
    protected function loadYearData($year): array
    {
        $year = (string) $year;

        if (isset($this->loadedData[$year])) {
            return $this->loadedData[$year];
        }

        $filePath = $this->dataDir . '/' . $year . '.json';

        if (!file_exists($filePath)) {
            throw new HolidayException("缺少 {$year} 年的节假日数据，请更新数据包或提交 PR。");
        }

        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HolidayException("{$year} 年的数据文件格式错误 (无效的 JSON)。");
        }

        $this->loadedData[$year] = $data;

        return $data;
    }
}
