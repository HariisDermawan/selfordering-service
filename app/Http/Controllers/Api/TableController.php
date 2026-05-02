<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TableRequest;
use App\Http\Resources\TableResource;
use App\Models\Table;
use App\Services\TableService;
use Illuminate\Http\Request;

class TableController extends Controller
{
    protected $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    public function index(Request $request)
    {
        $tables = $this->tableService->getAll($request->all());
        
        return TableResource::collection($tables);
    }

    public function store(TableRequest $request)
    {
        $table = $this->tableService->create($request->validated());
        
        return new TableResource($table);
    }

    public function show(Table $table)
    {
        return new TableResource($table->load('orders'));
    }

    public function update(TableRequest $request, Table $table)
    {
        $table = $this->tableService->update($table->id, $request->validated());
        
        return new TableResource($table);
    }

    public function destroy(Table $table)
    {
        $this->tableService->delete($table->id);
        
        return $this->successResponse(null, 'Table deleted successfully');
    }

    public function updateStatus(Request $request, Table $table)
    {
        $request->validate(['status' => 'required|in:available,occupied,reserved,maintenance']);
        
        $table = $this->tableService->updateStatus($table->id, $request->status);
        
        return $this->successResponse($table, 'Table status updated');
    }

    public function getAvailable()
    {
        $tables = $this->tableService->getAvailable();
        
        return TableResource::collection($tables);
    }

    public function generateQRCode(Table $table)
    {
        $qrCode = $this->tableService->generateQRCode($table->id);
        
        return $this->successResponse(['qr_code' => $qrCode], 'QR Code generated');
    }
}