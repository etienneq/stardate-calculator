<?php
namespace EtienneQ\Stardate;

/**
 * At the moment this class calculates TNG-era stardates only.
 */
class Calculator
{
    public const MIN_YEAR = 2323;
    
    public const MIN_STARDATE = 0;
    public const MAX_STARDATE = 7676999.99997;
    
    protected const UNITS_PER_YEAR = 1000;
    
    /**
     * @var \DateTimeZone
     */
    protected $timezone;
    
    public function __construct()
    {
        $this->timezone = new \DateTimeZone('GMT');
    }
    
    public function toStardate(\DateTimeInterface $dateTime, int $precision = 2):float
    {
        // "Convert" to GMT because internally we always use GMT.
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $dateTime->format('Y-m-d H:i:s'), $this->timezone);
        
        if ($dateTime->format('Y') < self::MIN_YEAR) {
            throw new InvalidDateException(sprintf('Year of given date must be at least %d.', self::MIN_YEAR));
        };
        
        $firstDayOfYear = new \DateTime($dateTime->format('Y-01-01'), $this->timezone);

        $secondsSoFar = $dateTime->format('U') - $firstDayOfYear->format('U');

        $fractionOfCurrentYear = $secondsSoFar / $this->getSecondsPerYear($dateTime);

        $currentYear = $dateTime->format('Y');
        $fullYearsSinceEpoch = $currentYear - self::MIN_YEAR;
        $yearsSinceEpoch = $fullYearsSinceEpoch + $fractionOfCurrentYear;
      
        $stardate = $yearsSinceEpoch * self::UNITS_PER_YEAR;
        return round($stardate, $precision);
    }
    
    public function toGregorianDate(float $stardate):\DateTime
    {
        if ($stardate < self::MIN_STARDATE || $stardate > self::MAX_STARDATE) {
            throw new InvalidStardateException(sprintf('Stardate must be between %d and %.5f.', self::MIN_STARDATE, self::MAX_STARDATE));
        };
        
        $yearsSinceEpoch = $stardate / self::UNITS_PER_YEAR;
        $fullYearsSinceEpoch = floor($yearsSinceEpoch);
        
        $fractionOfCurrentYear = $yearsSinceEpoch - $fullYearsSinceEpoch;

        $currentYear = $fullYearsSinceEpoch + self::MIN_YEAR;
        $firstDayOfYear = new \DateTime("{$currentYear}-01-01", $this->timezone);

        $secondsSoFar = $fractionOfCurrentYear * $this->getSecondsPerYear($firstDayOfYear);

        $secondsSinceEpoch = $firstDayOfYear->format('U') + $secondsSoFar;
        return \DateTime::createFromFormat('U', round($secondsSinceEpoch), $this->timezone);
    }
    
    protected function getDaysPerYear(\DateTimeInterface $dateTime):int
    {
        return (new \DateTime($dateTime->format('Y-12-31'), $this->timezone))->format('z') + 1;
    }
    
    protected function getSecondsPerYear(\DateTimeInterface $dateTime):int
    {
        return $this->getDaysPerYear($dateTime) * 24 * 60 * 60;
    }
}
