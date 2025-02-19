<?php

namespace App\Domain\Donki\Repositories;

use App\Domain\Donki\Contracts\DonkiRepositoryInterface;
use App\Domain\Donki\Entities\Instrument;
use App\Domain\Donki\Entities\Activity;
use GuzzleHttp\Client;

class DonkiRepository implements DonkiRepositoryInterface
{
    protected Client $client;
    protected string $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('NASA_API_KEY');
    }

    private function getEndpoints(string $startDate, string $endDate): array
    {
        return [
            'CME' => "https://api.nasa.gov/DONKI/CME?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
            'IPS' => "https://api.nasa.gov/DONKI/IPS?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
            'FLR' => "https://api.nasa.gov/DONKI/FLR?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
            'SEP' => "https://api.nasa.gov/DONKI/SEP?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
            'MPC' => "https://api.nasa.gov/DONKI/MPC?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
            'RBE' => "https://api.nasa.gov/DONKI/RBE?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
            'HSS' => "https://api.nasa.gov/DONKI/HSS?startDate={$startDate}&endDate={$endDate}&api_key={$this->apiKey}",
        ];
    }

    public function getInstruments(string $startDate, string $endDate): array
    {
        $endpoints = $this->getEndpoints($startDate, $endDate);
        $instruments = [];

        foreach ($endpoints as $type => $url) {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);

            foreach ($data as $entry) {
                if (isset($entry['instruments'])) {
                    foreach ($entry['instruments'] as $instrumentData) {
                        $instrumentName = $instrumentData['displayName'];
                        $instrument = new Instrument($instrumentName);
                        $instruments[] = $instrument->name;
                    }
                }
            }
        }

        return $instruments;
    }

    public function getActivityIDs(string $startDate, string $endDate): array
    {
        $endpoints = $this->getEndpoints($startDate, $endDate);
        $activityIDs = [];

        foreach ($endpoints as $type => $url) {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);

            foreach ($data as $entry) {
                if (isset($entry['activityID'])) {
                    $activity = new Activity($this->extractActivityID($entry['activityID']));
                    $activityIDs[] = $activity->id;
                }

                if (isset($entry['linkedEvents'])) {
                    foreach ($entry['linkedEvents'] as $linkedEvent) {
                        if (isset($linkedEvent['activityID'])) {
                            $activity = new Activity($this->extractActivityID($linkedEvent['activityID']));
                            $activityIDs[] = $activity->id;
                        }
                    }
                }
            }
        }

        return $activityIDs;
    }

    public function getInstrumentUsagePercentage(string $startDate, string $endDate): array
    {
        $instruments = $this->getInstruments($startDate, $endDate);
        $instrumentCounts = array_count_values($instruments);
        $totalAppearances = array_sum($instrumentCounts);
        $usagePercentages = [];

        foreach ($instrumentCounts as $instrument => $count) {
            $percentage = $count / $totalAppearances;
            $usagePercentages[$instrument] = round($percentage, 1);
        }

        return $usagePercentages;
    }

    public function getInstrumentUsagePercentageByName(string $instrument, string $startDate, string $endDate): array
    {
        $endpoints = $this->getEndpoints($startDate, $endDate);
        $activities = [];

        foreach ($endpoints as $type => $url) {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);

            foreach ($data as $entry) {
                if (isset($entry['instruments'])) {
                    $instrumentNames = array_column($entry['instruments'], 'displayName');
                    if (in_array($instrument, $instrumentNames)) {
                        if (isset($entry['activityID'])) {
                            $activityID = $this->extractActivityID($entry['activityID']);
                            $activities[$activityID] = ($activities[$activityID] ?? 0) + 1;
                        }

                        if (isset($entry['linkedEvents'])) {
                            foreach ($entry['linkedEvents'] as $linkedEvent) {
                                if (isset($linkedEvent['activityID'])) {
                                    $activityID = $this->extractActivityID($linkedEvent['activityID']);
                                    $activities[$activityID] = ($activities[$activityID] ?? 0) + 1;
                                }
                            }
                        }
                    }
                }
            }
        }

        $totalAppearances = array_sum($activities);
        $usagePercentages = [];

        foreach ($activities as $activityID => $count) {
            $usagePercentages[$activityID] = round($count / $totalAppearances, 1);
        }

        return [
            $instrument => $usagePercentages
        ];
    }

    private function extractActivityID(string $fullActivityID): string
    {
        $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}-/';
        $activityID = preg_replace($pattern, '', $fullActivityID);
        return $activityID;
    }
}
