<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Shop Approvals</h3>
                <span class="text-base font-normal text-gray-500 dark:text-gray-400">Review and manage shop applications</span>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Error Message -->
        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter options -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="w-full md:w-1/3">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input wire:model.debounce.300ms="search" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="Search shops">
            </div>
            <div class="w-full md:w-1/3">
                <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select wire:model="status_filter" id="status_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
            <div class="flex items-end">
                <button wire:click="resetFilters" class="p-2.5 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600">
                    <span class="sr-only">Reset filters</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Shops Table -->
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto rounded-lg">
                <div class="align-middle inline-block min-w-full">
                    <div class="shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Shop Name
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Owner
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Active
                                    </th>
                                    <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($shops as $shop)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900 dark:text-white">
                                            <div class="font-semibold">{{ $shop->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $shop->email }}</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $shop->owner ? $shop->owner->name : 'Unknown' }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal">
                                            <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                                {{ $shop->status === 'pending' ? 'text-yellow-700 bg-yellow-100 dark:bg-yellow-700 dark:text-yellow-100' : '' }}
                                                {{ $shop->status === 'approved' ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : '' }}
                                                {{ $shop->status === 'rejected' ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : '' }}">
                                                {{ ucfirst($shop->status) }}
                                            </span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal">
                                            <div class="flex items-center">
                                                <div class="h-2.5 w-2.5 rounded-full mr-2 {{ $shop->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                                <span>{{ $shop->is_active ? 'Active' : 'Inactive' }}</span>
                                                <button wire:click="toggleActive({{ $shop->id }})" class="ml-2 text-gray-500 dark:text-gray-400">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                        <path fill-rule="evenodd" d="M2 13a2 2 0 002 2h12a2 2 0 002-2V7a1 1 0 00-1-1h-1.586l-2-2H6a1 1 0 00-1 1v9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap space-x-2 text-right text-sm font-medium">
                                            <button wire:click="openViewModal({{ $shop->id }})" class="text-blue-600 dark:text-blue-500 hover:underline">
                                                View
                                            </button>
                                            @if($shop->status === 'pending')
                                                <button wire:click="approveShop({{ $shop->id }})" class="text-green-600 dark:text-green-500 hover:underline">
                                                    Approve
                                                </button>
                                                <button wire:click="openRejectModal({{ $shop->id }})" class="text-red-600 dark:text-red-500 hover:underline">
                                                    Reject
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                            No shops found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $shops->links() }}
        </div>

        <!-- Shop View Modal -->
        @if($isViewOpen && $selectedShop)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="viewModal">
                <div class="relative top-20 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $selectedShop->name }} Details
                        </h3>
                        <button wire:click="closeViewModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-4 space-y-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Shop Information</h4>
                            <div class="mt-2 grid grid-cols-1 gap-4">
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Name</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->name }}</div>
                                </div>
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Description</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->description ?? 'No description provided' }}</div>
                                </div>
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Email</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->email ?? 'N/A' }}</div>
                                </div>
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Phone</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->phone ?? 'N/A' }}</div>
                                </div>
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Address</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->address ?? 'N/A' }}</div>
                                </div>
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Created At</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->created_at->format('M d, Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Owner Information</h4>
                            <div class="mt-2 grid grid-cols-1 gap-4">
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Owner Name</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->owner ? $selectedShop->owner->name : 'Unknown' }}</div>
                                </div>
                                <div class="col-span-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Owner Email</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedShop->owner ? $selectedShop->owner->email : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($selectedShop->status === 'rejected' && $selectedShop->rejection_reason)
                            <div>
                                <h4 class="text-sm font-medium text-red-500">Rejection Reason</h4>
                                <div class="mt-2 p-2 border border-red-200 rounded bg-red-50 dark:bg-red-900 dark:border-red-700">
                                    <p class="text-sm text-red-700 dark:text-red-200">{{ $selectedShop->rejection_reason }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end mt-6">
                        <button wire:click="closeViewModal()" type="button" class="text-gray-500 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                            Close
                        </button>

                        @if($selectedShop->status === 'pending')
                            <button wire:click="approveShop({{ $selectedShop->id }})" type="button" class="ml-2 text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-500 dark:hover:bg-green-600">
                                Approve
                            </button>
                            <button wire:click="openRejectModal({{ $selectedShop->id }})" type="button" class="ml-2 text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-500 dark:hover:bg-red-600">
                                Reject
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Shop Rejection Modal -->
        @if($isRejectOpen)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="rejectModal">
                <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Reject Shop</h3>
                        <button wire:click="closeRejectModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="rejectShop">
                        <div class="mb-4">
                            <label for="rejection_reason" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rejection Reason</label>
                            <textarea wire:model="rejection_reason" id="rejection_reason" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Provide a reason for rejection" required></textarea>
                            @error('rejection_reason') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end mt-6">
                            <button wire:click="closeRejectModal()" type="button" class="mr-2 text-gray-500 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-500 dark:hover:bg-red-600">
                                Reject Shop
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
