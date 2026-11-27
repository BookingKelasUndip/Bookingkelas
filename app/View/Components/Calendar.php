<?php

namespace App\View\Components;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Calendar extends Component
{
    public $year;

    public $month;

    public $id;

    public bool $allowPast;

    public function __construct($id = 'calendar', $year = null, $month = null, bool $allowPast = false)
    {
        $this->id = $id;
        $this->year = $year ?? Carbon::now()->year;
        $this->month = $month ?? Carbon::now()->month;
        $this->allowPast = $allowPast;
    }

    public function render(): View|Closure|string
    {
        return view('components.calendar');
    }
}
