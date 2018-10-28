<?php
namespace Tests\EtienneQ\Stardate;

use EtienneQ\Stardate\Calculator;
use EtienneQ\Stardate\InvalidDateException;
use EtienneQ\Stardate\InvalidStardateException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \EtienneQ\Stardate\Calculator
 */
class CalculatorTest extends TestCase
{
    /**
     * @var Calculator
     */
    protected static $calculator;
    
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        
        self::$calculator = new Calculator();
    }
    
    public function testToStardateWithDateTooLowShouldThrowException()
    {
        $this->expectException(InvalidDateException::class);
        $this->expectExceptionMessage('Year of given date must be at least 2323.');
        
        $invalidDatetime = new \DateTime('2322-12-31 23:59:59');
        
        self::$calculator->toStardate($invalidDatetime);
    }
    
    /**
     * @dataProvider dataProviderDates
     */
    public function testToStardateWithValidDateShouldReturnStardate(string $date, float $expectedStardate):void
    {
        $dateTime = new \DateTime($date);
        
        $stardate = self::$calculator->toStardate($dateTime, 5);
        
        $this->assertInternalType('float', $stardate);
        $this->assertEquals($expectedStardate, $stardate);
    }
    
    public function dataProviderDates():array
    {
        return [
            'beginning of TNG-era stardates' => ['2323-01-01', 0],
            'one year after beginning' => ['2333-01-01', 10000],
            'start of TNG season 1' => ['2364-01-01', 41000],
            'some random day' => ['2364-01-02 10:56:02', 41003.97699],
            'end of TNG season 1' => ['2364-12-31 23:59:59', 41999.99997],
            'start of TNG season 2' => ['2365-01-01', 42000],
            'start of VOY season 1' => ['2371-01-01', 48000],
            'beginning of feb 83' => ['2383-02-01', 60084.93151],
            'end of feb 83' => ['2383-02-28 23:59:59', 60161.64380],
            'max date' => ['9999-12-31 23:59:59', 7676999.99997],
        ];
    }
    
    public function testToGregorianDateWithStardateTooLowShouldThrowException()
    {
        $this->expectException(InvalidStardateException::class);
        $this->expectExceptionMessage('Stardate must be between 0 and 7676999.99997.');
        
        self::$calculator->toGregorianDate(-0.1);
    }
    
    public function testToGregorianDateWithStardateTooHighShouldThrowException()
    {
        $this->expectException(InvalidStardateException::class);
        $this->expectExceptionMessage('Stardate must be between 0 and 7676999.99997.');
        
        self::$calculator->toGregorianDate(7676999.999981);
    }

    /**
     * @dataProvider dataProviderStardates
     */
    public function testToGregorianDateWithValidStardateShouldReturnDate(float $stardate, string $expectedDate)
    {
        $date = self::$calculator->toGregorianDate($stardate);
        
        $this->assertInstanceOf(\DateTime::class, $date);
        $this->assertEquals($expectedDate, $date->format('Y-m-d H:i:s'));
    }
    
    public function dataProviderStardates():array
    {
        return [
            'beginning of TNG-era stardates' => [0, '2323-01-01 00:00:00'],
            'one year after beginning' => [10000, '2333-01-01 00:00:00'],
            'start of TNG season 1' => [41000, '2364-01-01 00:00:00'],
            'some random stardate' => [41003.97699, '2364-01-02 10:56:02'],
            'end of TNG season 1' => [41999.99997, '2364-12-31 23:59:59'],
            'start of TNG season 2' => [42000, '2365-01-01 00:00:00'],
            'start of VOY season 1' => [48000, '2371-01-01 00:00:00'],
            'beginning of feb 83' => [60084.93151, '2383-02-01 00:00:00'],
            'end of feb 83' => [60161.64380, '2383-02-28 23:59:59'],
            'max date' => [7676999.99997, '9999-12-31 23:59:59'],
        ];
    }
}

