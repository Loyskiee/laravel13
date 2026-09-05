<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Repositories\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepo,
        protected ProductService $productService
    ) {}

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Product::class);

        $products = $this->productRepo->getPaginatedProducts(
            $request->user(),
            $request->only('search', 'stock_status', 'category_id'),
            10
        );

        $categories = $request->user()->categories()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Products/Index', [
            'categories' => $categories,
            'products' => $products,
            'filters' => $request->only('search', 'stock_status', 'category_id')
        ]);
    }

    public function create(Request $request)
    {
        
        return Inertia::render('Products/Create', [
            'categories' => $request->user()->categories()->select('id', 'name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->productService->createSku($request->validated(), $request->user());

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function show(Product $product, Request $request)
    {
        Gate::authorize('view', $product);

        $product->load(['category:id,name', 'inventoryMovements' => fn ($q) => $q->latest()->limit(10)]);

        return Inertia::render('Products/Show', [
            'product' => $product,
            'categories' => $request->user()->categories()->select('id', 'name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        Gate::authorize('update', $product);

        $product = $this->productRepo->update($request->validated(), $product);

        return redirect()->route('products.show', $product)->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        if ($product->inventoryMovements()->exists()) {
            return back()->withErrors(['product' => 'Cannot delete product with inventory history.']);
        }

        $this->productRepo->delete($product);

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}
