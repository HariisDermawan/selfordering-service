<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpenShiftRequest;
use App\Http\Requests\CloseShiftRequest;
use App\Http\Requests\CashMovementRequest;
use App\Http\Resources\ShiftResource;
use App\Models\Shift;
use App\Services\ShiftService;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    protected $shiftService;

    public function __construct(ShiftService $shiftService)
    {
        $this->shiftService = $shiftService;
    }

    public function openShift(OpenShiftRequest $request)
    {
        $shift = $this->shiftService->openShift($request->validated());
        
        return new ShiftResource($shift);
    }

    public function closeShift(CloseShiftRequest $request, Shift $shift)
    {
        $shift = $this->shiftService->closeShift($shift->id, $request->validated());
        
        return new ShiftResource($shift);
    }

    public function currentShift()
    {
        $shift = $this->shiftService->getCurrentShift();
        
        if (!$shift) {
            return $this->errorResponse('No active shift found', 404);
        }
        
        return new ShiftResource($shift);
    }

    public function cashMovement(CashMovementRequest $request, Shift $shift)
    {
        $log = $this->shiftService->cashMovement($shift->id, $request->validated());
        
        return $this->successResponse($log, 'Cash movement recorded');
    }

    public function getXReport(Shift $shift)
    {
        $report = $this->shiftService->getXReport($shift->id);
        
        return $this->successResponse($report, 'X Report generated');
    }

    public function getZReport(Shift $shift)
    {
        $report = $this->shiftService->getZReport($shift->id);
        
        return $this->successResponse($report, 'Z Report generated');
    }

    public function index(Request $request)
    {
        $shifts = $this->shiftService->getAll($request->all());
        
        return ShiftResource::collection($shifts);
    }
}