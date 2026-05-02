<?php

namespace App\Services;

use App\Enums\TableStatus;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TableService
{
    protected $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function getAll(array $filters = [])
    {
        $query = $this->table->with('orders');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['capacity'])) {
            $query->where('capacity', '>=', $filters['capacity']);
        }

        if (isset($filters['search'])) {
            $query->where('table_number', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('table_number')->paginate($filters['per_page'] ?? 15);
    }

    public function findById(int $id)
    {
        return $this->table->with('orders')->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $data['status'] = $data['status'] ?? TableStatus::AVAILABLE->value;
            $data['qr_code'] = $data['qr_code'] ?? $this->makeQrCodeValue();

            return $this->table->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $table = $this->findById($id);
            $table->update($data);

            return $table;
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            $table = $this->findById($id);

            return $table->delete();
        });
    }

    public function updateStatus(int $id, string $status)
    {
        return DB::transaction(function () use ($id, $status) {
            $table = $this->findById($id);
            $table->update(['status' => $status]);

            return $table;
        });
    }

    public function getAvailable()
    {
        return $this->table
            ->where('status', TableStatus::AVAILABLE->value)
            ->orderBy('table_number')
            ->get();
    }

    public function generateQRCode(int $id): string
    {
        return DB::transaction(function () use ($id) {
            $table = $this->findById($id);
            $qrCode = $this->makeQrCodeValue();

            $table->update(['qr_code' => $qrCode]);

            return $qrCode;
        });
    }

    protected function makeQrCodeValue(): string
    {
        return 'TABLE-' . Str::upper(Str::random(32));
    }
}
