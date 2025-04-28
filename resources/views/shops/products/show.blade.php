<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <a href="{{ route('shop.show', $shop->slug) }}" class="font-semibold text-lg text-gray-600 hover:text-gray-900">
                    {{ $shop->name }}
                </a>
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $product->name }}
                </h2>
            </div>
            
            @if(auth()->check() && (auth()->user()->id === $shop->owner_id || auth()->user()->can('update', $product)))
                <a href="{{ route('shop.products', $shop) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Manage Products
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row">
                        <!-- Product Image -->
                        <div class="md:w-1/2 md:pr-8">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="rounded-lg w-full object-cover mb-4 md:mb-0" style="max-height: 400px;">
                            @else
                                <div class="bg-gray-200 rounded-lg w-full flex items-center justify-center mb-4 md:mb-0" style="height: 400px;">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="md:w-1/2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                                    
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->source_type === 'local' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ ucfirst($product->source_type) }} Product
                                        </span>
                                        
                                        @if($product->shopCategory)
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $product->shopCategory->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($product->price, 2) }}
                                </div>
                            </div>
                            
                            <div class="border-t border-b border-gray-200 py-4 my-6">
                                <div class="grid grid-cols-2 gap-4">
                                    @if($product->unit)
                                        <div>
                                            <div class="text-sm font-medium text-gray-500">Unit</div>
                                            <div class="mt-1 text-sm text-gray-900">{{ $product->unit }}</div>
                                        </div>
                                    @endif
                                    
                                    <div>
                                        <div class="text-sm font-medium text-gray-500">Availability</div>
                                        <div class="mt-1 text-sm">
                                            @if($product->stock_quantity > 0)
                                                <span class="text-green-600">In Stock ({{ $product->stock_quantity }} available)</span>
                                            @else
                                                <span class="text-red-600">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- If this is a global product, display original information -->
                                    @if($product->source_type === 'global' && $product->globalProduct)
                                        <div class="col-span-2 mt-4">
                                            <div class="text-sm font-medium text-gray-500">Original Global Product</div>
                                            <div class="mt-1 text-sm text-gray-900">{{ $product->globalProduct->name }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Add to Cart / Purchase Form would go here -->
                            <div class="mt-6">
                                <button type="button" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add to Cart
                                </button>
                                
                                <div class="mt-4 text-center text-sm text-gray-500">
                                    <p>This is a demo product. The "Add to Cart" functionality is not implemented.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other Products Section -->
                    @if($relatedProducts->count() > 0)
                        <div class="mt-16">
                            <h2 class="text-xl font-medium text-gray-900">Other Products from {{ $shop->name }}</h2>
                            
                            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                                @foreach($relatedProducts as $related)
                                    <div class="group relative">
                                        <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                            @if($related->image)
                                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center">
                                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-4 flex justify-between">
                                            <div>
                                                <h3 class="text-sm text-gray-700">
                                                    <a href="{{ route('shop.product.show', ['shopSlug' => $shop->slug, 'productSlug' => $related->slug]) }}">
                                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                                        {{ $related->name }}
                                                    </a>
                                                </h3>
                                                @if($related->shopCategory)
                                                    <p class="mt-1 text-sm text-gray-500">{{ $related->shopCategory->name }}</p>
                                                @endif
                                            </div>
                                            <p class="text-sm font-medium text-gray-900">{{ number_format($related->price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>