<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        $customers = $this->customerService->getAll($request->all());
        
        return CustomerResource::collection($customers);
    }

    public function store(CustomerRequest $request)
    {
        $customer = $this->customerService->create($request->validated());
        
        return new CustomerResource($customer);
    }

    public function show(Customer $customer)
    {
        return new CustomerResource($customer->load('orders'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer = $this->customerService->update($customer->id, $request->validated());
        
        return new CustomerResource($customer);
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->delete($customer->id);
        
        return $this->successResponse(null, 'Customer deleted successfully');
    }

    public function getPoints(Customer $customer)
    {
        return $this->successResponse([
            'points' => $customer->total_points,
            'member_since' => $customer->created_at
        ], 'Customer points retrieved');
    }
}