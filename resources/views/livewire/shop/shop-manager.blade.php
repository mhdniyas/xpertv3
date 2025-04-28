<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Shop Management</h1>

            <div class="flex items-center space-x-4">
                <!-- Search box -->
                <div class="relative">
                    <input wire:model.debounce.300ms="searchTerm"
                        type="text"
                        placeholder="Search shops..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <!-- Create new shop button (if user has permission) -->
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperadmin() || auth()->user()->isManager())
                <button wire:click="editShop" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    New Shop
                </button>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button wire:click="$refresh" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Shop List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @forelse ($shops as $shop)
                    <li>
                        <div class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                            @if($shop->image)
                                                <img src="{{ Storage::url($shop->image) }}" alt="{{ $shop->name }}" class="h-12 w-12 object-cover">
                                            @else
                                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-lg font-medium text-indigo-600 truncate">
                                                {{ $shop->name }}
                                            </div>
                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                @if($shop->address)
                                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                                    </svg>
                                                    {{ $shop->address }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $shop->status === 'approved' ? 'green' : ($shop->status === 'pending' ? 'yellow' : 'red') }}-100 text-{{ $shop->status === 'approved' ? 'green' : ($shop->status === 'pending' ? 'yellow' : 'red') }}-800">
                                                {{ ucfirst($shop->status) }}
                                            </span>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $shop->is_active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $shop->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center text-xs text-gray-500">
                                            <span>Owner: {{ $shop->owner->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end space-x-3">
                                    <!-- Edit button -->
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isAdmin() || $shop->owner_id === auth()->id())
                                    <button wire:click="editShop({{ $shop->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 active:bg-indigo-200 transition ease-in-out duration-150">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Edit
                                    </button>
                                    @endif

                                    <!-- Staff button -->
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isAdmin() || $shop->owner_id === auth()->id())
                                    <button wire:click="selectShopForStaffAssignment({{ $shop->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:border-green-300 focus:ring focus:ring-green-200 active:bg-green-200 transition ease-in-out duration-150">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                        </svg>
                                        Staff
                                    </button>
                                    @endif

                                    <!-- Delete button -->
                                    @if(auth()->user()->isSuperadmin() || auth()->user()->isAdmin() || $shop->owner_id === auth()->id())
                                    <button wire:click="confirmDeleteShop({{ $shop->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:border-red-300 focus:ring focus:ring-red-200 active:bg-red-200 transition ease-in-out duration-150">
                                        <svg class="-ml-0.5 mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                        Delete
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-gray-500">
                        No shops found. Click "New Shop" to create one.
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Pagination -->
        @if($shops->hasPages())
        <div class="mt-6">
            {{ $shops->links() }}
        </div>
        @endif

        <!-- Shop Edit Modal -->
        @if($isEditingShop)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveShop">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                {{ $shopId ? 'Edit Shop' : 'Create New Shop' }}
                            </h3>

                            <div class="mt-4 space-y-4">
                                <!-- Shop Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Shop Name</label>
                                    <input type="text" wire:model="name" id="name" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea wire:model="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Address -->
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <input type="text" wire:model="address" id="address" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Phone -->
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" wire:model="phone" id="phone" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                        <input type="email" wire:model="email" id="email" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Active Status -->
                                    <div class="flex items-center">
                                        <input id="is_active" name="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                                        @error('is_active') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Shop Status (Admin/Superadmin only) -->
                                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperadmin())
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Shop Status</label>
                                        <select id="status" wire:model="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                        @error('status') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    @endif
                                </div>

                                <!-- Shop Image -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Shop Image</label>
                                    <div class="mt-1 flex items-center">
                                        @if ($shopImage)
                                            <div class="w-32 h-32 rounded-md overflow-hidden bg-gray-100">
                                                <img src="{{ $shopImage->temporaryUrl() }}" alt="Shop Image Preview" class="w-full h-full object-cover">
                                            </div>
                                        @elseif ($existingImage)
                                            <div class="w-32 h-32 rounded-md overflow-hidden bg-gray-100">
                                                <img src="{{ Storage::url($existingImage) }}" alt="Existing Shop Image" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-32 h-32 rounded-md overflow-hidden border-2 border-gray-300 flex items-center justify-center">
                                                <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div class="ml-5 flex-1">
                                            <div>
                                                <label for="shopImage" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition">
                                                    Select Image
                                                    <input id="shopImage" type="file" wire:model="shopImage" class="sr-only">
                                                </label>
                                            </div>
                                            @if ($existingImage)
                                                <div class="mt-2">
                                                    <label class="inline-flex items-center">
                                                        <input type="checkbox" wire:model="removeImage" class="form-checkbox h-4 w-4 text-red-600">
                                                        <span class="ml-2 text-sm text-gray-600">Remove current image</span>
                                                    </label>
                                                </div>
                                            @endif
                                            @error('shopImage') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Save
                            </button>
                            <button type="button" wire:click="cancelEdit" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Staff Assignment Modal -->
        @if($isAssigningStaff)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Manage Shop Staff
                        </h3>

                        <div class="space-y-6">
                            <!-- Add new staff form -->
                            <div class="p-4 bg-gray-50 rounded-md">
                                <h4 class="font-medium text-gray-900 mb-3">Add Staff Member</h4>
                                <form wire:submit.prevent="assignStaff">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- User selection -->
                                        <div>
                                            <label for="selectedUserId" class="block text-sm font-medium text-gray-700">User</label>
                                            <select id="selectedUserId" wire:model="selectedUserId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                <option value="">Select User</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role->name }})</option>
                                                @endforeach
                                            </select>
                                            @error('selectedUserId') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Role selection -->
                                        <div>
                                            <label for="selectedRole" class="block text-sm font-medium text-gray-700">Role</label>
                                            <select id="selectedRole" wire:model="selectedRole" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                                @endforeach
                                            </select>
                                            @error('selectedRole') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:text-sm">
                                            Assign Staff
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Current staff list -->
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Current Staff</h4>
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
                                            @forelse($staffMembers as $staff)
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

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="closeStaffModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-300 sm:mt-0 sm:w-auto sm:text-sm">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Confirmation Modal -->
        @if($showConfirmModal)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
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
                                    Confirmation Required
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        @if($shopToDelete)
                                            Are you sure you want to delete this shop? This action cannot be undone.
                                        @elseif($staffToRemove)
                                            Are you sure you want to remove this staff member from the shop?
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        @if($shopToDelete)
                            <button type="button" wire:click="deleteShop" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Delete
                            </button>
                        @elseif($staffToRemove)
                            <button type="button" wire:click="removeStaff" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Remove
                            </button>
                        @endif
                        <button type="button" wire:click="cancelConfirmation" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-300 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
