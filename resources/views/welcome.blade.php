<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <header class="bg-white shadow dark:bg-gray-800">
            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Welcome to {{ config('app.name', 'LeagueXpert') }}
                </h1>
            </div>
        </header>

        <!-- New Rental Hero Section -->
        <div class="relative bg-blue-600 text-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-blue-600 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-blue-600 transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polygon points="50,0 100,0 50,100 0,100" />
                    </svg>

                    <div class="px-4 pt-10 sm:px-6 sm:pt-12 md:pt-16 lg:px-8 lg:pt-20 xl:pt-28">
                        <div class="sm:text-center lg:text-left">
                            <h2 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                                <span class="block">Equipment Rental</span>
                                <span class="block text-blue-200">Made Simple</span>
                            </h2>
                            <p class="mt-3 text-base text-blue-200 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Easily manage your equipment rental business. Track inventory, process bookings, and build customer loyalty with our complete rental management system.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    @auth
                                    <a href="{{ route('shops.rentals') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-gray-100 md:py-4 md:text-lg md:px-10">
                                        Manage Rentals
                                    </a>
                                    @else
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-gray-100 md:py-4 md:text-lg md:px-10">
                                        Get Started
                                    </a>
                                    @endauth
                                </div>
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-500 hover:bg-blue-700 md:py-4 md:text-lg md:px-10">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                <img class="h-full w-full object-cover" src="https://images.unsplash.com/photo-1603203040743-24aced6793a4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80" alt="Equipment rental">
            </div>
        </div>

        <main class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Authentication Status -->
            <div class="p-4 mb-8 bg-white rounded-lg shadow dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Authentication</h2>
                    <div>
                        @if (Route::has('login'))
                            <div class="flex space-x-4">
                                @auth
                                    <span class="inline-flex items-center px-4 py-2 text-green-800 bg-green-100 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Logged in as {{ Auth::user()->name }}
                                    </span>
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Public Routes -->
            <div class="mb-8">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 dark:text-white">Public Routes</h2>
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Route Name</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Description</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Home</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Welcome page</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-blue-700 uppercase transition duration-150 ease-in-out bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:bg-blue-200 active:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Marketplace</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Shop marketplace</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('marketplace') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-blue-700 uppercase transition duration-150 ease-in-out bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:bg-blue-200 active:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Marketplace Landing</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">New marketplace landing page</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('marketplace.landing') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-blue-700 uppercase transition duration-150 ease-in-out bg-blue-100 border border-transparent rounded-md hover:bg-blue-200 focus:bg-blue-200 active:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Protected Routes (Only visible to authenticated users) -->
            @auth
            <div class="mb-8">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 dark:text-white">Protected Routes</h2>
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Route Name</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Description</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Access Level</th>
                                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-300">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Main dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">All Users</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-indigo-700 uppercase transition duration-150 ease-in-out bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:bg-indigo-200 active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Profile</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">User profile</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">All Users</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('profile') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-indigo-700 uppercase transition duration-150 ease-in-out bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:bg-indigo-200 active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">User Dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Regular user dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">All Users</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('user.dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-indigo-700 uppercase transition duration-150 ease-in-out bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:bg-indigo-200 active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>

                            <!-- Shop Management Routes -->
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Shop Management</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Manage your shops</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">All Users</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('shops.manage') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-indigo-700 uppercase transition duration-150 ease-in-out bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:bg-indigo-200 active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>

                            <!-- Updated Rental Management Routes -->
                            <tr class="bg-blue-50 dark:bg-blue-900">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Rental Management</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Comprehensive rental system for equipment</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Manager+</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('shops.rentals') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-purple-700 uppercase transition duration-150 ease-in-out bg-purple-100 border border-transparent rounded-md hover:bg-purple-200 focus:bg-purple-200 active:bg-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Visit
                                    </a>
                                </td>
                            </tr>

                            <!-- Admin Routes -->
                            @can('isAdmin')
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Admin Dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Admin control panel</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Admin+</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out border border-transparent rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200 focus:bg-amber-200 active:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">User Management</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Manage system users</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Admin+</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('admin.users') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out border border-transparent rounded-md bg-amber-100 text-amber-700 hover:bg-amber-200 focus:bg-amber-200 active:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            @endcan

                            <!-- Manager Routes -->
                            @can('isManager')
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Manager Dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Manager control panel</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Manager+</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('manager.dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out border border-transparent rounded-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 focus:bg-emerald-200 active:bg-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            @endcan

                            <!-- Superadmin Routes -->
                            @can('isSuperadmin')
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-gray-100">Superadmin Dashboard</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Superadmin control panel</td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">Superadmin only</td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <a href="{{ route('superadmin.dashboard') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-red-700 uppercase transition duration-150 ease-in-out bg-red-100 border border-transparent rounded-md hover:bg-red-200 focus:bg-red-200 active:bg-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        Visit
                                    </a>
                                </td>
                            </tr>
                            @endcan
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="p-6 mb-8 bg-white rounded-lg shadow dark:bg-gray-800">
                <p class="text-gray-600 dark:text-gray-400">Please log in to see protected routes.</p>
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Log in now
                    </a>
                </div>
            </div>
            @endauth

            <!-- Feature Cards -->
            <div id="features" class="mt-8">
                <h2 class="mb-6 text-2xl font-bold text-gray-800 dark:text-white">Key Features</h2>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    <!-- User Management -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-500 rounded-md shrink-0">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Management</h3>
                                </div>
                            </div>
                            <div class="ml-12">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Complete user management with roles and permissions.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Shop Marketplace -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-purple-500 rounded-md shrink-0">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Shop Marketplace</h3>
                                </div>
                            </div                            <div class="ml-12">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Browse and manage shops and products in our marketplace.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Multi-role Dashboards -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-500 rounded-md shrink-0">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Role-based Dashboards</h3>
                                </div>
                            </div>
                            <div class="ml-12">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Specialized dashboards for users, managers, admins, and superadmins.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- NEW: Equipment Rental System -->
                    <div class="overflow-hidden bg-white rounded-lg shadow-sm dark:bg-gray-800 ring-2 ring-blue-500">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-500 rounded-md shrink-0">
                                    <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Equipment Rental</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        New
                                    </span>
                                </div>
                            </div>
                            <div class="ml-12">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Complete rental system with bookings, inventory tracking, and customer loyalty program.
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('shops.rentals') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                        Learn more →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NEW: Rental System Features -->
            <div class="mt-12 bg-white rounded-lg shadow-sm overflow-hidden dark:bg-gray-800">
                <div class="px-6 py-8 bg-gradient-to-r from-blue-500 to-blue-700">
                    <h2 class="text-2xl font-bold text-white">Complete Rental Management System</h2>
                    <p class="mt-2 text-blue-100">Everything you need to run your equipment rental business</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Inventory Management</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Track equipment availability, maintenance status, and rental rates.
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Booking System</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Create and manage rental bookings with flexible pricing options.
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Customer Loyalty</h3>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Reward frequent customers with a tiered loyalty program and priority points.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="text-sm text-center text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ config('app.name', 'LeagueXpert') }}. All rights reserved.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
