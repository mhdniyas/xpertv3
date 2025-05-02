<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $shop->name }} - {{ __('Rental Bookings') }}
            </h2>
            <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700" onclick="openAddBookingModal()">
                {{ __('Create New Booking') }}
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter and Search -->
                    <div class="mb-6 flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                        <div class="flex items-center">
                            <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mr-2">
                                <option value="all">All Status</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="picked-up">Picked Up</option>
                                <option value="returned">Returned</option>
                                <option value="canceled">Canceled</option>
                            </select>
                            <select id="payment-filter" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="all">All Payments</option>
                                <option value="pending">Pending</option>
                                <option value="partial">Partial</option>
                                <option value="paid">Paid</option>
                                <option value="refunded">Refunded</option>
                            </select>
                        </div>
                        <div class="w-full md:w-1/3">
                            <input type="text" id="search" placeholder="Search bookings..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <!-- Bookings Table -->
                    @if($bookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Booking #
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Customer
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Rental Period
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Payment
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bookings as $booking)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $booking->booking_number }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $booking->created_at->format('M d, Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $booking->customer_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $booking->customer_phone ?? $booking->customer_email }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">
                                                    {{ $booking->rental_start->format('M d, Y H:i') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    to {{ $booking->rental_end->format('M d, Y H:i') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    ${{ number_format($booking->total_amount, 2) }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @if($booking->balance > 0)
                                                        ${{ number_format($booking->balance, 2) }} due
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $booking->booking_status === 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                                       ($booking->booking_status === 'picked-up' ? 'bg-yellow-100 text-yellow-800' :
                                                        ($booking->booking_status === 'returned' ? 'bg-green-100 text-green-800' :
                                                         'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                                       ($booking->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800' :
                                                        ($booking->payment_status === 'refunded' ? 'bg-purple-100 text-purple-800' :
                                                         'bg-red-100 text-red-800')) }}">
                                                    {{ ucfirst($booking->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <button type="button" class="text-indigo-600 hover:text-indigo-900 mr-2" onclick="viewBookingDetails({{ $booking->id }})">View</button>

                                                @if($booking->booking_status === 'confirmed')
                                                    <button type="button" class="text-yellow-600 hover:text-yellow-900 mr-2" onclick="processPickup({{ $booking->id }})">Pickup</button>
                                                @endif

                                                @if($booking->booking_status === 'picked-up')
                                                    <button type="button" class="text-green-600 hover:text-green-900 mr-2" onclick="processReturn({{ $booking->id }})">Return</button>
                                                @endif

                                                @if($booking->booking_status === 'confirmed')
                                                    <button type="button" class="text-red-600 hover:text-red-900" onclick="cancelBooking({{ $booking->id }})">Cancel</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $bookings->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new rental booking.</p>
                            <div class="mt-6">
                                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="openAddBookingModal()">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Create Booking
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder for modals - these would be implemented with Alpine.js or a similar approach -->
    <!-- Booking Modal -->
    <div id="bookingModal" x-data="{ open: false, action: 'create', bookingId: null }" x-show="open" style="display: none;" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title" x-text="action === 'create' ? 'Create New Booking' : 'Edit Booking'"></h3>
                            <div class="mt-2">
                                <form id="bookingForm" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Customer Information -->
                                        <div class="col-span-1 md:col-span-2">
                                            <h4 class="font-medium text-gray-900 mb-2">Customer Information</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Name</label>
                                                    <input type="text" name="customer_name" id="customer_name" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                                                    <input type="email" name="customer_email" id="customer_email" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                                    <input type="text" name="customer_phone" id="customer_phone" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="customer_id" class="block text-sm font-medium text-gray-700">ID Number</label>
                                                    <input type="text" name="customer_id" id="customer_id" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Rental Details -->
                                        <div class="col-span-1 md:col-span-2">
                                            <h4 class="font-medium text-gray-900 mb-2">Rental Details</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="rental_start" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                                                    <input type="datetime-local" name="rental_start" id="rental_start" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div>
                                                    <label for="rental_end" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                                                    <input type="datetime-local" name="rental_end" id="rental_end" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Product Selection -->
                                        <div class="col-span-1 md:col-span-2">
                                            <div class="flex justify-between items-center mb-2">
                                                <h4 class="font-medium text-gray-900">Selected Products</h4>
                                                <button type="button" id="add-product-btn" class="inline-flex items-center p-1 border border-transparent rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3h-3a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="bg-gray-50 p-3 rounded-md">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                                            <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                                            <th scope="col" class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200" id="selected-products">
                                                        <!-- Product rows will be added dynamically -->
                                                        <tr class="text-center text-gray-500 py-4">
                                                            <td colspan="5" class="px-3 py-4">
                                                                No products selected. Click the + button to add products.
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Payment Details -->
                                        <div class="col-span-1 md:col-span-2">
                                            <h4 class="font-medium text-gray-900 mb-2">Payment Details</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Amount</label>
                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">$</span>
                                                        </div>
                                                        <input type="text" name="total_amount" id="total_amount" readonly class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md bg-gray-50" placeholder="0.00">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="deposit_amount" class="block text-sm font-medium text-gray-700">Deposit Amount</label>
                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">$</span>
                                                        </div>
                                                        <input type="text" name="deposit_amount" id="deposit_amount" readonly class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md bg-gray-50" placeholder="0.00">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                                                    <select id="payment_status" name="payment_status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                        <option value="pending">Pending</option>
                                                        <option value="partial">Partial</option>
                                                        <option value="paid">Paid</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label for="payment_amount" class="block text-sm font-medium text-gray-700">Amount Paid</label>
                                                    <div class="mt-1 relative rounded-md shadow-sm">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <span class="text-gray-500 sm:text-sm">$</span>
                                                        </div>
                                                        <input type="text" name="payment_amount" id="payment_amount" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Notes -->
                                        <div class="col-span-1 md:col-span-2">
                                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                            <textarea id="notes" name="notes" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Add any special instructions or notes here..."></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" @click="saveBooking()">
                        Save
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="open = false">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div id="productSelectionModal" x-data="{ open: false }" x-show="open" style="display: none;" class="fixed inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Select Products</h3>

                            <div class="mt-2">
                                <div class="mb-4">
                                    <input type="text" id="product-search" placeholder="Search products..." class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>

                                <div class="overflow-y-auto max-h-96">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rates</th>
                                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" id="available-products">
                                            <!-- Dynamic content will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm" @click="open = false">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-placeholder"></div>

    <!-- JavaScript to handle the modal actions -->
    <script>
        function openAddBookingModal() {
            // Initialize Alpine.js component
            Alpine.store('booking', {
                selectedProducts: [],
                totalAmount: 0,
                depositAmount: 0
            });

            // Reset form and open the modal
            document.getElementById('bookingForm').reset();

            // Set default dates (today and tomorrow)
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);

            // Format dates for datetime-local input
            document.getElementById('rental_start').value = formatDateForInput(today);
            document.getElementById('rental_end').value = formatDateForInput(tomorrow);

            // Wait for Alpine.js to be fully initialized
            if (typeof Alpine !== 'undefined') {
                // Access the Alpine component using Alpine.js global store or find the component
                setTimeout(() => {
                    const bookingModalElement = document.querySelector('#bookingModal');
                    if (bookingModalElement && bookingModalElement.__x) {
                        bookingModalElement.__x.data.action = 'create';
                        bookingModalElement.__x.data.bookingId = null;
                        bookingModalElement.__x.data.open = true;
                    } else {
                        console.error('Alpine.js component not found on #bookingModal');
                    }
                }, 50);
            } else {
                console.error('Alpine.js is not defined');
            }
        }

        function formatDateForInput(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }

        function viewBookingDetails(bookingId) {
            // In a real implementation, this would fetch booking details from the server
            // and populate the booking modal with that data
            alert('View booking details for: ' + bookingId);
        }

        function processPickup(bookingId) {
            if(confirm('Process pickup for this booking?')) {
                // Implementation would go here
                alert('Pickup processed for booking: ' + bookingId);
            }
        }

        function processReturn(bookingId) {
            if(confirm('Process return for this booking?')) {
                // Implementation would go here
                alert('Return processed for booking: ' + bookingId);
            }
        }

        function cancelBooking(bookingId) {
            if(confirm('Are you sure you want to cancel this booking?')) {
                // Implementation would go here
                alert('Booking ' + bookingId + ' would be canceled');
            }
        }

        // Initialize Alpine.js components when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add product button event listener
            document.getElementById('add-product-btn').addEventListener('click', function() {
                openProductSelectionModal();
            });

            // Product search event listener
            const productSearch = document.getElementById('product-search');
            if (productSearch) {
                productSearch.addEventListener('input', function() {
                    filterProducts(this.value);
                });
            }
        });

        function openProductSelectionModal() {
            // In a real implementation, this would load available products from the server
            loadAvailableProducts();

            // Wait for Alpine.js to be fully initialized
            if (typeof Alpine !== 'undefined') {
                // Access the Alpine component using Alpine.js global store or find the component
                setTimeout(() => {
                    const productModalElement = document.querySelector('#productSelectionModal');
                    if (productModalElement && productModalElement.__x) {
                        productModalElement.__x.data.open = true;
                    } else {
                        console.error('Alpine.js component not found on #productSelectionModal');
                    }
                }, 50);
            } else {
                console.error('Alpine.js is not defined');
            }
        }

        function loadAvailableProducts() {
            // This is a simplified example. In a real implementation,
            // this would fetch data from the server via AJAX
            const products = [
                { id: 1, name: 'Power Drill XL-500', sku: 'DRILL-XL500', available: 5, daily_rate: 25.00, deposit: 50.00 },
                { id: 2, name: 'Cement Mixer Pro-300', sku: 'MIXER-PRO300', available: 2, daily_rate: 45.00, deposit: 100.00 },
                { id: 3, name: 'Party Tent 20x30', sku: 'TENT-2030', available: 1, daily_rate: 150.00, deposit: 300.00 }
            ];

            const productsContainer = document.getElementById('available-products');
            productsContainer.innerHTML = '';

            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-3 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                                <div class="text-sm text-gray-500">SKU: ${product.sku}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${product.available} units</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">$${product.daily_rate.toFixed(2)}/day</div>
                        <div class="text-sm text-gray-500">$${product.deposit.toFixed(2)} deposit</div>
                    </td>
                    <td class="px-3 py-4 whitespace-nowrap text-center">
                        <button type="button" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="selectProduct(${product.id}, '${product.name}', ${product.daily_rate}, ${product.deposit})">
                            Select
                        </button>
                    </td>
                `;
                productsContainer.appendChild(row);
            });
        }

        function filterProducts(query) {
            // Simplified implementation - in a real app, this might re-fetch filtered results from the server
            const rows = document.querySelectorAll('#available-products tr');
            query = query.toLowerCase();

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function selectProduct(id, name, rate, deposit) {
            // Get the selected products container
            const selectedProductsContainer = document.getElementById('selected-products');

            // Check if the "No products" row exists and remove it
            const noProductsRow = selectedProductsContainer.querySelector('tr.text-center');
            if (noProductsRow) {
                selectedProductsContainer.removeChild(noProductsRow);
            }

            // Create a new row for the selected product
            const row = document.createElement('tr');
            row.dataset.productId = id;
            row.innerHTML = `
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${name}</div>
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <input type="number" min="1" value="1" class="product-quantity w-16 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm border-gray-300 rounded-md" onchange="updateProductTotal(this)">
                </td>
                <td class="px-3 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">$${rate.toFixed(2)}/day</div>
                    <input type="hidden" class="product-rate" value="${rate}">
                    <input type="hidden" class="product-deposit" value="${deposit}">
                </td>
                <td class="px-3 py-4 whitespace-nowrap product-subtotal">
                    $${rate.toFixed(2)}
                </td>
                <td class="px-3 py-4 whitespace-nowrap text-right">
                    <button type="button" class="text-red-600 hover:text-red-900" onclick="removeProduct(this)">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </td>
            `;
            selectedProductsContainer.appendChild(row);

            // Close the product selection modal
            if (typeof Alpine !== 'undefined') {
                // Access the Alpine component using Alpine.js global store or find the component
                setTimeout(() => {
                    const productModalElement = document.querySelector('#productSelectionModal');
                    if (productModalElement && productModalElement.__x) {
                        productModalElement.__x.data.open = false;
                    } else {
                        console.error('Alpine.js component not found on #productSelectionModal');
                    }
                }, 50);
            } else {
                console.error('Alpine.js is not defined');
            }

            // Update totals
            calculateTotals();
        }

        function removeProduct(button) {
            const row = button.closest('tr');
            row.parentNode.removeChild(row);

            // If no products left, add the "No products" row back
            const selectedProductsContainer = document.getElementById('selected-products');
            if (selectedProductsContainer.children.length === 0) {
                const noProductsRow = document.createElement('tr');
                noProductsRow.className = 'text-center text-gray-500 py-4';
                noProductsRow.innerHTML = `
                    <td colspan="5" class="px-3 py-4">
                        No products selected. Click the + button to add products.
                    </td>
                `;
                selectedProductsContainer.appendChild(noProductsRow);
            }

            // Update totals
            calculateTotals();
        }

        function updateProductTotal(quantityInput) {
            const row = quantityInput.closest('tr');
            const rate = parseFloat(row.querySelector('.product-rate').value);
            const quantity = parseInt(quantityInput.value);
            const subtotal = rate * quantity;

            row.querySelector('.product-subtotal').textContent = '$' + subtotal.toFixed(2);

            // Update totals
            calculateTotals();
        }

        function calculateTotals() {
            let totalAmount = 0;
            let depositAmount = 0;

            // Calculate rental duration in days
            const startDate = new Date(document.getElementById('rental_start').value);
            const endDate = new Date(document.getElementById('rental_end').value);
            const durationDays = Math.max(1, Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)));

            // Get all product rows
            const productRows = document.querySelectorAll('#selected-products tr:not(.text-center)');

            productRows.forEach(row => {
                const rate = parseFloat(row.querySelector('.product-rate').value);
                const deposit = parseFloat(row.querySelector('.product-deposit').value);
                const quantity = parseInt(row.querySelector('.product-quantity').value);

                // Calculate subtotal for the rental period
                const subtotal = rate * quantity * durationDays;
                row.querySelector('.product-subtotal').textContent = '$' + subtotal.toFixed(2);

                totalAmount += subtotal;
                depositAmount += deposit * quantity;
            });

            // Update total and deposit fields
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
            document.getElementById('deposit_amount').value = depositAmount.toFixed(2);
        }

        function saveBooking() {
            // Collect form data
            const formData = new FormData(document.getElementById('bookingForm'));

            // Add shop ID and other required data
            formData.append('shop_id', {{ $shop->id }});

            // Collect selected products
            const products = [];
            document.querySelectorAll('#selected-products tr:not(.text-center)').forEach(row => {
                products.push({
                    product_id: row.dataset.productId,
                    quantity: row.querySelector('.product-quantity').value,
                    rate: row.querySelector('.product-rate').value,
                    deposit: row.querySelector('.product-deposit').value
                });
            });

            // In a real implementation, this would send the data to the server via AJAX
            // and handle the response
            console.log('Booking data:', Object.fromEntries(formData));
            console.log('Products:', products);

            alert('Booking saved successfully!');

            // Close the modal
            if (typeof Alpine !== 'undefined') {
                setTimeout(() => {
                    const bookingModalElement = document.querySelector('#bookingModal');
                    if (bookingModalElement && bookingModalElement.__x) {
                        bookingModalElement.__x.data.open = false;
                    } else {
                        console.error('Alpine.js component not found on #bookingModal');
                    }
                }, 50);
            } else {
                console.error('Alpine.js is not defined');
            }

            // In a real implementation, you would reload the bookings table
            // or add the new booking to the table
        }
    </script>
</x-app-layout>
