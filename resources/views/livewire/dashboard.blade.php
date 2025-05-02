<div class="w-full h-full">
    <div class="flex h-full">
        <!-- Vertical Sidebar Navigation -->
        <div class="w-64 h-full bg-white border-r border-gray-200 shadow-lg">
            <div class="flex items-center p-4 border-b border-gray-200">
                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-indigo-600 rounded-full">
                    <span class="font-semibold text-white text-md">{{ $currentUser->initials() }}</span>
                </div>
                <div class="ml-3 overflow-hidden">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $currentUser->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $currentUser->email }}</p>
                </div>
            </div>

            <nav class="mt-4" aria-label="Sidebar">
                <div class="px-2 space-y-1">
                    @foreach($tabs as $tabKey => $tabData)
                        <button
                            wire:click="setActiveTab('{{ $tabKey }}')"
                            class="group flex items-center px-3 py-3 text-sm font-medium rounded-md w-full {{ $activeTab === $tabKey ? 'bg-indigo-100 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900' }}"
                        >
                            <svg class="mr-3 flex-shrink-0 h-5 w-5 {{ $activeTab === $tabKey ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-500' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($tabData['icon'] === 'home')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                @elseif($tabData['icon'] === 'users')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                @elseif($tabData['icon'] === 'folder')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                @elseif($tabData['icon'] === 'shopping-bag')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                @elseif($tabData['icon'] === 'store')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                @elseif($tabData['icon'] === 'clipboard-check')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                @elseif($tabData['icon'] === 'clipboard-list')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                @elseif($tabData['icon'] === 'clipboard')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                @elseif($tabData['icon'] === 'chart-bar')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                @elseif($tabData['icon'] === 'cog')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                @elseif($tabData['icon'] === 'logout')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                            {{ $tabData['name'] }}
                        </button>
                    @endforeach
                </div>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Flash Messages with animation -->
            <div class="p-4">
                @if (session()->has('message'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="flex items-center justify-between p-4 mb-6 border-l-4 border-green-500 rounded-md shadow-md bg-green-50"
                    >
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-green-700">{{ session('message') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-700 hover:text-green-900 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 5000)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="flex items-center justify-between p-4 mb-6 border-l-4 border-red-500 rounded-md shadow-md bg-red-50"
                    >
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium text-red-700">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-700 hover:text-red-900 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Tab Content -->
            <div class="flex-1 p-4 overflow-auto">
                <!-- Overview Tab Content -->
                @if($activeTab === 'overview')
                    <!-- User Details Section -->
                    <div class="p-4 mb-6 bg-white shadow sm:p-8 sm:rounded-lg">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">User Details</h2>
                                <p class="mt-1 text-sm text-gray-600">Your personal account information.</p>
                            </div>
                            <div class="flex items-center justify-center w-12 h-12 bg-indigo-600 rounded-full">
                                <span class="text-lg font-semibold text-white">{{ $currentUser->initials() }}</span>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-gray-100">
                            <dl class="divide-y divide-gray-100">
                                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Full name</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $currentUser->name }}</dd>
                                </div>
                                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Email address</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $currentUser->email }}</dd>
                                </div>
                                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Role</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 rounded-md bg-blue-50 ring-1 ring-inset ring-blue-700/10">
                                            {{ $currentUser->role->name ?? 'User' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                    <dt class="text-sm font-medium leading-6 text-gray-900">Registered</dt>
                                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $currentUser->created_at->format('F j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Shop Metrics Section -->
                    @if(isset($userShopCount) && $userShopCount > 0)
                    <div class="p-4 mb-6 bg-white shadow sm:p-8 sm:rounded-lg">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Shop Performance</h2>
                                <p class="mt-1 text-sm text-gray-600">Overview of your shop metrics and activity.</p>
                            </div>
                            <div class="flex-shrink-0 p-2 rounded-md bg-gradient-to-r from-indigo-500 to-blue-600">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Shop Stats Cards -->
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- Total Shops Card -->
                            <div class="relative px-4 pt-5 pb-12 overflow-hidden bg-white rounded-lg shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute p-3 bg-indigo-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-sm font-medium text-gray-500 truncate">Total Shops</p>
                                </dt>
                                <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ $userShopCount }}</p>
                                    @if($userShopCount > 0 && $userActiveShops < $userShopCount)
                                    <p class="flex items-baseline ml-2 text-sm font-semibold text-yellow-600">
                                        <span class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                            {{ $userShopCount - $userActiveShops }} pending
                                        </span>
                                    </p>
                                    @endif
                                    <div class="absolute inset-x-0 bottom-0 px-4 py-4 bg-gray-50 sm:px-6">
                                        <div class="text-sm">
                                            <button wire:click="setActiveTab('shops')" class="font-medium text-indigo-600 hover:text-indigo-500">View all shops<span class="sr-only"> Total Shops stats</span></button>
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <!-- Products Card -->
                            <div class="relative px-4 pt-5 pb-12 overflow-hidden bg-white rounded-lg shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute p-3 bg-indigo-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-sm font-medium text-gray-500 truncate">Shop Products</p>
                                </dt>
                                <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ $userShopProducts }}</p>
                                    <div class="absolute inset-x-0 bottom-0 px-4 py-4 bg-gray-50 sm:px-6">
                                        <div class="text-sm">
                                            <button wire:click="setActiveTab('products')" class="font-medium text-indigo-600 hover:text-indigo-500">View all products<span class="sr-only"> Total Products stats</span></button>
                                        </div>
                                    </div>
                                </dd>
                            </div>

                            <!-- Sales Card -->
                            @if(isset($shopSales) && !empty($shopSales))
                            <div class="relative px-4 pt-5 pb-12 overflow-hidden bg-white rounded-lg shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute p-3 bg-green-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-sm font-medium text-gray-500 truncate">Sales ({{ $shopSales['period'] }})</p>
                                </dt>
                                <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($shopSales['total'], 0) }}</p>
                                    <p class="flex items-baseline ml-2 text-sm font-semibold text-green-600">
                                        <svg class="self-center flex-shrink-0 w-5 h-5 text-green-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="sr-only"> Increased by </span>
                                        {{ $shopSales['growth'] }}%
                                    </p>
                                    <div class="absolute inset-x-0 bottom-0 px-4 py-4 bg-gray-50 sm:px-6">
                                        <div class="text-sm">
                                            <button class="font-medium text-indigo-600 hover:text-indigo-500">View sales report<span class="sr-only"> Sales stats</span></button>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            @endif

                            <!-- Visitors Card -->
                            @if(isset($shopVisits) && $shopVisits > 0)
                            <div class="relative px-4 pt-5 pb-12 overflow-hidden bg-white rounded-lg shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute p-3 bg-blue-500 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-sm font-medium text-gray-500 truncate">Shop Visits (30 days)</p>
                                </dt>
                                <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($shopVisits, 0) }}</p>
                                    <div class="absolute inset-x-0 bottom-0 px-4 py-4 bg-gray-50 sm:px-6">
                                        <div class="text-sm">
                                            <button class="font-medium text-indigo-600 hover:text-indigo-500">View visitor stats<span class="sr-only"> Visitors stats</span></button>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            @endif

                            <!-- NEW: Rental Products Card -->
                            @if(isset($userRentalProducts) && $userRentalProducts > 0)
                            <div class="relative px-4 pt-5 pb-12 overflow-hidden bg-white rounded-lg shadow sm:px-6 sm:pt-6 ring-2 ring-blue-100">
                                <dt>
                                    <div class="absolute p-3 bg-blue-600 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-sm font-medium text-gray-500 truncate">Rental Equipment</p>
                                </dt>
                                <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($userRentalProducts, 0) }}</p>
                                    <div class="absolute inset-x-0 bottom-0 px-4 py-4 bg-gray-50 sm:px-6">
                                        <div class="text-sm">
                                            <button wire:click="setActiveTab('rentals')" class="font-medium text-blue-600 hover:text-blue-500">Manage equipment<span class="sr-only"> Rental equipment</span></button>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            @endif

                            <!-- NEW: Active Rentals Card -->
                            @if(isset($userRentalBookings) && $userRentalBookings > 0)
                            <div class="relative px-4 pt-5 pb-12 overflow-hidden bg-white rounded-lg shadow sm:px-6 sm:pt-6 ring-2 ring-blue-100">
                                <dt>
                                    <div class="absolute p-3 bg-blue-600 rounded-md">
                                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="ml-16 text-sm font-medium text-gray-500 truncate">Active Rentals</p>
                                </dt>
                                <dd class="flex items-baseline pb-6 ml-16 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($userActiveRentals, 0) }}</p>
                                    <p class="flex items-baseline ml-2 text-sm font-semibold text-blue-600">
                                        <span class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            Active
                                        </span>
                                    </p>
                                    <div class="absolute inset-x-0 bottom-0 px-4 py-4 bg-gray-50 sm:px-6">
                                        <div class="text-sm">
                                            <button wire:click="setActiveTab('rentals')" class="font-medium text-blue-600 hover:text-blue-500">View rental bookings<span class="sr-only"> Active rentals</span></button>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            @endif
                        </div>

                        @if($currentUser->isAdmin() || $currentUser->isSuperadmin())
                        <!-- Recent Shop Activities Section -->
                        <div class="mt-8">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Recent Shop Activities</h3>
                            <dl class="grid grid-cols-1 gap-5 mt-5 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach($recentShopActivities as $shop)
                                <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow sm:p-6">
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ $shop->name }}</dt>
                                    <dd class="flex items-center mt-1 font-semibold text-gray-900 text-md">
                                        <span class="inline-flex items-center rounded-md bg-{{ $shop->status === 'active' ? 'green' : ($shop->status === 'pending' ? 'yellow' : 'gray') }}-50 px-2 py-1 text-xs font-medium text-{{ $shop->status === 'active' ? 'green' : ($shop->status === 'pending' ? 'yellow' : 'gray') }}-700 mr-2">
                                            {{ ucfirst($shop->status) }}
                                        </span>
                                        <span class="text-sm font-normal text-gray-500">
                                            Created {{ $shop->created_at->diffForHumans() }} by {{ $shop->owner->name }}
                                        </span>
                                    </dd>
                                </div>
                                @endforeach
                            </dl>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Dashboard Stats -->
                    @if($currentUser->isAdmin() || $currentUser->isSuperadmin())
                    <div class="p-4 mb-6 bg-white shadow sm:p-8 sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900">System Statistics</h2>
                        <p class="mt-1 text-sm text-gray-600">System overview and statistics.</p>

                        <div class="grid grid-cols-1 gap-5 mt-5 sm:grid-cols-4">
                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalUsers }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Roles</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalRoles }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ is_array($shopCategories) ? count($shopCategories) : $shopCategories->count() }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Global Products</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalProducts }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Shops</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $pendingShops }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Product Suggestions</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $productSuggestions }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Your Role</dt>
                                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ $currentUser->role->name ?? 'User' }}</dd>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($currentUser->isSuperadmin())
                    <div class="p-4 mb-6 bg-white shadow sm:p-8 sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900">Statistics</h2>
                        <p class="mt-1 text-sm text-gray-600">System overview and statistics.</p>

                        <div class="grid grid-cols-1 gap-5 mt-5 sm:grid-cols-3">
                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalUsers }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Roles</dt>
                                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalRoles }}</dd>
                            </div>

                            <div class="px-4 py-5 overflow-hidden bg-white rounded-lg shadow">
                                <dt class="text-sm font-medium text-gray-500 truncate">Your Role</dt>
                                <dd class="mt-1 text-xl font-semibold tracking-tight text-gray-900">{{ $currentUser->role->name ?? 'User' }}</dd>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($currentUser->isAdmin() || $currentUser->isSuperadmin())
                    <!-- Recent Users -->
                    <div class="p-4 mb-6 bg-white shadow sm:p-8 sm:rounded-lg">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">Recent Users</h2>
                                <p class="mt-1 text-sm text-gray-600">Recently registered users in the system.</p>
                            </div>
                            <button
                                wire:click="setActiveTab('users')"
                                class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                            >
                                View All Users
                            </button>
                        </div>

                        <div class="mt-6 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Name</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Role</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($recentUsers as $user)
                                    <tr>
                                        <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 whitespace-nowrap sm:pl-0">{{ $user->name }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-700 rounded-md bg-blue-50 ring-1 ring-inset ring-blue-700/10">
                                                {{ $user->role->name ?? 'User' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $user->created_at->format('M j, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($currentUser->isSuperadmin())
                    <!-- Users by Role Distribution -->
                    <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900">Users by Role</h2>
                        <p class="mt-1 text-sm text-gray-600">Distribution of users across different roles.</p>

                        <div class="mt-6">
                            @foreach ($usersByRole as $role)
                            <div class="mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900">{{ $role->name }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $role->users_count }}</span>
                                </div>
                                <div class="w-full h-2 mt-1 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-indigo-600 rounded-full" style="width: {{ ($role->users_count / max(1, $totalUsers)) * 100 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif

                <!-- Users Tab Content -->
                @if($activeTab === 'users' && ($currentUser->isAdmin() || $currentUser->isSuperadmin()))
                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                        <!-- User Form Card -->
                        <div class="lg:col-span-1">
                            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-lg">
                                <div class="flex items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <div class="flex-shrink-0 p-2 bg-indigo-100 rounded-md">
                                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $editingUserId ? 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z' : 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z' }}"></path>
                                        </svg>
                                    </div>
                                    <h2 class="ml-3 text-lg font-semibold text-gray-900">
                                        {{ $editingUserId ? 'Edit User' : 'Create New User' }}
                                    </h2>
                                </div>

                                <div class="p-6">
                                    <form wire:submit.prevent="save" class="space-y-6">
                                        <div>
                                            <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Name</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <input
                                                    wire:model="name"
                                                    id="name"
                                                    type="text"
                                                    class="block w-full pl-10 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                    placeholder="Full Name"
                                                >
                                            </div>
                                            @error('name')
                                                <span class="flex items-center mt-1 text-xs text-red-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                    </svg>
                                                </div>
                                                <input
                                                    wire:model="email"
                                                    id="email"
                                                    type="email"
                                                    class="block w-full pl-10 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                    placeholder="Email Address"
                                                >
                                            </div>
                                            @error('email')
                                                <span class="flex items-center mt-1 text-xs text-red-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">
                                                {{ $editingUserId ? 'Password (Leave blank to keep current)' : 'Password' }}
                                            </label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </div>
                                                <input
                                                    wire:model="password"
                                                    id="password"
                                                    type="password"
                                                    class="block w-full pl-10 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                    placeholder="Password"
                                                >
                                            </div>
                                            @error('password')
                                                <span class="flex items-center mt-1 text-xs text-red-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="role" class="block mb-1 text-sm font-medium text-gray-700">Role</label>
                                            <div class="relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                </div>
                                                <select
                                                    wire:model="role_id"
                                                    id="role"
                                                    class="block w-full pl-10 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                >
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('role_id')
                                                <span class="flex items-center mt-1 text-xs text-red-600">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="flex justify-end pt-4 space-x-3">
                                            @if($editingUserId)
                                                <button
                                                    type="button"
                                                    wire:click="cancel"
                                                    class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                >
                                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Cancel
                                                </button>
                                            @endif
                                            <button
                                                type="submit"
                                                class="flex items-center px-4 py-2 text-sm text-white transition-all duration-150 bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            >
                                                <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $editingUserId ? 'Update User' : 'Create User' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Users Table Card -->
                        <div class="lg:col-span-2">
                            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-lg">
                                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 p-2 bg-indigo-100 rounded-md">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="ml-3 text-lg font-semibold text-gray-900">Users</h3>
                                    </div>
                                    <span class="flex items-center px-3 py-1 text-xs font-medium text-indigo-800 bg-indigo-100 rounded-full">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        Total: {{ $users->total() }}
                                    </span>
                                </div>
                                <div class="p-6">
                                    <div class="relative overflow-x-auto">
                                        <table class="w-full text-sm text-left">
                                            <thead class="text-xs uppercase bg-gray-100 rounded-t-lg">
                                                <tr>
                                                    <th scope="col" class="px-4 py-3 font-semibold text-gray-800 rounded-tl-lg">#</th>
                                                    <th scope="col" class="px-4 py-3 font-semibold text-gray-800">Name</th>
                                                    <th scope="col" class="px-4 py-3 font-semibold text-gray-800">Email</th>
                                                    <th scope="col" class="px-4 py-3 font-semibold text-gray-800">Role</th>
                                                    <th scope="col" class="px-4 py-3 font-semibold text-gray-800 rounded-tr-lg">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($users as $user)
                                                    <tr class="border-b {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100 transition-colors duration-150">
                                                        <td class="px-4 py-3 font-semibold text-gray-900">
                                                            {{ $user->id }}
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center">
                                                                <div class="flex items-center justify-center font-semibold text-white rounded-full shadow-md h-9 w-9 bg-gradient-to-br from-indigo-500 to-purple-600">
                                                                    {{ substr($user->name, 0, 1) }}
                                                                </div>
                                                                <div class="ml-3">
                                                                    <span class="font-medium text-gray-800">{{ $user->name }}</span>
                                                                    <p class="text-xs text-gray-500 mt-0.5">User #{{ $user->id }}</p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-gray-700">
                                                            <span class="flex items-center">
                                                                <svg class="h-4 w-4 text-gray-400 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                                </svg>
                                                                {{ $user->email }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{
                                                                $user->role->name == 'superadmin' ? 'bg-purple-100 text-purple-800' :
                                                                ($user->role->name == 'admin' ? 'bg-blue-100 text-blue-800' :
                                                                ($user->role->name == 'manager' ? 'bg-green-100 text-green-800' :
                                                                'bg-gray-100 text-gray-800'))
                                                            }} flex items-center w-fit">
                                                                <span class="h-1.5 w-1.5 rounded-full {{
                                                                    $user->role->name == 'superadmin' ? 'bg-purple-600' :
                                                                    ($user->role->name == 'admin' ? 'bg-blue-600' :
                                                                    ($user->role->name == 'manager' ? 'bg-green-600' :
                                                                    'bg-gray-600'))
                                                                }} mr-1.5"></span>
                                                                {{ ucfirst($user->role->name) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex space-x-3">
                                                                <button
                                                                    wire:click="edit({{ $user->id }})"
                                                                    class="flex items-center text-sm font-medium text-blue-600 transition duration-150 hover:text-blue-900"
                                                                >
                                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                    Edit
                                                                </button>

                                                                @if(auth()->user()->isSuperadmin() && auth()->id() !== $user->id)
                                                                    <button
                                                                        wire:click="confirmDelete({{ $user->id }})"
                                                                        class="flex items-center text-sm font-medium text-red-600 transition duration-150 hover:text-red-900"
                                                                    >
                                                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Delete
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                                @if($users->count() === 0)
                                                    <tr>
                                                        <td colspan="5" class="py-6 text-center text-gray-500">
                                                            <div class="flex flex-col items-center justify-center">
                                                                <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <span class="text-sm font-medium">No users found</span>
                                                                <p class="mt-1 text-xs">Create your first user using the form</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-6">
                                        {{ $users->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Categories Tab Content -->
                @if($activeTab === 'categories' && ($currentUser->isAdmin() || $currentUser->isSuperadmin()))
                    <div>
                        <livewire:admin.category-management />
                    </div>
                @endif

                <!-- Products Tab Content -->
                @if($activeTab === 'products' && ($currentUser->isAdmin() || $currentUser->isSuperadmin()))
                    <div>
                        <livewire:admin.product-management />
                    </div>
                @endif

                <!-- Shop Approvals Tab Content -->
                @if($activeTab === 'shops' && ($currentUser->isAdmin() || $currentUser->isSuperadmin()))
                    <div>
                        <livewire:admin.shop-approval />
                    </div>
                @endif

                <!-- Product Suggestions Tab Content -->
                @if($activeTab === 'product_suggestions' && ($currentUser->isAdmin() || $currentUser->isSuperadmin()))
                    <div>
                        <livewire:admin.product-suggestion-approval />
                    </div>
                @endif

                <!-- Activity Logs Tab Content (SuperAdmin only) -->
                @if($activeTab === 'activity_logs' && $currentUser->isSuperadmin())
                    <div>
                        <livewire:admin.activity-logger />
                    </div>
                @endif

                <!-- Shop Details Tab Content -->
                @if($activeTab === 'shop_details')
                    <div>
                        <livewire:shop.shop-details />
                    </div>
                @endif

                <!-- NEW: Rental Management Tab Content -->
                @if($activeTab === 'rentals')
                    <div>
                        <div class="mb-6 space-y-4 sm:p-2">
                            <div class="flex items-center justify-between">
                                <h1 class="text-2xl font-bold text-gray-900">Rental Management</h1>
                                <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add New Rental Product
                                </button>
                            </div>
                        </div>

                        <!-- Rental Management Tabs -->
                        <div x-data="{ activeTab: 'products' }" class="bg-white rounded-lg shadow">
                            <div class="border-b border-gray-200">
                                <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                                    <button
                                        @click="activeTab = 'products'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'products', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'products' }"
                                        class="py-4 px-1 border-b-2 font-medium text-sm"
                                    >
                                        Rental Products
                                    </button>
                                    <button
                                        @click="activeTab = 'bookings'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'bookings', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'bookings' }"
                                        class="py-4 px-1 border-b-2 font-medium text-sm"
                                    >
                                        Bookings
                                    </button>
                                    <button
                                        @click="activeTab = 'active'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'active', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'active' }"
                                        class="py-4 px-1 border-b-2 font-medium text-sm"
                                    >
                                        Active Rentals
                                    </button>
                                    <button
                                        @click="activeTab = 'customers'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'customers', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'customers' }"
                                        class="py-4 px-1 border-b-2 font-medium text-sm"
                                    >
                                        Customers
                                    </button>
                                    <button
                                        @click="activeTab = 'reports'"
                                        :class="{ 'border-blue-500 text-blue-600': activeTab === 'reports', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reports' }"
                                        class="py-4 px-1 border-b-2 font-medium text-sm"
                                    >
                                        Reports
                                    </button>
                                </nav>
                            </div>

                            <!-- Rental Products Tab -->
                            <div x-show="activeTab === 'products'" class="p-6">
                                <!-- Filter and Search Controls -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="relative">
                                            <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                <option value="">All Categories</option>
                                                <option value="tools">Tools</option>
                                                <option value="construction">Construction Equipment</option>
                                                <option value="party">Party & Event</option>
                                                <option value="outdoor">Outdoor</option>
                                            </select>
                                        </div>
                                        <div class="relative">
                                            <select class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                <option value="all">All Status</option>
                                                <option value="available">Available</option>
                                                <option value="rented">Rented</option>
                                                <option value="maintenance">Maintenance</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search rental products">
                                    </div>
                                </div>

                                <!-- Rental Products Table -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Units</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <!-- Sample Rental Product 1 -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">Power Drill XL-500</div>
                                                            <div class="text-sm text-gray-500">SKU: DRILL-XL500</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Power Tools
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Available
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">$25.00 / day</div>
                                                    <div class="text-sm text-gray-500">$120.00 / week</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    5 units
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                                                        <button class="text-green-600 hover:text-green-900">Create Booking</button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Sample Rental Product 2 -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">Cement Mixer Pro-300</div>
                                                            <div class="text-sm text-gray-500">SKU: MIXER-PRO300</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Construction
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Rented
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">$45.00 / day</div>
                                                    <div class="text-sm text-gray-500">$180.00 / week</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    2 units (0 available)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                                                        <button class="text-gray-400 cursor-not-allowed">Create Booking</button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Sample Rental Product 3 -->
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-md flex items-center justify-center">
                                                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">Party Tent 20x30</div>
                                                            <div class="text-sm text-gray-500">SKU: TENT-2030</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Party & Event
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        Maintenance
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">$150.00 / day</div>
                                                    <div class="text-sm text-gray-500">$500.00 / week</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    3 units (1 available)
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                                                        <button class="text-green-600 hover:text-green-900">Create Booking</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6 mt-4">
                                    <div class="flex justify-between flex-1 sm:hidden">
                                        <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Previous
                                        </a>
                                        <a href="#" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Next
                                        </a>
                                    </div>
                                    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">12</span> results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="inline-flex -space-x-px rounded-md shadow-sm isolate" aria-label="Pagination">
                                                <a href="#" class="relative inline-flex items-center px-2 py-2 text-gray-400 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Previous</span>
                                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <a href="#" aria-current="page" class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 border border-blue-600 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">1</a>
                                                <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a>
                                                <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 bg-white border border-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">3</a>
                                                <a href="#" class="relative inline-flex items-center px-2 py-2 text-gray-400 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                                    <span class="sr-only">Next</span>
                                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bookings Tab -->
                            <div x-show="activeTab === 'bookings'" class="p-6">
                                <div class="text-center py-12">
                                    <h3 class="text-lg font-medium text-gray-900">Rental Bookings Management</h3>
                                    <p class="mt-2 text-sm text-gray-500">View and manage all rental bookings here.</p>
                                    <p class="mt-2 text-sm text-gray-500">This tab will be implemented with rental booking functionality.</p>
                                </div>
                            </div>

                            <!-- Active Rentals Tab -->
                            <div x-show="activeTab === 'active'" class="p-6">
                                <div class="text-center py-12">
                                    <h3 class="text-lg font-medium text-gray-900">Active Rentals Management</h3>
                                    <p class="mt-2 text-sm text-gray-500">View and manage currently active rentals here.</p>
                                    <p class="mt-2 text-sm text-gray-500">This tab will be implemented with active rental tracking functionality.</p>
                                </div>
                            </div>

                            <!-- Customers Tab -->
                            <div x-show="activeTab === 'customers'" class="p-6">
                                <div class="text-center py-12">
                                    <h3 class="text-lg font-medium text-gray-900">Rental Customers Management</h3>
                                    <p class="mt-2 text-sm text-gray-500">View and manage rental customer profiles here.</p>
                                    <p class="mt-2 text-sm text-gray-500">This tab will be implemented with customer management functionality.</p>
                                </div>
                            </div>

                            <!-- Reports Tab -->
                            <div x-show="activeTab === 'reports'" class="p-6">
                                <div class="text-center py-12">
                                    <h3 class="text-lg font-medium text-gray-900">Rental Reports</h3>
                                    <p class="mt-2 text-sm text-gray-500">View rental performance reports here.</p>
                                    <p class="mt-2 text-sm text-gray-500">This tab will be implemented with reporting and analytics functionality.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
            x-data="{}"
            x-init="$nextTick(() => { document.body.classList.add('overflow-y-hidden'); });"
            x-on:keydown.escape.window="$wire.cancelDelete()"
        >
            <div
                class="w-full max-w-md overflow-hidden transition-all duration-300 ease-out transform bg-white rounded-lg shadow-xl"
                x-on:click.away="$wire.cancelDelete()"
                @keydown.escape.window="$wire.cancelDelete()"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="flex items-center text-lg font-medium text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Delete User
                        </h3>
                        <button wire:click="cancelDelete" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="px-6 py-5">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>

                        <div class="mt-3 sm:mt-0 sm:ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Delete User</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this user? This action cannot be undone.
                                    All data associated with this user will be permanently removed.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end px-6 py-4 space-x-3 border-t border-gray-200 bg-gray-50">
                    <button
                        wire:click="cancelDelete"
                        class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="delete"
                        class="flex items-center px-4 py-2 text-sm text-white transition-all duration-150 bg-red-600 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Sign-out Confirmation Modal -->
    @if($showSignoutConfirm)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
            x-data="{}"
            x-init="$nextTick(() => { document.body.classList.add('overflow-y-hidden'); });"
            x-on:keydown.escape.window="$wire.cancelSignout()"
        >
            <div
                class="w-full max-w-md overflow-hidden transition-all duration-300 ease-out transform bg-white rounded-lg shadow-xl"
                x-on:click.away="$wire.cancelSignout()"
                @keydown.escape.window="$wire.cancelSignout()"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <h3 class="flex items-center text-lg font-medium text-gray-900">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign Out
                        </h3>
                        <button wire:click="cancelSignout" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Content -->
                <div class="px-6 py-5">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-indigo-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </div>

                        <div class="mt-3 sm:mt-0 sm:ml-4">
                            <h3 class="text-lg font-medium text-gray-900">Sign Out</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to sign out of your account?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end px-6 py-4 space-x-3 border-t border-gray-200 bg-gray-50">
                    <button
                        wire:click="cancelSignout"
                        class="px-4 py-2 text-sm font-medium text-gray-700 transition-all duration-150 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="signOut"
                        class="flex items-center px-4 py-2 text-sm text-white transition-all duration-150 bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Sign Out
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
