<x-layouts.app>
    <div class="p-6">
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-box mr-3 text-orange-500"></i>
                        {{ $product->name }}
                    </h1>
                    <p class="text-gray-600 mt-2">Product Details</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('modules.inventory.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <a href="{{ route('modules.inventory.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Products
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Product Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Product Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <p class="text-gray-900 font-medium">{{ $product->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $product->sku }}</code>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <p class="text-gray-900">{{ $product->category ?? 'Not categorized' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                            <p class="text-gray-900">{{ $product->supplier ?? 'Not specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <p class="text-gray-900">{{ $product->unit }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($product->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-900">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pricing & Stock Information -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Pricing & Stock</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Selling Price</label>
                            <p class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                        </div>
                        
                        @if($product->cost)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cost Price</label>
                                <p class="text-xl font-semibold text-gray-600">${{ number_format($product->cost, 2) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Profit Margin</label>
                                <p class="text-lg font-medium text-blue-600">
                                    ${{ number_format($product->price - $product->cost, 2) }}
                                    ({{ number_format((($product->price - $product->cost) / $product->price) * 100, 1) }}%)
                                </p>
                            </div>
                        @endif
                        
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                            <p class="text-2xl font-bold {{ $product->isLowStock() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $product->stock_quantity }} {{ $product->unit }}
                            </p>
                            @if($product->isLowStock())
                                <p class="text-sm text-red-600 mt-1">
                                    <i class="fas fa-exclamation-triangle"></i> Low Stock Warning
                                </p>
                            @endif
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stock Level</label>
                            <p class="text-gray-900">{{ $product->min_stock_level }} {{ $product->unit }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Created</label>
                            <p class="text-gray-900">{{ $product->created_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $product->updated_at->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Added By</label>
                            <p class="text-gray-900">{{ $product->user->name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('modules.inventory.edit', $product) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 transition-colors">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Product
                        </a>
                        
                        <form action="{{ route('modules.inventory.destroy', $product) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="fas fa-trash mr-2"></i>
                                Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
