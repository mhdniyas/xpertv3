<div class="bg-blue-700" id="newsletter">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="bg-blue-800 rounded-2xl shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4">
            <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-10 lg:p-12">
                <div class="lg:self-center">
                    <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                        <span class="block">Stay updated</span>
                        <span class="block">with our newsletter</span>
                    </h2>
                    <p class="mt-4 text-lg leading-6 text-blue-200">
                        Sign up to receive updates on new products, special offers, and marketplace tips.
                    </p>
                    <div class="mt-8">
                        <form wire:submit.prevent="subscribe" class="sm:flex">
                            <label for="email-address" class="sr-only">Email address</label>
                            <div class="w-full">
                                <input
                                    wire:model.lazy="email"
                                    id="email-address"
                                    type="email"
                                    placeholder="Enter your email"
                                    autocomplete="email"
                                    class="w-full px-5 py-3 border border-transparent placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:border-white rounded-md"
                                >

                                <!-- Error Message -->
                                @if($errorMessage)
                                    <div class="mt-2 text-red-200 text-sm">{{ $errorMessage }}</div>
                                @endif

                                <!-- Success Message -->
                                @if($successMessage)
                                    <div class="mt-2 text-green-200 text-sm">{{ $successMessage }}</div>
                                @endif
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <button
                                    type="submit"
                                    class="w-full flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-blue-800 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700"
                                >
                                    <span wire:loading.remove wire:target="subscribe">Subscribe</span>
                                    <span wire:loading wire:target="subscribe">
                                        <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-blue-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                    <p class="mt-3 text-sm text-blue-200">
                        We care about your data. Read our
                        <a href="#" class="text-white font-medium underline">Privacy Policy</a>
                    </p>
                </div>
            </div>
            <div class="relative -mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                <img class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top sm:translate-x-12 lg:translate-y-12" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1471&q=80" alt="App screenshot">
            </div>
        </div>
    </div>
</div>
