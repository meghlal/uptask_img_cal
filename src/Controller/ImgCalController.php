<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\Finder\in;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\SubscribedService;

class ImgCalController extends AbstractController
{

    private $months_in_a_year;
    private $days_in_a_month_even;
    private $days_in_a_month_odd;
    private $days_in_leaplear_last_month;
    private $leapyear_by;
    private $first_day_of_1990;
    private $days;
    private $base_date;
    private $month_days;

    /**
     * ImgCalController constructor.
     * @param int $months_in_a_year
     */
    public function __construct()
    {
        $this->months_in_a_year = 13;
        $this->days_in_a_month_even = 21;
        $this->days_in_a_month_odd = 22;
        $this->days_in_leaplear_last_month = 21;
        $this->leapyear_by = 5;
        $this->first_day_of_1990 = "Monday";
        $this->days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $this->base_date = "01.01.1990";
        $this->month_days = [22, 21, 22, 21, 22, 21, 22, 21, 22, 21, 22, 21, 22];
    }

    public function index(string $date): JsonResponse
    {
        return $this->json([
            'input_date' => $date,
            'day' => $this->calculateDays($date)

        ]);
    }

    private function calculateDays(string $date){
        list($day, $month, $year) = explode('.', $date);
        list($basedate_day, $basedate_month, $basedate_year) = explode('.', $this->base_date);

        $year_gap = $year - $basedate_year;
        $month_gap = $month - $basedate_month;
        $day_gap = $day - $basedate_day;

        $months_in_gap_year_odd =   $year_gap * 7;
        $months_in_gap_year_even =   $year_gap * 6;

        $total_days = ($months_in_gap_year_odd * $this->days_in_a_month_odd) + ($months_in_gap_year_even * $this->days_in_a_month_even) - intval($year_gap / $this->leapyear_by);

        for ($i = 0; $i < $month_gap; $i++) {
            $total_days += $this->month_days[$i];
        }
        $total_days+=$day_gap;

        return $this->days[$total_days % count($this->days)];

    }
}
