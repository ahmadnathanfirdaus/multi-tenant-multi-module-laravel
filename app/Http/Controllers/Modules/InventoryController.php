<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryProduct;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $products = InventoryProduct::with('user')->latest()->paginate(10);
        return view('modules.inventory.index', compact('products'));
    }

    public function show(InventoryProduct $product)
    {
        return view('modules.inventory.show', compact('product'));
    }

    public function create()
    {
        return view('modules.inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'sku' => 'required|max:255|unique:inventory_products,sku',
            'description' => 'nullable',
            'category' => 'nullable|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'unit' => 'required|max:50',
            'supplier' => 'nullable|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        InventoryProduct::create($validated);

        return redirect()->route('modules.inventory.index')
                        ->with('success', 'Product created successfully!');
    }

    public function edit(InventoryProduct $product)
    {
        return view('modules.inventory.edit', compact('product'));
    }

    public function update(Request $request, InventoryProduct $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'sku' => 'required|max:255|unique:inventory_products,sku,' . $product->id,
            'description' => 'nullable',
            'category' => 'nullable|max:255',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'unit' => 'required|max:50',
            'supplier' => 'nullable|max:255',
        ]);

        $product->update($validated);

        return redirect()->route('modules.inventory.index')
                        ->with('success', 'Product updated successfully!');
    }

    public function destroy(InventoryProduct $product)
    {
        $product->delete();

        return redirect()->route('modules.inventory.index')
                        ->with('success', 'Product deleted successfully!');
    }
}
