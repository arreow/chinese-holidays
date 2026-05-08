<?php

namespace ChineseHolidays\Tests;

use ChineseHolidays\Exceptions\HolidayException;
use ChineseHolidays\HolidayChecker;
use PHPUnit\Framework\TestCase;

/**
 * 节假日检查器测试类
 */
class HolidayCheckerTest extends TestCase
{
    /**
     * @var HolidayChecker
     */
    protected $checker;

    /**
     * 设置测试环境
     */
    protected function setUp(): void
    {
        parent::setUp();
        // 假设我们使用真实的 data 目录进行测试
        $this->checker = new HolidayChecker();
    }

    /**
     * 测试法定节假日判断
     */
    public function testIsHoliday(): void
    {
        // 2024-02-10 是春节（法定节假日）
        $this->assertTrue($this->checker->isHoliday('2024-02-10'));
        // 2024-10-01 是国庆节（法定节假日）
        $this->assertTrue($this->checker->isHoliday('2024-10-01'));
        
        // 2024-02-18 是春节调休（不是休息日，是工作日）
        $this->assertFalse($this->checker->isHoliday('2024-02-18'));
        
        // 2024-01-08 是普通周一（工作日）
        $this->assertFalse($this->checker->isHoliday('2024-01-08'));
    }

    /**
     * 测试工作日判断
     */
    public function testIsWorkday(): void
    {
        // 2024-01-08 是普通周一
        $this->assertTrue($this->checker->isWorkday('2024-01-08'));
        
        // 2024-02-18 是春节调休（周日，但是工作日）
        $this->assertTrue($this->checker->isWorkday('2024-02-18'));
        
        // 2024-02-10 是春节（休息日）
        $this->assertFalse($this->checker->isWorkday('2024-02-10'));
        
        // 2024-01-13 是普通周六（非工作日）
        $this->assertFalse($this->checker->isWorkday('2024-01-13'));
    }

    /**
     * 测试日常周末判断
     */
    public function testIsWeekend(): void
    {
        // 2024-01-13 是普通周六
        $this->assertTrue($this->checker->isWeekend('2024-01-13'));
        
        // 2024-01-14 是普通周日
        $this->assertTrue($this->checker->isWeekend('2024-01-14'));
        
        // 2024-02-18 是春节调休（周日，但是工作日，不是日常周末）
        $this->assertFalse($this->checker->isWeekend('2024-02-18'));
        
        // 2024-01-08 是普通周一
        $this->assertFalse($this->checker->isWeekend('2024-01-08'));
    }

    /**
     * 测试获取节假日详细信息
     */
    public function testGetHolidayInfo(): void
    {
        $info = $this->checker->getHolidayInfo('2024-10-01');
        $this->assertIsArray($info);
        $this->assertEquals('国庆节', $info['name']);
        $this->assertTrue($info['isOffDay']);

        $info = $this->checker->getHolidayInfo('2024-10-12');
        $this->assertIsArray($info);
        $this->assertEquals('国庆节调休', $info['name']);
        $this->assertFalse($info['isOffDay']);

        // 普通日期应该返回 null
        $this->assertNull($this->checker->getHolidayInfo('2024-01-08'));
    }

    /**
     * 测试无效日期格式抛出异常
     */
    public function testInvalidDateFormatThrowsException(): void
    {
        $this->expectException(HolidayException::class);
        $this->checker->isHoliday('invalid-date');
    }

    /**
     * 测试缺少数据的年份抛出异常
     */
    public function testMissingYearDataThrowsException(): void
    {
        $this->expectException(HolidayException::class);
        $this->checker->isHoliday('2019-01-01');
    }
}
