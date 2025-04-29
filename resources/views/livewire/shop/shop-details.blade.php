<div>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Shop Management</h2>

            <!-- Shop Selector -->
            <div class="mb-6">
                <label for="shop-selector" class="block text-sm font-medium text-gray-700 mb-2">Select a Shop</label>
                <select
                    id="shop-selector"
                    wire:model.live="selectedShopId"
                    wire:change="selectShop($event.target.value)"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                >
                    <option value="">-- Select a Shop --</option>
                    @foreach($userShops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->name }} ({{ $shop->owner->name }})</option>
                    @endforeach
                </select>
            </div>

            @if($selectedShop)
                <!-- Shop Details Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex -mb-px space-x-6">
                        <a href="#"
                            wire:click.prevent="setShopDetailView('overview')"
                            class="{{ $shopDetailView === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Overview
                        </a>
                        <a href="#"
                            wire:click.prevent="setShopDetailView('inventory')"
                            class="{{ $shopDetailView === 'inventory' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Inventory
                        </a>
                        <a href="#"
                            wire:click.prevent="setShopDetailView('categories')"
                            class="{{ $shopDetailView === 'categories' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Categories
                        </a>
                        <a href="#"
                            wire:click.prevent="setShopDetailView('sales')"
                            class="{{ $shopDetailView === 'sales' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Sales
                        </a>
                        <a href="#"
                            wire:click.prevent="setShopDetailView('staff')"
                            class="{{ $shopDetailView === 'staff' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Staff
                        </a>
                        <a href="#"
                            wire:click.prevent="toggleShopManager({{ $selectedShop->id }})"
                            class="{{ $shopDetailView === 'manager' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm"
                        >
                            Shop Manager
                        </a>
                    </nav>
                </div>

                <!-- Shop Details Content -->
                @if($shopDetailView === 'overview')
                    <div>
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $selectedShop->name }}</h3>
                                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                            @if($selectedShop->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Approved
                                                </span>
                                            @elseif($selectedShop->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Rejected
                                                </span>
                                            @endif

                                            @if($selectedShop->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-2">
                                                    Inactive
                                                </span>
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        <a href="#" wire:click.prevent="toggleShopManager({{ $selectedShop->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Edit Shop
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Owner</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $selectedShop->owner->name }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $selectedShop->address ?: 'Not specified' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Contact</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            Phone: {{ $selectedShop->phone ?: 'Not specified' }}<br>
                                            Email: {{ $selectedShop->email ?: 'Not specified' }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $selectedShop->description ?: 'No description provided' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Shop Stats -->
                        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Products Count -->
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4m0-10L4 7m8 4v10" />
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ $shopProducts ?? 0 }}</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-5 py-3">
                                    <div class="text-sm">
                                        <a href="#" wire:click.prevent="setShopDetailView('inventory')" class="font-medium text-blue-700 hover:text-blue-900">
                                            View inventory
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Sales Card -->
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Total Sales ({{ $shopSales['period'] ?? '30 days' }})</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">${{ number_format($shopSales['total'] ?? 0, 2) }}</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-5 py-3">
                                    <div class="text-sm">
                                        <a href="#" wire:click.prevent="setShopDetailView('sales')" class="font-medium text-blue-700 hover:text-blue-900">
                                            View sales details
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Visits Card -->
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                            <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Total Visits (Last 30 days)</dt>
                                                <dd>
                                                    <div class="text-lg font-medium text-gray-900">{{ number_format($shopVisits ?? 0) }}</div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-5 py-3">
                                    <div class="text-sm">
                                        <a href="#" class="font-medium text-blue-700 hover:text-blue-900">
                                            View analytics
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($shopDetailView === 'inventory')
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Inventory Management</h3>

                        <!-- Quick Add Product Form -->
                        <div class="bg-white p-4 mb-6 rounded-lg shadow">
                            <h4 class="font-medium text-gray-900 mb-3">Add New Product</h4>
                            <form wire:submit.prevent="saveProduct">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="productName" class="block text-sm font-medium text-gray-700">Product Name</label>
                                        <input type="text" wire:model="productName" id="productName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('productName') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="productCategoryId" class="block text-sm font-medium text-gray-700">Category</label>
                                        <select wire:model="productCategoryId" id="productCategoryId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            <option value="">Select Category</option>
                                            @forelse($shopCategories ?? [] as $catId => $category)
                                                <option value="{{ $catId }}">{{ $category['name'] }}</option>
                                                @if(!empty($category['children']))
                                                    @foreach($category['children'] as $childId => $childCategory)
                                                        <option value="{{ $childId }}">-- {{ $childCategory['name'] }}</option>
                                                    @endforeach
                                                @endif
                                            @empty
                                                <option disabled>No categories available</option>
                                            @endforelse
                                        </select>
                                        @error('productCategoryId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="productPrice" class="block text-sm font-medium text-gray-700">Price</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" wire:model="productPrice" id="productPrice" step="0.01" min="0" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        </div>
                                        @error('productPrice') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="productDescription" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea wire:model="productDescription" id="productDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                                    @error('productDescription') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label for="productStock" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                        <input type="number" wire:model="productStock" id="productStock" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                        @error('productStock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="productStatus" class="block text-sm font-medium text-gray-700">Status</label>
                                        <select wire:model="productStatus" id="productStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('productStatus') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="productUnit" class="block text-sm font-medium text-gray-700">Unit</label>
                                        <input type="text" wire:model="productUnit" id="productUnit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="piece, kg, liter, etc.">
                                        @error('productUnit') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Add Product
                                    </button>
                                </div>
                            </form>
                        </div>

                        @if(empty($productsByCategory))
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            No products found for this shop. Use the form above to add products.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                <!-- Categories sidebar -->
                                <div class="col-span-1 bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-700 mb-3">Categories</h4>
                                    <nav class="space-y-1">
                                        @foreach($productsByCategory as $catId => $category)
                                            <a href="#"
                                                wire:click.prevent="selectCategory({{ $catId }})"
                                                class="{{ $selectedCategoryId == $catId ? 'bg-blue-50 text-blue-700 border-l-4 border-blue-500' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }} flex items-center px-3 py-2 text-sm font-medium"
                                            >
                                                {{ $category['category_name'] }}
                                                <span class="ml-auto inline-block px-2 py-0.5 text-xs font-medium rounded-full bg-gray-200">
                                                    {{ count($category['products']) }}
                                                </span>
                                            </a>
                                        @endforeach
                                    </nav>
                                </div>

                                <!-- Products table -->
                                <div class="col-span-1 lg:col-span-3">
                                    @if($selectedCategoryId && isset($productsByCategory[$selectedCategoryId]))
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($productsByCategory[$selectedCategoryId]['products'] as $product)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $product->name }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($product->price, 2) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                @if($product->status === 'active')
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        Active
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                        Inactive
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="bg-gray-50 p-4 text-center rounded-md">
                                            <p class="text-gray-600">Select a category to view products</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($shopDetailView === 'categories')
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Categories Management</h3>

                        @if(empty($shopCategories))
                            <div class="bg-yellow-50 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            No categories found for this shop. Add categories from the Shop Manager tab.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($shopCategories as $categoryId => $category)
                                        <li>
                                            <div class="px-4 py-4 flex items-center sm:px-6">
                                                <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                                    <div>
                                                        <div class="flex text-sm">
                                                            <p class="font-medium text-blue-600 truncate">{{ $category['name'] }}</p>
                                                            <p class="ml-1 flex-shrink-0 font-normal text-gray-500">
                                                                ({{ $category['product_count'] }} products)
                                                            </p>
                                                        </div>
                                                        <div class="mt-2">
                                                            <div>
                                                                <p class="text-sm text-gray-500">
                                                                    {{ $category['description'] ?? 'No description' }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5">
                                                        <div class="flex justify-end space-x-3">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ ucfirst($category['status']) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-5 flex-shrink-0">
                                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </div>

                                            @if(!empty($category['children']))
                                                <div class="pl-8 pb-4">
                                                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Subcategories</h4>
                                                    <ul class="border-l-2 border-gray-200 space-y-2">
                                                        @foreach($category['children'] as $childId => $childCategory)
                                                            <li class="pl-4 -ml-px border-l-2 border-gray-200">
                                                                <div class="flex items-center justify-between text-sm">
                                                                    <div>
                                                                        <span class="font-medium text-gray-900">{{ $childCategory['name'] }}</span>
                                                                        <span class="text-gray-500">({{ $childCategory['product_count'] }} products)</span>
                                                                    </div>
                                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $childCategory['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                        {{ ucfirst($childCategory['status']) }}
                                                                    </span>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @elseif($shopDetailView === 'sales')
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sales Analytics</h3>

                        <div class="bg-yellow-50 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Sales analytics functionality is coming soon. This feature is in development.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($shopDetailView === 'staff')
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Staff Management</h3>

                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="p-4 bg-gray-50">
                                <h4 class="font-medium text-gray-700 mb-3">Add Staff Member</h4>
                                <form wire:submit.prevent="assignStaff">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- User selection -->
                                        <div>
                                            <label for="selectedUserId" class="block text-sm font-medium text-gray-700">User</label>
                                            <select id="selectedUserId" wire:model="selectedUserId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option value="">Select User</option>
                                                @foreach($users ?? [] as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->name }})</option>
                                                @endforeach
                                            </select>
                                            @error('selectedUserId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Role selection -->
                                        <div>
                                            <label for="selectedRole" class="block text-sm font-medium text-gray-700">Role</label>
                                            <select id="selectedRole" wire:model="selectedRole" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                @foreach($roles ?? ['staff', 'manager'] as $role)
                                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedRole') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Add Staff Member
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Staff list -->
                            <div class="px-4 py-4">
                                <h4 class="font-medium text-gray-700 mb-3">Current Staff</h4>
                                <div class="overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($staffMembers ?? [] as $staff)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="h-10 w-10 flex-shrink-0 rounded-full bg-gray-100 overflow-hidden">
                                                                @if($staff->photo)
                                                                    <img src="{{ Storage::url($staff->photo) }}" alt="{{ $staff->name }}" class="h-10 w-10 object-cover">
                                                                @else
                                                                    <svg class="h-10 w-10 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                                    </svg>
                                                                @endif
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-medium text-gray-900">{{ $staff->name }}</div>
                                                                <div class="text-sm text-gray-500">{{ $staff->email }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $staff->pivot->role === 'manager' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst($staff->pivot->role) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <button wire:click="confirmRemoveStaff({{ $staff->id }})" class="text-red-600 hover:text-red-900">Remove</button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                        No staff members assigned to this shop yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($shopDetailView === 'manager')
                    <div>
                        @if($showShopManager)
                            <livewire:shop.shop-manager :key="'shop-manager-' . $selectedShopId" :shopId="$selectedShopId" />
                        @else
                            <div class="text-center py-10">
                                <p class="text-gray-500">Loading shop manager...</p>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                @if($userShops->isEmpty())
                                    You don't have any shops yet. Please create a shop first.

                                    @if($currentUser->isAdmin() || $currentUser->isSuperadmin())
                                        <a href="#" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                                            Create Shop
                                        </a>
                                    @endif
                                @else
                                    Please select a shop from the dropdown above to view its details.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Staff Removal Confirmation Modal -->
    @if($staffToRemove)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Remove Staff Member
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to remove this staff member from the shop? They will no longer have access to manage this shop.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="removeStaff" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Remove
                    </button>
                    <button wire:click="$set('staffToRemove', null)" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
