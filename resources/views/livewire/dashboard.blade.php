<div class="bg-gray-100">
    <!-- Flash Messages with animation removed for ugliness -->
    @if (session()->has('message'))
        <div class="bg-green-200 border-2 border-green-700 p-2 m-2">
            <span>{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-200 border-2 border-red-700 p-2 m-2">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Remove Dashboard Navigation Tabs and make it full page -->
    <div>
        <!-- Tab Content - Only show settings content -->
        <div>
            @if($activeTab === 'overview' || true)
                <!-- Always show overview content regardless of active tab -->
                <div class="bg-white border-4 border-gray-400 p-4 w-full">
                    <h2 class="text-2xl font-bold text-gray-700 mb-4">SETTINGS</h2>

                    <!-- User Details Section -->
                    <div class="border-2 border-gray-300 p-2 mb-4">
                        <div>
                            <h2 class="text-lg">User Details</h2>
                            <p>Your personal account information.</p>
                        </div>

                        <div class="border-t-2 border-gray-300 mt-2">
                            <dl>
                                <div class="py-2">
                                    <dt>Full name</dt>
                                    <dd>{{ $currentUser->name }}</dd>
                                </div>
                                <div class="py-2">
                                    <dt>Email address</dt>
                                    <dd>{{ $currentUser->email }}</dd>
                                </div>
                                <div class="py-2">
                                    <dt>Role</dt>
                                    <dd>{{ $currentUser->role->name ?? 'User' }}</dd>
                                </div>
                                <div class="py-2">
                                    <dt>Registered</dt>
                                    <dd>{{ $currentUser->created_at->format('F j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Dashboard Stats -->
                    @if($currentUser->isAdmin() || $currentUser->isSuperadmin())
                    <div class="border-2 border-gray-300 p-2 mb-4">
                        <h2 class="text-lg">System Statistics</h2>
                        <p>System overview and statistics.</p>

                        <div class="grid grid-cols-1 gap-2 mt-2 sm:grid-cols-2">
                            <div class="border border-gray-300 p-2">
                                <dt>Total Users</dt>
                                <dd class="text-2xl">{{ $totalUsers }}</dd>
                            </div>

                            <div class="border border-gray-300 p-2">
                                <dt>Total Roles</dt>
                                <dd class="text-2xl">{{ $totalRoles }}</dd>
                            </div>

                            <div class="border border-gray-300 p-2">
                                <dt>Categories</dt>
                                <dd class="text-2xl">{{ $totalCategories }}</dd>
                            </div>

                            <div class="border border-gray-300 p-2">
                                <dt>Global Products</dt>
                                <dd class="text-2xl">{{ $totalProducts }}</dd>
                            </div>

                            <div class="border border-gray-300 p-2">
                                <dt>Pending Shops</dt>
                                <dd class="text-2xl">{{ $pendingShops }}</dd>
                            </div>

                            <div class="border border-gray-300 p-2">
                                <dt>Product Suggestions</dt>
                                <dd class="text-2xl">{{ $productSuggestions }}</dd>
                            </div>

                            <div class="border border-gray-300 p-2">
                                <dt>Your Role</dt>
                                <dd class="text-xl">{{ $currentUser->role->name ?? 'User' }}</dd>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($currentUser->isAdmin() || $currentUser->isSuperadmin())
                    <!-- Recent Users -->
                    <div class="border-2 border-gray-300 p-2 mb-4">
                        <div>
                            <h2 class="text-lg">Recent Users</h2>
                            <p>Recently registered users in the system.</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-300 p-2 bg-gray-200">Name</th>
                                        <th class="border border-gray-300 p-2 bg-gray-200">Email</th>
                                        <th class="border border-gray-300 p-2 bg-gray-200">Role</th>
                                        <th class="border border-gray-300 p-2 bg-gray-200">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentUsers as $user)
                                    <tr>
                                        <td class="border border-gray-300 p-2">{{ $user->name }}</td>
                                        <td class="border border-gray-300 p-2">{{ $user->email }}</td>
                                        <td class="border border-gray-300 p-2">{{ $user->role->name ?? 'User' }}</td>
                                        <td class="border border-gray-300 p-2">{{ $user->created_at->format('M j, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if($currentUser->isSuperadmin())
                    <!-- Users by Role Distribution -->
                    <div class="border-2 border-gray-300 p-2">
                        <h2 class="text-lg">Users by Role</h2>
                        <p>Distribution of users across different roles.</p>

                        <div class="mt-4">
                            @foreach ($usersByRole as $role)
                            <div class="mb-2">
                                <div class="flex justify-between">
                                    <span>{{ $role->name }}</span>
                                    <span>{{ $role->users_count }}</span>
                                </div>
                                <div class="w-full bg-gray-300 h-4 mt-1">
                                    <div class="bg-gray-600 h-4" style="width: {{ ($role->users_count / max(1, $totalUsers)) * 100 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @endif

            <!-- Users Tab Content -->
            @if($activeTab === 'users' && ($currentUser->isAdmin() || $currentUser->isSuperadmin()))
                <div>
                    <livewire:admin.user-manager />
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
        </div>
    </div>

    <!-- Delete Confirmation Modal (simplified) -->
    @if($confirmingDelete)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white border-4 border-gray-800 p-4 max-w-md w-full">
                <h3>Delete User</h3>
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="flex justify-end mt-4">
                    <button wire:click="cancelDelete" class="bg-gray-300 p-2 mr-2">Cancel</button>
                    <button wire:click="delete" class="bg-red-600 text-white p-2">Delete User</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Sign-out Confirmation Modal (simplified) -->
    @if($showSignoutConfirm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white border-4 border-gray-800 p-4 max-w-md w-full">
                <h3>Sign Out</h3>
                <p>Are you sure you want to sign out of your account?</p>
                <div class="flex justify-end mt-4">
                    <button wire:click="cancelSignout" class="bg-gray-300 p-2 mr-2">Cancel</button>
                    <button wire:click="signOut" class="bg-blue-600 text-white p-2">Sign Out</button>
                </div>
            </div>
        </div>
    @endif
</div>
