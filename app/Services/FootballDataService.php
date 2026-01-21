<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FootballDataService
{
    protected string $baseUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.football_data.base_url');
        $this->apiKey = config('services.football_data.api_key');
    }

    /**
     * Fetch upcoming matches from external API.
     * Uses caching and robust error handling.
     */
    public function getUpcomingMatches(): array
    {
        // Cache response for 60 minutes to optimize performance and stay within API limits
        return Cache::remember('football_upcoming_matches', 3600, function () {
            return $this->fetchFromApi();
        });
    }

    protected function fetchFromApi(): array
    {
        try {
            // If no API Key matches the environment variable, authorize fallback immediately
            if (empty($this->apiKey)) {
                return $this->getMockData();
            }

            // External API Call with timeout and headers
            $response = Http::withHeaders([
                'X-Auth-Token' => $this->apiKey,
            ])
            ->timeout(5) // Fail fast if API hangs
            ->get($this->baseUrl . 'matches', [
                // 'status' => 'SCHEDULED', // Optional filters
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['matches'] ?? [];
            }

            // Log the specific error for debugging
            Log::warning('Football API Unsuccessful: ' . $response->status() . ' - ' . $response->body());
            
            // Return mock data so the widget doesn't break the layout
            return $this->getMockData();

        } catch (\Exception $e) {
            // Handle connection errors (DNS, Timeout, etc.)
            Log::error('Football API Connection Failed: ' . $e->getMessage());
            return $this->getMockData();
        }
    }

    /**
     * Provides fallback data when API is unreachable or unconfigured.
     */
    protected function getMockData(): array
    {
        return [
            [
                'id' => 101,
                'competition' => ['name' => 'Demo League'],
                'utcDate' => now()->addHours(24)->toIso8601String(),
                'homeTeam' => ['name' => 'Manchester City (Demo)', 'crest' => null],
                'awayTeam' => ['name' => 'Arsenal (Demo)', 'crest' => null],
            ],
            [
                'id' => 102,
                'competition' => ['name' => 'Champions Cup'],
                'utcDate' => now()->addDays(3)->toIso8601String(),
                'homeTeam' => ['name' => 'Real Madrid (Demo)', 'crest' => null],
                'awayTeam' => ['name' => 'Bayern Munich (Demo)', 'crest' => null],
            ],
            [
                'id' => 103,
                'competition' => ['name' => 'World Showcase'],
                'utcDate' => now()->addDays(5)->toIso8601String(),
                'homeTeam' => ['name' => 'Brazil (Demo)', 'crest' => null],
                'awayTeam' => ['name' => 'France (Demo)', 'crest' => null],
            ],
        ];
    }
}
