<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(protected InventoryService $inventory){}

    public function index(Request $request)
    {
        $user = $request->user();

        $base = Product::where('user_id', $user->id);

        $stats = [
          'total_products' => (clone $base)->count(),
            'total_quantity' => (clone $base)->sum('quantity'),
            'low_stock' => (clone $base)->where('quantity', '>', 0)->whereColumn('quantity', '<=', 'minimum_stock')->count(),
            'out_of_stock' => (clone $base)->where('quantity', 0)->count(),
            'categories' => Category::where('user_id', $user->id)->count(),
        ];

        $needsAttention = (clone $base)
            ->where(fn ($q) => $q->where('quantity', 0)->orWhere(fn ($q) => $q->where('quantity', '>', 0)->whereColumn('quantity', '<=', 'minimum_stock')))
            ->with('category:id,name')
            ->orderBy('quantity')
            ->limit(5)
            ->get(['id', 'name', 'sku', 'quantity', 'minimum_stock', 'category_id']);

        $recentActivity = $this->inventory->getRecentForUser($user, [], 5);
        
        return Inertia::render('Dashboard', compact('stats', 'needsAttention', 'recentActivity'));
    }
}
