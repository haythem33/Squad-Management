<?php

namespace App\View\Components;

use App\Services\FootballDataService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ExternalFootballWidget extends Component
{
    public array $matches;

    /**
     * Create a new component instance.
     */
    public function __construct(FootballDataService $footballService)
    {
        $this->matches = $footballService->getUpcomingMatches();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.external-football-widget');
    }
}
