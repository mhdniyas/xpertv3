<div class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $shop->name }}</h1>
                    <span class="ml-3 px-2 py-1 text-xs rounded-full {{ $shop->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $shop->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $shop->status == 'approved' ? 'bg-green-100 text-green-800' : ($shop->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($shop->status) }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('shop.details', ['shopId' => $shop->id]) }}" class="text-sm text-gray-500 hover:text-gray-700">
                        <span class="mr-1">←</span> Back to Shop
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-700">Shop Management</h2>
                    </div>
                    <nav class="py-2">
                        @foreach($views as $key => $label)
                            <a 
                                wire:click="changeView('{{ $key }}')" 
                                class="flex items-center px-4 py-3 {{ $currentView === $key ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50' }} cursor-pointer transition-colors duration-150"
                            >
                                @switch($key)
                                    @case('overview')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        @break
                                    @case('staff')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        @break
                                    @case('categories')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        @break
                                    @case('products')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        @break
                                    @case('orders')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        @break
                                    @case('analytics')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        @break
                                    @case('settings')
                                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        @break
                                @endswitch
                                {{ $label }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                <!-- Shop Owner Info -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-700">Shop Owner</h2>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $shop->owner->name }}</p>
                                <p class="text-xs text-gray-500">{{ $shop->owner->email }}</p>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">
                            <p class="mb-1">
                                <span class="font-medium">Created On:</span> 
                                {{ $shop->created_at->format('M d, Y') }}
                            </p>
                            <p>
                                <span class="font-medium">Last Updated:</span> 
                                {{ $shop->updated_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                @if($currentView === 'overview')
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        <!-- Products Stats -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-md bg-indigo-100 p-3">
                                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Products</dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] }}</div>
                                                <div class="ml-2 text-sm text-green-600">
                                                    {{ $stats['active_products'] }} active
                                                </div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a wire:click="changeView('products')" class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                        Manage products
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Stats -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-md bg-green-100 p-3">
                                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_categories'] }}</div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a wire:click="changeView('categories')" class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                        Manage categories
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Staff Stats -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-md bg-blue-100 p-3">
                                            <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Staff Members</dt>
                                            <dd class="flex items-baseline">
                                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['staff_count'] }}</div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="text-sm">
                                    <a wire:click="changeView('staff')" class="font-medium text-indigo-600 hover:text-indigo-500 cursor-pointer">
                                        Manage staff
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Recent Activity / Orders -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Orders</h3>
                            </div>
                            <div class="p-6">
                                @if($stats['recent_orders'] > 0)
                                    <p class="text-gray-500 mb-6">You have {{ $stats['recent_orders'] }} recent orders to process.</p>
                                    <div class="flex justify-center">
                                        <a wire:click="changeView('orders')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none cursor-pointer">
                                            View All Orders
                                        </a>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center py-6">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <p class="text-gray-500">No recent orders.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Revenue Summary -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Revenue Summary</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Today</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">${{ $stats['revenue_today'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">This Month</p>
                                        <p class="mt-1 text-2xl font-semibold text-gray-900">${{ $stats['revenue_month'] }}</p>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-center">
                                    <a wire:click="changeView('analytics')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none cursor-pointer">
                                        View Analytics
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inventory Alerts -->
                    <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Inventory Alerts</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center p-4 bg-yellow-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-yellow-800">Low Stock Items</h4>
                                        <p class="mt-1 text-yellow-700">{{ $stats['low_stock_products'] }} products with low stock</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 bg-red-50 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-medium text-red-800">Out of Stock</h4>
                                        <p class="mt-1 text-red-700">{{ $stats['out_of_stock'] }} products out of stock</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-center">
                                <a wire:click="changeView('products')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none cursor-pointer">
                                    Manage Inventory
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif($currentView === 'staff')
                    <!-- Staff Management Component -->
                    <livewire:shop.manager.staff-manager :shopId="$shopId" />
                @elseif($currentView === 'categories')
                    <!-- Categories Management Component -->
                    <livewire:shop.manager.category-manager :shopId="$shopId" />
                @elseif($currentView === 'products')
                    <!-- Coming Soon Placeholder -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">Product Management</h3>
                            <p class="text-gray-500 text-center max-w-md">
                                This feature is coming soon. Check back later for product management capabilities.
                            </p>
                        </div>
                    </div>
                @elseif($currentView === 'orders' || $currentView === 'analytics' || $currentView === 'settings')
                    <!-- Coming Soon Placeholder -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex flex-col items-center justify-center py-12">
                            <svg class="w-16 h-16 text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">{{ $views[$currentView] }}</h3>
                            <p class="text-gray-500 text-center max-w-md">
                                This feature is coming soon. Check back later for enhanced shop management capabilities.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>