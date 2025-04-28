<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Marketplace Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Shop Marketplace</h2>
            <p class="text-gray-600">Discover local shops and browse their products.</p>
        </div>

        <!-- Filters -->
        <div class="mb-8">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Shops</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input wire:model.debounce.300ms="searchTerm" type="text" name="search" id="search" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Search by name or description...">
                        </div>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Filter by Location</label>
                        <select wire:model="locationFilter" id="location" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Locations</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}">{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($shops as $shop)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-md transition-shadow duration-300">
                    <a href="{{ route('shop.show', $shop->slug) }}" class="block">
                        @if($shop->image)
                            <img src="{{ asset('storage/' . $shop->image) }}" alt="{{ $shop->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $shop->name }}</h3>

                            <div class="flex items-center mb-2">
                                @if($shop->address)
                                    <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-600">{{ $shop->address }}</span>
                                @endif
                            </div>

                            @if($shop->description)
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $shop->description }}</p>
                            @endif

                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $shop->products()->where('status', 'active')->count() }} products</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Open
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
