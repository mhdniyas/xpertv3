<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <nav x-data="{ open: false, searchOpen: false }"
         class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-100 transition-all duration-300"
         :class="{ 'shadow-lg': window.scrollY > 20 }">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                            <x-application-logo class="block h-9 w-auto fill-current text-blue-600" />
                            <span class="ml-2 text-xl font-bold text-blue-600">LeageXpert</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-1 sm:flex sm:items-center sm:ml-8">
                        @auth
                        <a href="{{ route('dashboard') }}"
                           wire:navigate
                           class="{{ request()->routeIs('dashboard') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-5 px-3 font-medium text-sm transition-colors duration-200">
                            {{ __('Dashboard') }}
                        </a>
                        @endauth

                        <a href="{{ route('marketplace.landing') }}"
                           wire:navigate
                           class="{{ request()->routeIs('marketplace.landing') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-5 px-3 font-medium text-sm transition-colors duration-200">
                            {{ __('Marketplace') }}
                        </a>

                        <a href="{{ route('marketplace') }}"
                           wire:navigate
                           class="{{ request()->routeIs('marketplace') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-5 px-3 font-medium text-sm transition-colors duration-200">
                            {{ __('Shops') }}
                        </a>

                        @if(Auth::check() && Auth::user()->shop)
                        <a href="{{ route('shop.show', Auth::user()->shop->slug) }}"
                           wire:navigate
                           class="{{ request()->routeIs('shop.show') ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600' }} py-5 px-3 font-medium text-sm transition-colors duration-200">
                            {{ __('My Shop') }}
                        </a>
                        @endif
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center space-x-4">
                    <!-- Search Button -->
                    <button @click="searchOpen = !searchOpen" class="text-gray-600 hover:text-blue-600 focus:outline-none p-1 transition-colors duration-200">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    @auth
                    <!-- Shopping Cart -->
                    <a href="#" class="text-gray-600 hover:text-blue-600 relative transition-colors duration-200">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-blue-600 flex items-center justify-center text-xs text-white">0</span>
                    </a>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2 focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-sm">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Str::limit(auth()->user()->name, 12) }}</span>
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen"
                             @click.away="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-lg z-50"
                             style="display: none;">
                            <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                {{ __('Profile') }}
                            </a>

                            <a href="{{ route('settings.profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                {{ __('Settings') }}
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            <button wire:click="logout" class="w-full text-start block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                {{ __('Log Out') }}
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200">
                            {{ __('Log in') }}
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            {{ __('Register') }}
                        </a>
                    </div>
                    @endauth
                </div>

                <!-- Mobile Hamburger -->
                <div class="flex items-center sm:hidden">
                    <button @click="searchOpen = true" class="text-gray-600 hover:text-blue-600 focus:outline-none p-2 mr-2">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>

                    @auth
                    <a href="#" class="text-gray-600 hover:text-blue-600 relative p-2 mr-2">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute top-1 right-1 h-4 w-4 rounded-full bg-blue-600 flex items-center justify-center text-xs text-white">0</span>
                    </a>
                    @endauth

                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-blue-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-blue-600 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden shadow-lg">
            <div class="pt-2 pb-3 space-y-1 border-t">
                @auth
                <a href="{{ route('dashboard') }}" wire:navigate class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 border-l-4 border-blue-600 text-blue-700' : 'border-l-4 border-transparent text-gray-600' }} block pl-3 pr-4 py-2 text-base font-medium focus:outline-none focus:text-blue-700 focus:bg-blue-50 transition duration-150 ease-in-out">
                    {{ __('Dashboard') }}
                </a>
                @endauth

                <a href="{{ route('marketplace.landing') }}" wire:navigate class="{{ request()->routeIs('marketplace.landing') ? 'bg-blue-50 border-l-4 border-blue-600 text-blue-700' : 'border-l-4 border-transparent text-gray-600' }} block pl-3 pr-4 py-2 text-base font-medium focus:outline-none focus:text-blue-700 focus:bg-blue-50 transition duration-150 ease-in-out">
                    {{ __('Marketplace') }}
                </a>

                <a href="{{ route('marketplace') }}" wire:navigate class="{{ request()->routeIs('marketplace') ? 'bg-blue-50 border-l-4 border-blue-600 text-blue-700' : 'border-l-4 border-transparent text-gray-600' }} block pl-3 pr-4 py-2 text-base font-medium focus:outline-none focus:text-blue-700 focus:bg-blue-50 transition duration-150 ease-in-out">
                    {{ __('Shops') }}
                </a>

                @if(Auth::check() && Auth::user()->shop)
                <a href="{{ route('shop.show', Auth::user()->shop->slug) }}" wire:navigate class="{{ request()->routeIs('shop.show') ? 'bg-blue-50 border-l-4 border-blue-600 text-blue-700' : 'border-l-4 border-transparent text-gray-600' }} block pl-3 pr-4 py-2 text-base font-medium focus:outline-none focus:text-blue-700 focus:bg-blue-50 transition duration-150 ease-in-out">
                    {{ __('My Shop') }}
                </a>
                @endif
            </div>

            <!-- Mobile User Menu -->
            @auth
            <div class="pt-4 pb-3 border-t border-gray-100">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1 border-t border-gray-100 pt-2">
                    <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-blue-700 hover:bg-blue-50 transition duration-150 ease-in-out">
                        {{ __('Profile') }}
                    </a>
                    <a href="{{ route('settings.profile') }}" wire:navigate class="block px-4 py-2 text-base font-medium text-gray-600 hover:text-blue-700 hover:bg-blue-50 transition duration-150 ease-in-out">
                        {{ __('Settings') }}
                    </a>
                    <button wire:click="logout" class="w-full text-start block px-4 py-2 text-base font-medium text-gray-600 hover:text-blue-700 hover:bg-blue-50 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </div>
            </div>
            @else
            <div class="pt-4 pb-3 border-t border-gray-100 bg-gray-50">
                <div class="grid grid-cols-1 gap-2 px-4">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        {{ __('Log In') }}
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        {{ __('Register') }}
                    </a>
                </div>
            </div>
            @endauth
        </div>

        <!-- Search Overlay -->
        <div x-show="searchOpen"
             @click.away="searchOpen = false"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50"
             style="display: none;">
            <div class="absolute inset-x-0 top-0 bg-white shadow-lg p-6 transform transition-all duration-300"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform -translate-y-full"
                 x-transition:enter-end="transform translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="transform translate-y-0"
                 x-transition:leave-end="transform -translate-y-full">
                <div class="max-w-7xl mx-auto flex items-center justify-between">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" class="block w-full bg-gray-100 border-0 rounded-lg pl-10 pr-3 py-3 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition duration-200" placeholder="Search products, shops, categories...">
                    </div>
                    <button @click="searchOpen = false" class="ml-4 p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="max-w-7xl mx-auto mt-6">
                    <h3 class="text-sm font-medium text-gray-500">Popular Searches</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a href="#" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-blue-100 hover:text-blue-800 transition-colors duration-200">
                            Electronics
                        </a>
                        <a href="#" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-blue-100 hover:text-blue-800 transition-colors duration-200">
                            Gaming
                        </a>
                        <a href="#" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-blue-100 hover:text-blue-800 transition-colors duration-200">
                            Home Appliances
                        </a>
                        <a href="#" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-blue-100 hover:text-blue-800 transition-colors duration-200">
                            Best Sellers
                        </a>
                        <a href="#" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800 hover:bg-blue-100 hover:text-blue-800 transition-colors duration-200">
                            New Arrivals
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Fixed height spacer for sticky navbar -->
    <div x-data="{}" x-init="$el.style.height = document.querySelector('nav').offsetHeight + 'px'"></div>
</div>
