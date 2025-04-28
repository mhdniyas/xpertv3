<div>
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 sm:p-6 xl:p-8">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Global Products Management</h3>
                <span class="text-base font-normal text-gray-500 dark:text-gray-400">Manage global products across all shops</span>
            </div>
            <div>
                <button wire:click="openModal()" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600">
                    Add New Global Product
                </button>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        <!-- Filter options -->
        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="w-full md:w-1/3">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input wire:model.debounce.300ms="search" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" placeholder="Search products">
            </div>
            <div class="w-full md:w-1/3">
                <label for="category_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                <select wire:model="category_filter" id="category_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select wire:model="status_filter" id="status_filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
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

        <!-- Products Table -->
        <div class="flex flex-col mt-6">
            <div class="overflow-x-auto rounded-lg">
                <div class="align-middle inline-block min-w-full">
                    <div class="shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-900 dark:text-white">
                                            <div class="font-semibold">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($product->description, 50) }}</div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal text-gray-500 dark:text-gray-400">
                                            {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="p-4 whitespace-nowrap text-sm font-normal">
                                            <div class="flex items-center">
                                                <div class="h-2.5 w-2.5 rounded-full mr-2 {{ $product->is_active ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                                <span>{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                                                <button wire:click="toggleActive({{ $product->id }})" class="ml-2 text-gray-500 dark:text-gray-400">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                        <path fill-rule="evenodd" d="M2 13a2 2 0 002 2h12a2 2 0 002-2V7a1 1 0 00-1-1h-1.586l-2-2H6a1 1 0 00-1 1v9z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="p-4 whitespace-nowrap space-x-2 text-right text-sm font-medium">
                                            <button wire:click="edit({{ $product->id }})" class="text-blue-600 dark:text-blue-500 hover:underline">
                                                Edit
                                            </button>
                                            <button wire:click="openDeleteModal({{ $product->id }})" class="text-red-600 dark:text-red-500 hover:underline">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                            No products found
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
            {{ $products->links() }}
        </div>

        <!-- Create/Edit Modal -->
        @if($isOpen)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="createEditModal">
                <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $product_id ? 'Edit Global Product' : 'Create New Global Product' }}
                        </h3>
                        <button wire:click="closeModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="store">
                        <div class="grid grid-cols-1 gap-4">
                            <div class="col-span-1">
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                                <input wire:model="name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Product name" required>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1">
                                <label for="slug" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slug (optional)</label>
                                <input wire:model="slug" type="text" id="slug" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="product-slug">
                                @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                                <textarea wire:model="description" id="description" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Product description"></textarea>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1">
                                <label for="price" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                                <input wire:model="price" type="number" step="0.01" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="0.00" required>
                                @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1">
                                <label for="category_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                                <select wire:model="category_id" id="category_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-span-1 flex items-center">
                                <input wire:model="is_active" id="is_active" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Active</label>
                            </div>
                        </div>
                        <div class="flex justify-end mt-6">
                            <button wire:click="closeModal()" type="button" class="mr-2 text-gray-500 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600">
                                {{ $product_id ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        @if($isDeleteOpen)
            <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="deleteModal">
                <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirm Delete</h3>
                        <button wire:click="closeDeleteModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete this product? This action cannot be undone.
                        </p>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button wire:click="closeDeleteModal()" type="button" class="mr-2 text-gray-500 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">
                            Cancel
                        </button>
                        <button wire:click="delete()" type="button" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-500 dark:hover:bg-red-600">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
