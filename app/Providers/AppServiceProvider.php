<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\Order;
use App\Models\Shift;
use App\Models\Payment;
use App\Models\Category;
use App\Models\Table;
use App\Models\Customer;
use App\Models\Material;
use App\Models\Promotion;
use App\Models\KitchenTicket;
use App\Models\DeliveryOrder;
use App\Models\SplitBill;
use App\Models\Receipt;
use App\Models\Refund;
use App\Models\VoidTransaction;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ShiftPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\TablePolicy;
use App\Policies\CustomerPolicy;
use App\Policies\MaterialPolicy;
use App\Policies\PromotionPolicy;
use App\Policies\KitchenPolicy;
use App\Policies\DeliveryPolicy;
use App\Policies\SplitBillPolicy;
use App\Policies\ReceiptPolicy;
use App\Policies\RefundPolicy;
use App\Policies\VoidPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Shift::class, ShiftPolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Table::class, TablePolicy::class);
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Material::class, MaterialPolicy::class);
        Gate::policy(Promotion::class, PromotionPolicy::class);
        Gate::policy(KitchenTicket::class, KitchenPolicy::class);
        Gate::policy(DeliveryOrder::class, DeliveryPolicy::class);
        Gate::policy(SplitBill::class, SplitBillPolicy::class);
        Gate::policy(Receipt::class, ReceiptPolicy::class);
        Gate::policy(Refund::class, RefundPolicy::class);
        Gate::policy(VoidTransaction::class, VoidPolicy::class);
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}