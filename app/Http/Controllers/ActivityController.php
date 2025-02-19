<?php

namespace App\Http\Controllers;

use App\Domain\Donki\Services\DonkiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{

    protected DonkiService $donkiService;

    public function __construct(DonkiService $donkiService)
    {
        $this->donkiService = $donkiService;
    }

    public function getActivityIDs(Request $request): JsonResponse
    {
        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        $activityIDs = $this->donkiService->getActivityIDs($startDate, $endDate);
        return response()->json(['activityIDs' => $activityIDs]);
    }
}
