<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Activity Logger</h3>
                <span class="text-base font-normal text-gray-500 dark:text-gray-400">Track all system activities and user actions</span>
            </div>
        </div>

        <!-- Filter options -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input wire:model.debounce.300ms="search" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="Search by description or user">
            </div>
            <div>
                <label for="user_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User</label>
                <select wire:model="user_filter" id="user_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="action_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Action</label>
                <select wire:model="action_filter" id="action_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="model_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Model</label>
                <select wire:model="model_filter" id="model_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $type => $name)
                        <option value="{{ $type }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_start" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                <input wire:model="date_start" type="date" id="date_start" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
            </div>
            <div>
                <label for="date_end" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                <input wire:model="date_end" type="date" id="date_end" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
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

        <!-- Activity Log Table -->
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto rounded-lg">
                <div class="align-middle inline-block min-w-full">
                    <div class="shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Time
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Action
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Model
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Description
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $log->created_at->format('M d, Y H:i:s') }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $log->user ? $log->user->name : 'System' }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal">
                                            <span class="px-2 py-1 font-semibold leading-tight rounded-full
                                                {{ $log->action === 'created' ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : '' }}
                                                {{ $log->action === 'updated' ? 'text-blue-700 bg-blue-100 dark:bg-blue-700 dark:text-blue-100' : '' }}
                                                {{ $log->action === 'deleted' ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : '' }}
                                                {{ $log->action === 'approved' ? 'text-green-700 bg-green-100 dark:bg-green-700 dark:text-green-100' : '' }}
                                                {{ $log->action === 'rejected' ? 'text-red-700 bg-red-100 dark:bg-red-700 dark:text-red-100' : '' }}">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500 dark:text-gray-400">
                                            @php
                                                $parts = explode('\\', $log->model_type);
                                                $modelName = end($parts);
                                            @endphp
                                            {{ $modelName }}
                                        </td>
                                        <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $log->description }}

                                            @if($log->old_values || $log->new_values)
                                                <button x-data="{}" x-on:click="$dispatch('open-modal', 'activity-changes-{{ $log->id }}')" class="ml-2 text-blue-600 dark:text-blue-500 hover:underline text-xs">
                                                    View Changes
                                                </button>

                                                <!-- Modal for viewing changes -->
                                                <div x-data="{ show: false, activeTab: 'new' }"
                                                    x-show="show"
                                                    x-on:open-modal.window="if ($event.detail === 'activity-changes-{{ $log->id }}') { show = true }"
                                                    x-on:close-modal.window="show = false"
                                                    x-on:keydown.escape.window="show = false"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 transform scale-90"
                                                    x-transition:enter-end="opacity-100 transform scale-100"
                                                    x-transition:leave="transition ease-in duration-300"
                                                    x-transition:leave-start="opacity-100 transform scale-100"
                                                    x-transition:leave-end="opacity-0 transform scale-90"
                                                    class="fixed inset-0 z-50 overflow-y-auto"
                                                    style="display: none;">
                                                    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" x-on:click="show = false">
                                                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                                                        </div>
                                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                <div class="sm:flex sm:items-start">
                                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                                            Changes
                                                                        </h3>
                                                                        <div class="mt-4">
                                                                            <!-- Tab Navigation -->
                                                                            <div class="border-b border-gray-200 dark:border-gray-600">
                                                                                <nav class="-mb-px flex" aria-label="Tabs">
                                                                                    <button x-on:click="activeTab = 'new'" :class="{'border-blue-500 text-blue-600 dark:text-blue-500': activeTab === 'new', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'new'}" class="w-1/2 py-2 px-1 text-center border-b-2 font-medium text-sm">
                                                                                        New Values
                                                                                    </button>
                                                                                    <button x-on:click="activeTab = 'old'" :class="{'border-blue-500 text-blue-600 dark:text-blue-500': activeTab === 'old', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400': activeTab !== 'old'}" class="w-1/2 py-2 px-1 text-center border-b-2 font-medium text-sm">
                                                                                        Old Values
                                                                                    </button>
                                                                                </nav>
                                                                            </div>

                                                                            <!-- Tab Content -->
                                                                            <div class="py-4">
                                                                                <div x-show="activeTab === 'new'" class="space-y-2">
                                                                                    @if($log->new_values)
                                                                                        @foreach($log->new_values as $key => $value)
                                                                                            <div class="flex">
                                                                                                <span class="font-medium text-sm text-gray-900 dark:text-white w-1/3">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                                                <span class="text-sm text-gray-500 dark:text-gray-400 w-2/3">
                                                                                                    @if(is_bool($value))
                                                                                                        {{ $value ? 'Yes' : 'No' }}
                                                                                                    @else
                                                                                                        {{ is_array($value) ? json_encode($value) : $value }}
                                                                                                    @endif
                                                                                                </span>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <p class="text-sm text-gray-500 dark:text-gray-400">No new values recorded</p>
                                                                                    @endif
                                                                                </div>
                                                                                <div x-show="activeTab === 'old'" class="space-y-2" style="display: none;">
                                                                                    @if($log->old_values)
                                                                                        @foreach($log->old_values as $key => $value)
                                                                                            <div class="flex">
                                                                                                <span class="font-medium text-sm text-gray-900 dark:text-white w-1/3">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                                                                <span class="text-sm text-gray-500 dark:text-gray-400 w-2/3">
                                                                                                    @if(is_bool($value))
                                                                                                        {{ $value ? 'Yes' : 'No' }}
                                                                                                    @else
                                                                                                        {{ is_array($value) ? json_encode($value) : $value }}
                                                                                                    @endif
                                                                                                </span>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <p class="text-sm text-gray-500 dark:text-gray-400">No old values recorded</p>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                                <button type="button" x-on:click="show = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                                    Close
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                            No activity logs found
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
            {{ $logs->links() }}
        </div>
    </div>
</div>
