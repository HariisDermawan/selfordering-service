<?php

namespace App\Services;

use App\Models\Tax;

class TaxService
{
    protected $tax;

    public function __construct(Tax $tax)
    {
        $this->tax = $tax;
    }

    public function calculateTax(float $amount, string $taxCode): float
    {
        $tax = $this->tax->where('code', $taxCode)
            ->where('is_active', true)
            ->first();

        if (!$tax) {
            return 0;
        }

        if ($tax->type === 'percentage') {
            return round($amount * ($tax->rate / 100), 2);
        }

        return $tax->rate;
    }

    public function calculatePB1(float $amount): float
    {
        return $this->calculateTax($amount, 'PB1');
    }

    public function calculateServiceCharge(float $amount, float $rate = 5.0): float
    {
        return round($amount * ($rate / 100), 2);
    }

    public function getAllTaxes()
    {
        return $this->tax->where('is_active', true)->get();
    }

    public function createTax(array $data)
    {
        return $this->tax->create($data);
    }

    public function updateTax(int $id, array $data)
    {
        $tax = $this->tax->findOrFail($id);
        $tax->update($data);
        return $tax;
    }
}