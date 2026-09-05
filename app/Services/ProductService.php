<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\InventoryRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

/**
 * Add automatic sku generator based on category
 */
class ProductService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepo,
        protected ProductRepositoryInterface $productRepo,
        protected InventoryRepositoryInterface $inventoryRepo
    ) {}

    // Creates product and initial inventory movement atomically
    public function createWithInitialMovement(array $data, User $user): Product
    {
        return DB::transaction(function () use ($data, $user) {
            $quantity = $data['quantity'] ?? 0;

            // Create product with owner injected server-side
            $product = $this->productRepo->create([
                ...$data,
                'user_id' => $user->id,
            ]);

            // Record traceable movement if initial stock exists
            if ($quantity > 0) {
                $this->inventoryRepo->createMovement([
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'type' => 'initial',
                    'quantity' => $quantity,
                    'reason' => 'Initial stock',
                    'before_quantity' => 0,
                    'after_quantity' => $quantity,
                ]);
            }

            return $product;
        });
    }

    private function generateSku(Category $category, User $user): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $category->name), 0, 3));
        $prefix = str_pad($prefix, 3, 'X');

        // Find max sequence for this user + prefix
        $maxSku = Product::where('user_id', $user->id)
            ->where('sku', 'like', $prefix . '-%')
            ->orderByDesc('sku')
            ->value('sku');

        $next = 1;
        if ($maxSku && preg_match('/-(\d+)$/', $maxSku, $m)) {
            $next = (int) $m[1] + 1;
        }

        // Loop until unique (handles race vs unique constraint)
        do {
            $sku = sprintf('%s-%04d', $prefix, $next);
            $exists = Product::where('user_id', $user->id)->where('sku', $sku)->exists();
            if (!$exists) {
                return $sku;
            }
            $next++;
        } while (true);
    }
    
    public function createSku(array $data, User $user): Product
    {
        if (empty($data['sku'])) {
            $category = $this->categoryRepo->find($data['category_id']);
            $data['sku'] = $this->generateSku($category, $user);
        }

        return $this->createWithInitialMovement($data, $user);
    }

}
