<div class="bg-white py-8" id="explore">
    @csrf
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Explore Products
            </h2>
            <p class="mt-4 text-lg text-gray-600">
                Find exactly what you're looking for with our powerful search and filter options.
            </p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-gray-50 rounded-xl p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Search Box -->
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input
                            wire:model.debounce.500ms="search"
                            type="text"
                            name="search"
                            id="search"
                            class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search products..."
                        />
                        @if($search)
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" wire:click="$set('search', '')">
                                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select
                        wire:model="category"
                        id="category"
                        class="block w-full py-3 pl-3 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Range Filter -->
                <div>
                    <label for="price-range" class="block text-sm font-medium text-gray-700 mb-1">Price Range</label>
                    <select
                        wire:model="priceRange"
                        id="price-range"
                        class="block w-full py-3 pl-3 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Any Price</option>
                        <option value="0-50">Under $50</option>
                        <option value="50-100">$50 - $100</option>
                        <option value="100-200">$100 - $200</option>
                        <option value="200-500">$200 - $500</option>
                        <option value="500-1000">$500 - $1000</option>
                        <option value="1000-10000">Over $1000</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Toggle Filters -->
                <div class="flex flex-wrap gap-3">
                    <!-- In Stock Toggle -->
                    <label class="inline-flex items-center">
                        <input wire:model="inStock" type="checkbox" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:outline-none focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">In Stock Only</span>
                    </label>

                    <!-- Featured Toggle -->
                    <label class="inline-flex items-center">
                        <input wire:model="featured" type="checkbox" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:outline-none focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Featured Items</span>
                    </label>
                </div>

                <!-- Rating Filter -->
                <div class="flex flex-wrap gap-6">
                    <label class="inline-flex items-center">
                        <span class="mr-2 text-sm text-gray-700">Min Rating:</span>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <button
                                    wire:click="$set('minRating', {{ $i }})"
                                    class="text-{{ $minRating >= $i ? 'yellow' : 'gray' }}-400 hover:text-yellow-500 focus:outline-none"
                                >
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </button>
                            @endfor

                            @if($minRating > 0)
                                <button
                                    wire:click="$set('minRating', 0)"
                                    class="ml-2 text-gray-400 hover:text-gray-600 focus:outline-none"
                                >
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </label>
                </div>

                <!-- Sort Options -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            wire:click="sortBy('created_at')"
                            class="px-3 py-1 text-sm border rounded-md focus:outline-none {{ $sortBy === 'created_at' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                        >
                            Latest
                            @if($sortBy === 'created_at')
                                <span class="ml-1">
                                    @if($sortDirection === 'desc') ↓ @else ↑ @endif
                                </span>
                            @endif
                        </button>

                        <button
                            wire:click="sortBy('price')"
                            class="px-3 py-1 text-sm border rounded-md focus:outline-none {{ $sortBy === 'price' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                        >
                            Price
                            @if($sortBy === 'price')
                                <span class="ml-1">
                                    @if($sortDirection === 'desc') ↓ @else ↑ @endif
                                </span>
                            @endif
                        </button>

                        <button
                            wire:click="sortBy('name')"
                            class="px-3 py-1 text-sm border rounded-md focus:outline-none {{ $sortBy === 'name' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                        >
                            Name
                            @if($sortBy === 'name')
                                <span class="ml-1">
                                    @if($sortDirection === 'desc') ↓ @else ↑ @endif
                                </span>
                            @endif
                        </button>

                        <button
                            wire:click="sortBy('rating')"
                            class="px-3 py-1 text-sm border rounded-md focus:outline-none {{ $sortBy === 'rating' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}"
                        >
                            Rating
                            @if($sortBy === 'rating')
                                <span class="ml-1">
                                    @if($sortDirection === 'desc') ↓ @else ↑ @endif
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Active Tags -->
            @if($search || $category || $priceRange || $inStock || $featured || $minRating > 0)
                <div class="mt-6 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-gray-700">Active Filters:</span>

                    @if($search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Search: "{{ $search }}"
                            <button wire:click="$set('search', '')" class="ml-1 focus:outline-none">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($category)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Category: {{ $categories->firstWhere('id', $category)->name ?? 'Unknown' }}
                            <button wire:click="$set('category', '')" class="ml-1 focus:outline-none">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($priceRange)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Price:
                            @if($priceRange == '0-50') Under $50
                            @elseif($priceRange == '50-100') $50 - $100
                            @elseif($priceRange == '100-200') $100 - $200
                            @elseif($priceRange == '200-500') $200 - $500
                            @elseif($priceRange == '500-1000') $500 - $1000
                            @elseif($priceRange == '1000-10000') Over $1000
                            @endif
                            <button wire:click="$set('priceRange', '')" class="ml-1 focus:outline-none">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($inStock)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            In Stock Only
                            <button wire:click="$set('inStock', false)" class="ml-1 focus:outline-none">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($featured)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Featured Only
                            <button wire:click="$set('featured', false)" class="ml-1 focus:outline-none">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($minRating > 0)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Min Rating: {{ $minRating }}★
                            <button wire:click="$set('minRating', 0)" class="ml-1 focus:outline-none">
                                <svg class="h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    @endif

                    <button wire:click="resetFilters" class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none">
                        Reset All
                    </button>
                </div>
            @endif
        </div>

        <!-- Products Grid -->
        <div class="mt-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('shop.product.show', ['shopSlug' => $product->shop->slug, 'productSlug' => $product->slug]) }}">
                            <div class="relative h-48">
                                <img
                                    class="w-full h-full object-cover"
                                    src="{{ $product->image ?? 'https://via.placeholder.com/300x200?text=Product+Image' }}"
                                    alt="{{ $product->name }}"
                                    loading="lazy"
                                >

                                @if($product->is_featured)
                                    <div class="absolute top-0 left-0 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-br-lg">
                                        Featured
                                    </div>
                                @endif

                                @if($product->stock <= 0)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <span class="bg-red-600 text-white text-sm font-bold px-4 py-2 rounded-md">Out of Stock</span>
                                    </div>
                                @elseif($product->stock < 5)
                                    <div class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg">
                                        Only {{ $product->stock }} left
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="p-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </span>

                                <div class="flex items-center">
                                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-600">{{ number_format($product->rating, 1) }}</span>
                                </div>
                            </div>

                            <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600 truncate">
                                <a href="{{ route('shop.product.show', ['shopSlug' => $product->shop->slug, 'productSlug' => $product->slug]) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                {{ $product->description }}
                            </p>

                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>

                                <div class="flex space-x-2">
                                    <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>

                                    <button class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg p-10 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                        <button
                            wire:click="resetFilters"
                            class="mt-4 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Reset All Filters
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
