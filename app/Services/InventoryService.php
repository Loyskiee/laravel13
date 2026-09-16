<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use App\Repositories\InventoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryService
{
    public function __construct(
        protected InventoryRepositoryInterface $inventoryRepo,
        protected ProductRepositoryInterface $productRepo
    ) {}

    public function stockIn(Product $product, int $quantity, string $reason, User $user): Inventory
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be positive.']);
        }
        if (trim($reason) === '') {
            throw ValidationException::withMessages(['reason' => 'Reason is required.']);
        }

        return DB::transaction(function () use ($product, $quantity, $reason, $user) {
            $fresh = Product::where('id', $product->id)->lockForUpdate()->firstOrFail();
            $before = $fresh->quantity;
            $after = $before + $quantity;

            $this->productRepo->update(['quantity' => $after], $fresh);

            return $this->inventoryRepo->createMovement([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => 'in',
                'quantity' => $quantity,
                'reason' => $reason,
                'before_quantity' => $before,
                'after_quantity' => $after,
            ]);
        });
    }

    public function stockOut(Product $product, int $quantity, string $reason, User $user): Inventory
    {
        if ($quantity <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantity must be positive.']);
        }
        if (trim($reason) === '') {
            throw ValidationException::withMessages(['reason' => 'Reason is required.']);
        }

        return DB::transaction(function () use ($product, $quantity, $reason, $user) {
            $fresh = Product::where('id', $product->id)->lockForUpdate()->firstOrFail();
            $before = $fresh->quantity;

            if ($quantity > $before) {
                throw ValidationException::withMessages(['quantity' => 'Cannot exceed available stock.']);
            }

            $after = $before - $quantity;

            $this->productRepo->update(['quantity' => $after], $fresh);

            return $this->inventoryRepo->createMovement([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => 'out',
                'quantity' => $quantity,
                'reason' => $reason,
                'before_quantity' => $before,
                'after_quantity' => $after,
            ]);
        });
    }

    public function adjust(Product $product, int $correctedQuantity, string $reason, User $user): Inventory
    {
        if ($correctedQuantity < 0) {
            throw ValidationException::withMessages(['quantity' => 'Quantity cannot be negative.']);
        }
        if (trim($reason) === '') {
            throw ValidationException::withMessages(['reason' => 'Reason is required.']);
        }

        return DB::transaction(function () use ($product, $correctedQuantity, $reason, $user) {
            $fresh = Product::where('id', $product->id)->lockForUpdate()->firstOrFail();
            $before = $fresh->quantity;
            $after = $correctedQuantity;
            $delta = abs($after - $before);

            if ($before === $after) {
                throw ValidationException::withMessages(['quantity' => 'Corrected quantity is same as current.']);
            }

            $this->productRepo->update(['quantity' => $after], $fresh);

            return $this->inventoryRepo->createMovement([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'type' => 'adjustment',
                'quantity' => $delta,
                'reason' => $reason,
                'before_quantity' => $before,
                'after_quantity' => $after,
            ]);
        });
    }

    public function getRecentForUser(User $user, array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->inventoryRepo->getRecentForUser($user, $filters, $perPage);
    }
}
