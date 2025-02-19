<?php

namespace App\Domain\Donki\Contracts;

interface DonkiRepositoryInterface
{
    public function getInstruments(string $startDate, string $endDate): array;

    public function getActivityIDs(string $startDate, string $endDate): array;

    public function getInstrumentUsagePercentage(string $startDate, string $endDate): array;

    public function getInstrumentUsagePercentageByName(string $instrument, string $startDate, string $endDate): array;
}
