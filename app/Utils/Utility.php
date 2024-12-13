<?php

namespace App\Utils;

use App\Models\Post;
use App\Utils\Contracts\IUtility;
use Carbon\Carbon;

class Utility implements IUtility
{
    private Carbon $datetime;
    public function __construct()
    {
        $this->datetime = Carbon::now();
    }

    function daysOfMonthLength(?int $month = null): int|false
    {
        $firstDateOfMonth = date_create(date('Y-m-') . '01');
        $lastDateOfMonth = date_create($firstDateOfMonth->format('Y-m-t'));
        $dateInterval = date_diff($firstDateOfMonth, $lastDateOfMonth);
        return $dateInterval->days +1;
    }

    function datesOfTheMonthBy(int $daysOfMonthLength): array
    {
        $dates = [];
        $i = 0;
        do { ++$i;
            $dates[] = $i;
        } while($i < $daysOfMonthLength);
        return $dates;
    }
    public function datesOfTheMonth(?int $count = 31): array
    {
        $result = [];
        for ($i = 1; $i <= $count; $i++) {
            $result[] = sprintf('%02d', $i);
        }
        return $result;
    }

    public function getListOfDates(int $nextDayCount, ?string $startDate = null): array
    {
        $list = [];
        $startDate = $startDate ?? date('Y-m-d');

        for ($i = 0; $i < $nextDayCount; $i++) {
            $list[] = date('Y-m-d', strtotime($startDate . ' +' . $i . ' day'));
        }

        return $list;
    }

    public function getListOfTimes(int $startHour, int $endHour): array
    {
        $list = [];
        for ($i = $startHour; $i <= $endHour; $i += 2) {
            $list[] = str_pad(
                    "$i",
                    2,
                    0,
                    STR_PAD_LEFT) .':00:00';
        }
        return $list;
    }
    public function getListOfTimesOptions(
        int $startHour, int $endHour, ?bool $isWholeTime = null): array
    {
        $result = $this->getListOfTimes($startHour, $endHour);

        if($isWholeTime) array_unshift(
            $result, $result[0]." ~ ".$result[count($result) - 1]
        );

        return array_map(fn ($time)  => [
            'name' => $time,
            'value' => $time,

        ], array_values($result));
    }

    public function getListOfDatesOptions(
        int $nextDayCount, ?string $startDate, ?bool $isWholeDate = false): array
    {
        $dates = $this->getListOfDates($nextDayCount, $startDate);

        if($isWholeDate) array_unshift(
            $dates, $dates[0]." ~ ".$dates[count($dates) - 1]
        );
        $result = array();

        for ($i = 0; $i < count($dates); $i++) {
            $result[$i]['name'] = $dates[$i];
            $result[$i]['value'] = $dates[$i];

            if ($nextDayCount != 2) continue;
            $result[$i]['name'] .= (' '.($i == 0 ? '(Today)' : '(This Week)'));
        }
        return $result;
    }

    public function timeAgo(string $datetime): string
    {
        return $this->datetime->diffForHumans($datetime);
    }
    public function transporter(mixed $operator): string
    {
        return trim('('.($operator->department->short_name ?? 'NA').') '.
            ($operator->prefix ?? '').' '.
            ($operator->name ?? 'NA').' '.
            ($operator->postfix ?? '')
        );
    }

    public function nameOfMonth(string $numOfMonth): string
    { // carbon parse number of months to name of month
        $date = "$numOfMonth/01".date('Y');
        return date('M', strtotime($date));
    }
    /*private function tomorrow()
    {
        $date = "01/$numOfMonth/".date('Y');
        $date1 = str_replace('-', '/', $date);
        $tomorrow = date('m-d-Y',strtotime($date1 . "+1 days"));

        echo $tomorrow;
    }*/
    public function combineDashboardArrays(array $loads, array $days): array
    {
        $view = [];
        $colSum = 0;
        for ($i = 0; $i < count($days); $i++) {
            $result = 0;
            foreach ($loads as $load){
                if ($load['day'] != $days[$i]) continue;

                $result = $load['count'];
                $colSum += $result;
            }
            $view['days'][$i+1] = $result;
        }
        $view['total'] = $colSum;

        return $view;
    }
    public function countWoPendingRequest(Post $post): int
    {
        return collect($post->workTrips)
            ->filter(function ($wt){
                return $wt->status == WorkOrderStatusEnum::STATUS_PENDING->value;
            })
            ->count();
    }
    public function countWtPendingRequest(Post $post): int
    {
        return collect($post->workTrips)
            ->filter(function ($wt){
                return $wt->status == WorkTripStatusEnum::PENDING->value;
            })
            ->count();
    }

    function addDaysOfParse(
        ?string $latestDate, ?int $dayCount = 1): ?string
    {
        $carbon = Carbon::parse($latestDate);
        return $carbon->addDays($dayCount);
    }
}
