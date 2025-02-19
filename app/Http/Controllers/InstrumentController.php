<?php

namespace App\Http\Controllers;

use App\Domain\Donki\Services\DonkiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstrumentController extends Controller
{

    protected DonkiService $donkiService;

    public function __construct(DonkiService $donkiService)
    {
        $this->donkiService = $donkiService;
    }

    public function getInstruments(Request $request): JsonResponse
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $instruments = $this->donkiService->getInstruments($startDate, $endDate);
        return response()->json(['instruments' => $instruments]);
    }

    public function getInstrumentUsagePercentage(Request $request): JsonResponse
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $usagePercentage = $this->donkiService->getInstrumentUsagePercentage($startDate, $endDate);
        return response()->json(['instruments_use' => $usagePercentage]);
    }

    public function getInstrumentUsagePercentageByName(Request $request): JsonResponse
    {
        $modelInstrument = $request->query('model');
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $usagePercentage = $this->donkiService->getInstrumentUsagePercentageByName($modelInstrument, $startDate, $endDate);
        return response()->json(['instrument_activity' => $usagePercentage]);
    }
}
