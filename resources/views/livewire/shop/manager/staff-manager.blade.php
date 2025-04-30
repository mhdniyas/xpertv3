<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Staff Management for {{ $shop->name }}</h2>
            </div>

            <!-- Staff Assignment Form -->
            <div class="mb-6 bg-gray-50 p-4 rounded-md">
                <h3 class="text-lg font-medium mb-3">Assign New Staff Member</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- User Search and Selection -->
                    <div>
                        <label for="selectedUserId" class="block text-sm font-medium text-gray-700 mb-1">Select User</label>
                        <div class="relative">
                            <input
                                type="text"
                                wire:model.live="searchTerm"
                                placeholder="Search users..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-2"
                            >
                            @if(count($users) > 0)
                                <div class="absolute z-10 w-full bg-white shadow-lg max-h-60 overflow-auto border border-gray-300 rounded-md">
                                    @foreach($users as $user)
                                        <div
                                            wire:click="$set('selectedUserId', '{{ $user->id }}')"
                                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer {{ $selectedUserId == $user->id ? 'bg-indigo-50' : '' }}"
                                        >
                                            <div>{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            <div class="text-xs text-gray-400">{{ $user->role->name ?? 'No role' }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @error('selectedUserId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role Selection -->
                    <div>
                        <label for="selectedRole" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select
                            wire:model="selectedRole"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            @foreach($roles as $role)
                                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        @error('selectedRole') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button
                            wire:click="assignStaff"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Assign Staff
                        </button>
                    </div>
                </div>
            </div>

            <!-- Staff Members List -->
            <div>
                <h3 class="text-lg font-medium mb-3">Current Staff Members</h3>

                @if(count($staffMembers) > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($staffMembers as $staff)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $staff->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $staff->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $staff->pivot->role == 'manager' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                {{ ucfirst($staff->pivot->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $staff->role->name ?? 'No role' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                wire:click="confirmRemoveStaff({{ $staff->id }})"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-md text-center">
                        <p class="text-gray-600">No staff members have been assigned to this shop yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Remove Staff Member
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to remove this staff member from the shop? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button
                        wire:click="removeStaff"
                        type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Remove
                    </button>
                    <button
                        wire:click="cancelRemoveStaff"
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if(session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ session('message') }}
        </div>
    @endif
</div>
