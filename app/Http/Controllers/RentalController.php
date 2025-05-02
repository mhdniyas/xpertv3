<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\RentalProduct;
use App\Models\RentalBooking;
use App\Models\RentalCustomerProfile;

class RentalController extends Controller
{
    /**
     * Display a listing of all rental shops.
     */
    public function index()
    {
        $shops = Shop::where('is_active', true)
            ->where('status', 'approved')
            ->get();

        return view('rentals.index', compact('shops'));
    }

    /**
     * Display the rental dashboard for a specific shop.
     */
    public function dashboard(Shop $shop)
    {
        // Get rental statistics
        $totalProducts = RentalProduct::where('shop_id', $shop->id)->count();
        $activeBookings = RentalBooking::where('shop_id', $shop->id)
            ->whereIn('booking_status', ['confirmed', 'picked-up'])
            ->count();
        $totalCustomers = RentalCustomerProfile::where('shop_id', $shop->id)->count();

        return view('rentals.dashboard', compact('shop', 'totalProducts', 'activeBookings', 'totalCustomers'));
    }

    /**
     * Display the rental products for a specific shop.
     */
    public function products(Shop $shop)
    {
        $products = RentalProduct::where('shop_id', $shop->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rentals.products', compact('shop', 'products'));
    }

    /**
     * Display the rental bookings for a specific shop.
     */
    public function bookings(Shop $shop)
    {
        $bookings = RentalBooking::where('shop_id', $shop->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rentals.bookings', compact('shop', 'bookings'));
    }

    /**
     * Display the rental customers for a specific shop.
     */
    public function customers(Shop $shop)
    {
        $customers = RentalCustomerProfile::where('shop_id', $shop->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('rentals.customers', compact('shop', 'customers'));
    }
}
