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
    <div id="modal-placeholder"></div>

    <!-- JavaScript to handle the modal actions (placeholder) -->
    <script>
        function openAddBookingModal() {
            // Implementation would go here - likely using Alpine.js or a similar approach
            alert('Add booking modal would open here');
        }
        
        function viewBookingDetails(bookingId) {
            // Implementation would go here
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
    </script>
</x-app-layout>