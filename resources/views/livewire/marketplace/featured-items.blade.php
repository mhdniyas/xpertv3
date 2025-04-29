<div class="bg-gray-50 py-14" wire:poll.10s>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900">
                Featured Items
                <span class="block mt-1 text-lg font-normal text-gray-500">Top picks for you</span>
            </h2>

            <a href="#" class="hidden sm:flex items-center text-blue-600 hover:text-blue-700 font-medium">
                View all
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        <!-- Scrollable container -->
        <div class="relative">
            <!-- Left scroll button (hidden on small screens) -->
            <button class="hidden lg:flex absolute left-0 top-1/2 transform -translate-y-1/2 -ml-4 z-10 bg-white rounded-full shadow-lg p-2 text-gray-800 hover:text-blue-600 focus:outline-none" id="scroll-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Main scrollable content -->
            <div class="flex overflow-x-auto pb-6 scrollbar-hide" id="featured-items-container">
                @forelse($featuredProducts as $product)
                    <div class="flex-shrink-0 w-64 mr-6">
                        <div class="bg-white rounded-xl shadow-md overflow-hidden h-full hover:shadow-lg transition-shadow">
                            <div class="relative">
                                <img src="{{ $product->image ?? 'https://via.placeholder.com/300x200' }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-40 object-cover">
                                <div class="absolute top-0 left-0 bg-yellow-500 text-white px-2 py-1 text-xs font-medium rounded-br-lg">
                                    Featured
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        <span class="ml-1 text-sm text-gray-600">{{ number_format($product->rating, 1) }}</span>
                                    </div>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-1">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $product->description }}</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    <button class="px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full py-10 text-center">
                        <p class="text-gray-500">No featured products available</p>
                    </div>
                @endforelse
            </div>

            <!-- Right scroll button (hidden on small screens) -->
            <button class="hidden lg:flex absolute right-0 top-1/2 transform -translate-y-1/2 -mr-4 z-10 bg-white rounded-full shadow-lg p-2 text-gray-800 hover:text-blue-600 focus:outline-none" id="scroll-right">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Simple JavaScript for horizontal scrolling with buttons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('featured-items-container');
            const scrollLeftBtn = document.getElementById('scroll-left');
            const scrollRightBtn = document.getElementById('scroll-right');

            if (scrollLeftBtn && scrollRightBtn && container) {
                scrollLeftBtn.addEventListener('click', () => {
                    container.scrollBy({ left: -300, behavior: 'smooth' });
                });

                scrollRightBtn.addEventListener('click', () => {
                    container.scrollBy({ left: 300, behavior: 'smooth' });
                });
            }
        });
    </script>
</div>
