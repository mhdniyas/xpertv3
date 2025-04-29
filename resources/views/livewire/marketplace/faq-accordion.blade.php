<div class="bg-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Frequently Asked Questions
            </h2>
            <p class="mt-4 text-lg text-gray-600">
                Have questions about our marketplace? Find answers to common inquiries below.
            </p>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button
                        wire:click="toggleItem({{ $index }})"
                        class="flex justify-between items-center w-full px-6 py-4 text-lg font-medium text-left text-gray-900 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <span>{{ $faq['question'] }}</span>
                        <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200 {{ isset($openItems[$index]) ? 'rotate-180' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Answer section with smooth height transition -->
                    <div x-data="{ open: {{ isset($openItems[$index]) ? 'true' : 'false' }} }"
                         x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-2"
                         class="px-6 py-4 bg-white"
                         @if(!isset($openItems[$index])) style="display: none;" @endif
                    >
                        <p class="text-gray-600">
                            {{ $faq['answer'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <p class="text-gray-600">Can't find what you're looking for?</p>
            <a href="#contact" class="mt-2 inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                Contact our support team
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</div>
