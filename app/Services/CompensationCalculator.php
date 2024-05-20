<?php
namespace App\Services;
use App\Models\Commute;
use App\Models\Transport;
use Carbon\Carbon;

class CompensationCalculator
{
    public const WEEKS_IN_MONTH = 4;
    public const TRAVELS_PER_DAY = 2; // assuming going to work and back home

    public function calculateMonthlyCompensation(Commute $commute): float
    {
        $rate = $commute->transport->rate;
        $multiplier = $this->getMultiplier($commute);
        $dailyCompensation = $rate * $commute->distance * $multiplier * self::TRAVELS_PER_DAY;
        $weeklyCompensation = $dailyCompensation * $commute->workdays_per_week;
        // assuming 4 weeks in a month for simplicity
        return $weeklyCompensation * self::WEEKS_IN_MONTH;
    }

    protected function getMultiplier(Commute $commute): float
    {
        if ($this->isBikeWithinRange($commute)) {
            return 2.0;
        }
        return 1.0;
    }

    protected function isBikeWithinRange(Commute $commute): bool
    {
        return $commute->transport->type === Transport::TYPE_BIKE &&
            $commute->distance >= 5 &&
            $commute->distance <= 10;
    }

    public function getPaymentDatesForYear($year): array
    {
        $dates = [];
        for ($month = 1; $month <= 12; $month++) {
            $date = new Carbon("first monday of $year-$month");
            $dates[] = $date;
        }
        return $dates;
    }
}
