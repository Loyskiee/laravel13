<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Http\Requests\StoreInventoryRequest;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected ProductRepositoryInterface $productRepo
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Inventory::class);

        $movements = $this->inventoryService->getRecentForUser(
            $request->user(),
            $request->only('search', 'type', 'product_id'),
            10
        );

        return Inertia::render('InventoryHistory', [
            'movements' => $movements,
            'filters' => $request->only('search', 'type', 'product_id'),
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Inventory/Create', [
            'products' => $request->user()->products()->select('id', 'name')->get(),
        ]);
    }

    public function store(StoreInventoryRequest $request)
    {
        Gate::authorize('create', Inventory::class);

        $data = $request->validated();
        $product = $this->productRepo->find($data['product_id']);

        // Ensure product belongs to user via policy
        Gate::authorize('view', $product);

        match ($data['type']) {
            'in' => $this->inventoryService->stockIn($product, $data['quantity'], $data['reason'], $request->user()),
            'out' => $this->inventoryService->stockOut($product, $data['quantity'], $data['reason'], $request->user()),
            'adjustment' => $this->inventoryService->adjust($product, $data['corrected_quantity'], $data['reason'], $request->user()),
            default => throw new \InvalidArgumentException('Invalid movement type'),
        };

        return to_route('inventories.index')->with('success', 'Inventory updated.');
    }

    public function show(Inventory $inventory)
    {
        Gate::authorize('view', $inventory);
        return Inertia::render('Inventory/Show', ['inventory' => $inventory->load(['product','user'])]);
    }

    public function edit(Inventory $inventory) 
    { 
        abort(404);

    }
    public function update() 
    { 
        abort(404); 
    }
    public function destroy() 
    { 
        abort(404); 
    
    }
}
