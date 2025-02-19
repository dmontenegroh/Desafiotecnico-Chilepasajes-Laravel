<?php

namespace App\Domain\Donki\Services;

use App\Domain\Donki\Contracts\DonkiRepositoryInterface;


class DonkiService
{

    protected DonkiRepositoryInterface $donkiRepository;

    public function __construct(DonkiRepositoryInterface $donkiRepository)
    {
        $this->donkiRepository = $donkiRepository;
    }

    public function getInstruments(string $startDate, string $endDate): array
    {
        return $this->donkiRepository->getInstruments($startDate, $endDate);
    }

    public function getActivityIDs(string $startDate, string $endDate): array
    {
        return $this->donkiRepository->getActivityIDs($startDate, $endDate);
    }

    public function getInstrumentUsagePercentage(string $startDate, string $endDate): array
    {
        return $this->donkiRepository->getInstrumentUsagePercentage($startDate, $endDate);
    }

    public function getInstrumentUsagePercentageByName(string $instrument, string $startDate, string $endDate): array
    {
        $decodedInstrument = urldecode($instrument);
        return $this->donkiRepository->getInstrumentUsagePercentageByName($decodedInstrument, $startDate, $endDate);
    }
}
