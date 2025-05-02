<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $shop->name }} - {{ __('Rental Products') }}
            </h2>
            <button type="button" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase bg-blue-600 border border-transparent rounded-md hover:bg-blue-700" onclick="openAddProductModal()">
                {{ __('Add New Product') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter and Search -->
                    <div class="flex flex-col mb-6 space-y-4 md:flex-row md:justify-between md:items-center md:space-y-0">
                        <div class="flex items-center">
                            <select id="status-filter" class="mr-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="all">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            <select id="condition-filter" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="all">All Conditions</option>
                                <option value="new">New</option>
                                <option value="good">Good</option>
                                <option value="fair">Fair</option>
                                <option value="poor">Poor</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3">
                            <input type="text" id="search" placeholder="Search products..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <!-- Products Table -->
                    @if($products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Product
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Stock / Available
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Rates
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                            Condition
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($products as $product)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 w-10 h-10">
                                                        @if($product->primary_image)
                                                            <img class="object-cover w-10 h-10 rounded-md" src="{{ $product->primary_image }}" alt="{{ $product->name }}">
                                                        @else
                                                            <div class="flex items-center justify-center w-10 h-10 bg-gray-200 rounded-md">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $product->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            SKU: {{ $product->sku ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $product->available_quantity }} / {{ $product->stock_quantity }}</div>
                                                <div class="text-sm text-gray-500">{{ $product->unit }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    @if($product->daily_rate)
                                                        ${{ number_format($product->daily_rate, 2) }}/day
                                                    @elseif($product->hourly_rate)
                                                        ${{ number_format($product->hourly_rate, 2) }}/hour
                                                    @else
                                                        N/A
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @if($product->deposit_amount > 0)
                                                        ${!! number_format($product->deposit_amount, 2) !!} deposit
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $product->status === 'active' ? 'bg-green-100 text-green-800' :
                                                       ($product->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $product->condition === 'new' ? 'bg-blue-100 text-blue-800' :
                                                       ($product->condition === 'good' ? 'bg-green-100 text-green-800' :
                                                        ($product->condition === 'fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($product->condition) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                <button type="button" class="mr-2 text-indigo-600 hover:text-indigo-900" onclick="openEditProductModal({{ $product->id }})">Edit</button>
                                                <button type="button" class="text-red-600 hover:text-red-900" onclick="confirmDelete({{ $product->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="py-4 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new rental product.</p>
                            <div class="mt-6">
                                <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="openAddProductModal()">
                                    <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Product
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder for add/edit modals - these would be implemented with Alpine.js or a similar approach -->
    <!-- Product Modal -->
    <div id="productModal" x-data="productModalData()" x-show="open" style="display: none;" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="open = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <!-- Main modal content -->
                <div x-show="!showIterateConfirm">
                    <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title" x-text="action === 'create' ? 'Add New Rental Product' : 'Edit Rental Product'"></h3>
                                <div class="mt-2">
                                    <form id="productForm" class="space-y-6">
                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                            <!-- Basic Information -->
                                            <div class="col-span-1 md:col-span-2">
                                                <h4 class="mb-2 font-medium text-gray-900">Basic Information</h4>
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                    <div>
                                                        <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                                        <input type="text" name="name" id="name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
                                                        <input type="text" name="sku" id="sku" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                                        <select id="category" name="category" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                            <option value="">Select a category...</option>
                                                            <option value="tools">Tools & Equipment</option>
                                                            <option value="party">Party & Event</option>
                                                            <option value="outdoor">Outdoor & Garden</option>
                                                            <option value="electronics">Electronics</option>
                                                            <option value="vehicles">Vehicles</option>
                                                            <option value="other">Other</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="condition" class="block text-sm font-medium text-gray-700">Condition</label>
                                                        <select id="condition" name="condition" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                            <option value="new">New</option>
                                                            <option value="good">Good</option>
                                                            <option value="fair">Fair</option>
                                                            <option value="poor">Poor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Inventory Information -->
                                            <div class="col-span-1 md:col-span-2">
                                                <h4 class="mb-2 font-medium text-gray-900">Inventory Information</h4>
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                                    <div>
                                                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                                                        <input type="number" name="stock_quantity" id="stock_quantity" min="0" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                                        <input type="text" name="unit" id="unit" placeholder="e.g., piece, set, pair" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                    </div>
                                                    <div>
                                                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                                        <select id="status" name="status" class="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                            <option value="active">Active</option>
                                                            <option value="inactive">Inactive</option>
                                                            <option value="maintenance">Maintenance</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Pricing Information -->
                                            <div class="col-span-1 md:col-span-2">
                                                <h4 class="mb-2 font-medium text-gray-900">Pricing Information</h4>
                                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                                    <div>
                                                        <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                                                        <div class="relative mt-1 rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="text" name="hourly_rate" id="hourly_rate" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="daily_rate" class="block text-sm font-medium text-gray-700">Daily Rate</label>
                                                        <div class="relative mt-1 rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="text" name="daily_rate" id="daily_rate" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="weekly_rate" class="block text-sm font-medium text-gray-700">Weekly Rate</label>
                                                        <div class="relative mt-1 rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="text" name="weekly_rate" id="weekly_rate" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="deposit_amount" class="block text-sm font-medium text-gray-700">Security Deposit</label>
                                                        <div class="relative mt-1 rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="text" name="deposit_amount" id="deposit_amount" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="replacement_cost" class="block text-sm font-medium text-gray-700">Replacement Cost</label>
                                                        <div class="relative mt-1 rounded-md shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                                <span class="text-gray-500 sm:text-sm">$</span>
                                                            </div>
                                                            <input type="text" name="replacement_cost" id="replacement_cost" class="block w-full pr-12 border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pl-7 sm:text-sm" placeholder="0.00">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Image Upload -->
                                            <div class="col-span-1 md:col-span-2">
                                                <h4 class="mb-2 font-medium text-gray-900">Product Images</h4>
                                                <div class="flex justify-center px-6 pt-5 pb-6 mt-1 border-2 border-gray-300 border-dashed rounded-md">
                                                    <div class="space-y-1 text-center">
                                                        <svg class="w-12 h-12 mx-auto text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600">
                                                            <label for="product_images" class="relative font-medium text-blue-600 bg-white rounded-md cursor-pointer hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                                <span>Upload images</span>
                                                                <input id="product_images" name="product_images[]" type="file" multiple class="sr-only">
                                                            </label>
                                                            <p class="pl-1">or drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">
                                                            PNG, JPG, GIF up to 10MB
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Preview uploaded images -->
                                                <div id="image-preview" class="grid grid-cols-4 gap-4 mt-4">
                                                    <!-- Images will be previewed here -->
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="col-span-1 md:col-span-2">
                                                <label for="description" class="block text-sm font-medium text-gray-700">Product Description</label>
                                                <textarea id="description" name="description" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Provide a detailed description of the product..."></textarea>
                                            </div>

                                            <!-- Rental Terms & Requirements -->
                                            <div class="col-span-1 md:col-span-2">
                                                <label for="rental_terms" class="block text-sm font-medium text-gray-700">Rental Terms & Requirements</label>
                                                <textarea id="rental_terms" name="rental_terms" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Specify any terms, conditions, or requirements for renting this product..."></textarea>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Continue to iterate confirmation -->
                <div x-show="showIterateConfirm" class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="w-full mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Continue to iterate?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Would you like to continue adding/editing products or finish?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" x-show="!showIterateConfirm" @click="saveProduct()">
                        Save
                    </button>
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" x-show="!showIterateConfirm" @click="open = false">
                        Cancel
                    </button>

                    <!-- Iteration confirmation buttons -->
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" x-show="showIterateConfirm" @click="continueIteration()">
                        Continue Adding
                    </button>
                    <button type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" x-show="showIterateConfirm" @click="finishIteration()">
                        Finish
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-placeholder"></div>

    <!-- JavaScript to handle the modal actions -->
    <script>
        // Alpine.js component initialization
        document.addEventListener('alpine:init', () => {
            Alpine.data('productModalData', () => ({
                open: false,
                action: 'create',
                productId: null,
                showIterateConfirm: false,

                saveProduct() {
                    // Collect form data
                    const formData = new FormData(document.getElementById('productForm'));

                    // Add shop ID
                    formData.append('shop_id', {{ $shop->id }});

                    // In a real implementation, this would send the data to the server via AJAX
                    // and handle the response
                    console.log('Product data:', Object.fromEntries(formData));

                    // Show the continue iteration confirmation
                    this.showIterateConfirm = true;
                },

                continueIteration() {
                    // User chose to continue adding/editing products
                    this.showIterateConfirm = false;

                    if (this.action === 'create') {
                        // Reset the form for a new product
                        document.getElementById('productForm').reset();
                    } else {
                        // Close current edit form and reset for new one
                        this.open = false;
                        setTimeout(() => {
                            this.action = 'create';
                            this.productId = null;
                            this.open = true;
                            document.getElementById('productForm').reset();
                        }, 300);
                    }
                },

                finishIteration() {
                    // User chose to finish adding/editing products
                    this.showIterateConfirm = false;
                    this.open = false;
                }
            }));
        });

        function openAddProductModal() {
            // Reset form and open the modal
            document.getElementById('productForm').reset();
            document.getElementById('image-preview').innerHTML = '';

            // Wait for Alpine.js to be fully initialized
            if (typeof Alpine !== 'undefined') {
                // Access the Alpine component using Alpine.js global store or find the component
                setTimeout(() => {
                    const productModalElement = document.querySelector('#productModal');
                    if (productModalElement && productModalElement.__x) {
                        productModalElement.__x.data.action = 'create';
                        productModalElement.__x.data.productId = null;
                        productModalElement.__x.data.open = true;
                    } else {
                        console.error('Alpine.js component not found on #productModal');
                    }
                }, 50);
            } else {
                console.error('Alpine.js is not defined');
            }
        }

        function openEditProductModal(productId) {
            // In a real implementation, this would fetch product details from the server
            // and populate the form with that data

            // For demo purposes, we'll use some mock data
            const mockProduct = {
                id: productId,
                name: 'Power Drill XL-5000',
                sku: 'DRILL-XL5000',
                category: 'tools',
                condition: 'good',
                stock_quantity: 5,
                unit: 'piece',
                status: 'active',
                hourly_rate: 5.00,
                daily_rate: 25.00,
                weekly_rate: 150.00,
                deposit_amount: 50.00,
                replacement_cost: 200.00,
                description: 'Professional grade power drill with multiple attachments and settings.',
                rental_terms: 'Must be 18+ to rent. Valid ID and credit card required for deposit.'
            };

            // Populate the form with the product data
            document.getElementById('name').value = mockProduct.name;
            document.getElementById('sku').value = mockProduct.sku;
            document.getElementById('category').value = mockProduct.category;
            document.getElementById('condition').value = mockProduct.condition;
            document.getElementById('stock_quantity').value = mockProduct.stock_quantity;
            document.getElementById('unit').value = mockProduct.unit;
            document.getElementById('status').value = mockProduct.status;
            document.getElementById('hourly_rate').value = mockProduct.hourly_rate.toFixed(2);
            document.getElementById('daily_rate').value = mockProduct.daily_rate.toFixed(2);
            document.getElementById('weekly_rate').value = mockProduct.weekly_rate.toFixed(2);
            document.getElementById('deposit_amount').value = mockProduct.deposit_amount.toFixed(2);
            document.getElementById('replacement_cost').value = mockProduct.replacement_cost.toFixed(2);
            document.getElementById('description').value = mockProduct.description;
            document.getElementById('rental_terms').value = mockProduct.rental_terms;

            // Clear image preview
            document.getElementById('image-preview').innerHTML = '';

            // Wait for Alpine.js to be fully initialized
            if (typeof Alpine !== 'undefined') {
                // Access the Alpine component using Alpine.js global store or find the component
                setTimeout(() => {
                    const productModalElement = document.querySelector('#productModal');
                    if (productModalElement && productModalElement.__x) {
                        productModalElement.__x.data.action = 'edit';
                        productModalElement.__x.data.productId = productId;
                        productModalElement.__x.data.open = true;
                    } else {
                        console.error('Alpine.js component not found on #productModal');
                    }
                }, 50);
            } else {
                console.error('Alpine.js is not defined');
            }
        }

        function confirmDelete(productId) {
            if(confirm('Are you sure you want to delete this product?')) {
                // In a real implementation, this would send a delete request to the server
                alert('Product ' + productId + ' would be deleted');
            }
        }

        // Initialize event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Set up image preview functionality
            const imageInput = document.getElementById('product_images');
            const imagePreview = document.getElementById('image-preview');

            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    // Clear existing previews
                    imagePreview.innerHTML = '';

                    // Create preview for each selected file
                    for (const file of this.files) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewContainer = document.createElement('div');
                                previewContainer.className = 'relative';

                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.className = 'h-24 w-24 object-cover rounded-md';
                                previewContainer.appendChild(img);

                                const removeButton = document.createElement('button');
                                removeButton.type = 'button';
                                removeButton.className = 'absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none';
                                removeButton.innerHTML = `
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                `;
                                removeButton.addEventListener('click', function() {
                                    previewContainer.remove();
                                });
                                previewContainer.appendChild(removeButton);

                                imagePreview.appendChild(previewContainer);
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
