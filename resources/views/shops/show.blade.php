<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $shop->name }}
            </h2>
            @if(auth()->check() && (auth()->user()->id === $shop->owner_id || auth()->user()->can('manageProducts', $shop)))
                <div class="flex space-x-2">
                    <a href="{{ route('shop.categories', $shop) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Manage Categories
                    </a>
                    <a href="{{ route('shop.products', $shop) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Manage Products
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Shop Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="md:w-1/3 mb-4 md:mb-0">
                            @if($shop->image)
                                <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}" class="rounded-lg h-64 w-full object-cover">
                            @else
                                <div class="bg-gray-200 rounded-lg h-64 w-full flex items-center justify-center">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="md:w-2/3 md:pl-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $shop->name }}</h2>
                            
                            @if($shop->description)
                                <p class="text-gray-600 mb-4">{{ $shop->description }}</p>
                            @endif
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Address</div>
                                    <div class="mt-1 text-sm text-gray-900">{{ $shop->address }}</div>
                                </div>
                                
                                @if($shop->phone)
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Phone</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $shop->phone }}</div>
                                    </div>
                                @endif
                                
                                @if($shop->email)
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Email</div>
                                        <div class="mt-1 text-sm text-gray-900">{{ $shop->email }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Shop Products Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Shop Products</h3>
                    
                    <!-- Products Listing -->
                    <div x-data="{ 
                        activeCategory: 'all',
                        searchTerm: '',
                        categories: [],
                        products: @js($shop->products()->with('shopCategory')->where('status', 'active')->get()),
                        get filteredProducts() {
                            return this.products.filter(product => {
                                const matchesCategory = this.activeCategory === 'all' || product.shop_category_id == this.activeCategory;
                                const matchesSearch = this.searchTerm === '' || 
                                    product.name.toLowerCase().includes(this.searchTerm.toLowerCase());
                                return matchesCategory && matchesSearch;
                            });
                        }
                     }">
                        
                        <!-- Search and Filter Bar -->
                        <div class="mb-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                                <div class="md:w-1/3">
                                    <label for="search" class="block text-sm font-medium text-gray-700">Search Products</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <input x-model="searchTerm" type="text" name="search" id="search" 
                                               class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md" 
                                               placeholder="Search products...">
                                    </div>
                                </div>
                                
                                <div class="md:w-1/3">
                                    <label for="category" class="block text-sm font-medium text-gray-700">Filter by Category</label>
                                    <select x-model="activeCategory" id="category" 
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="all">All Categories</option>
                                        @foreach($shop->shopCategories()->where('status', 'active')->get() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Products Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <div class="bg-white overflow-hidden border rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="aspect-w-16 aspect-h-9 h-48">
                                        <img x-bind:src="product.image ? '/storage/' + product.image : '/images/placeholder-product.png'" 
                                             x-bind:alt="product.name" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-4">
                                        <h4 class="text-lg font-medium text-gray-900" x-text="product.name"></h4>
                                        
                                        <div class="flex justify-between items-center mt-2">
                                            <div>
                                                <span class="text-sm text-gray-500">Price:</span>
                                                <span class="ml-1 text-sm font-medium text-gray-900" x-text="'$' + parseFloat(product.price).toFixed(2)"></span>
                                            </div>
                                            <div x-show="product.shop_category" 
                                                 class="px-2 py-1 text-xs font-medium rounded-full" 
                                                 x-bind:class="{'bg-blue-100 text-blue-800': product.source_type === 'local', 'bg-purple-100 text-purple-800': product.source_type === 'global'}" 
                                                 x-text="product.shop_category ? product.shop_category.name : 'Uncategorized'"></div>
                                        </div>
                                        
                                        <div class="flex justify-between items-center mt-2">
                                            <div class="text-sm text-gray-500" x-text="product.stock_quantity + ' in stock'"></div>
                                            <div x-show="product.source_type" 
                                                 class="px-2 py-1 text-xs font-medium rounded-full" 
                                                 x-bind:class="{'bg-blue-100 text-blue-800': product.source_type === 'local', 'bg-purple-100 text-purple-800': product.source_type === 'global'}" 
                                                 x-text="product.source_type === 'local' ? 'Local' : 'Global'"></div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <a x-bind:href="`/shop/${product.shop_id}/product/${product.slug}`" 
                                              class="inline-flex items-center justify-center w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="filteredProducts.length === 0" class="col-span-full py-8 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                                <p class="mt-1 text-sm text-gray-500">No products match your search criteria.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>