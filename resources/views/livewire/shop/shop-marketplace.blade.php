<div>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Marketplace Header -->
        <div class="mb-6">
            <h2 class="mb-4 text-2xl font-bold text-gray-900">Shop Marketplace</h2>
            <p class="text-gray-600">Discover local shops and browse their products.</p>
        </div>

        <!-- Filters -->
        <div class="mb-8">
            <div class="p-4 bg-white rounded-lg shadow-lg card">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block mb-1 text-sm font-medium text-gray-700">Search Shops</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.debounce.300ms="searchTerm" type="text" name="search" id="search" class="block w-full pl-10 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search by name or description...">
                        </div>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label for="location" class="block mb-1 text-sm font-medium text-gray-700">Filter by Location</label>
                        <select wire:model="locationFilter" id="location" class="block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Locations</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}">{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sortBy" class="block mb-1 text-sm font-medium text-gray-700">Sort By</label>
                        <div class="flex items-center space-x-2">
                            <button wire:click="sortBy('name')" class="px-3 py-2 rounded-md text-sm font-medium {{ $sortField === 'name' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:text-gray-700' }} focus:outline-none">
                                Name
                                @if($sortField === 'name')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                            <button wire:click="sortBy('created_at')" class="px-3 py-2 rounded-md text-sm font-medium {{ $sortField === 'created_at' ? 'bg-gray-100 text-gray-900' : 'text-gray-500 hover:text-gray-700' }} focus:outline-none">
                                Newest
                                @if($sortField === 'created_at')
                                    <span class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop List -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($shops as $shop)
                <div class="overflow-hidden transition-shadow duration-300 bg-white rounded-lg shadow-lg card hover:shadow-xl">
                    <a href="{{ route('shop.show', $shop->slug) }}" class="block">
                        @if($shop->image)
                            <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}" class="object-cover w-full h-48">
                        @else
                            <div class="flex items-center justify-center w-full h-48 bg-gray-200">
                                <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="mb-1 text-lg font-semibold text-gray-900">{{ $shop->name }}</h3>

                            <div class="flex items-center mb-2">
                                @if($shop->address)
                                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-600">{{ $shop->address }}</span>
                                @endif
                            </div>

                            @if($shop->description)
                                <p class="mb-3 text-sm text-gray-600 line-clamp-2">{{ $shop->description }}</p>
                            @endif

                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $shop->products()->where('status', 'active')->count() }} products</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Open
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="py-12 text-center col-span-full">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No shops found</h3>
                    <p class="mt-1 text-sm text-gray-500">No shops match your search criteria or there are no approved shops yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $shops->links() }}
        </div>
    </div>
</div>
