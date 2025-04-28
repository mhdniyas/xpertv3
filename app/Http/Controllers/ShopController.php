<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\ShopProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display the marketplace of shops
     */
    public function index()
    {
        return view('shops.marketplace');
    }
    
    /**
     * Display a specific shop and its products.
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $shop = Shop::where('slug', $slug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();
            
        return view('shops.show', compact('shop'));
    }
    
    /**
     * Display a specific product from a shop
     *
     * @param string $shopSlug
     * @param string $productSlug
     * @return \Illuminate\View\View
     */
    public function showProduct($shopSlug, $productSlug)
    {
        $shop = Shop::where('slug', $shopSlug)
            ->where('status', 'approved')
            ->where('is_active', true)
            ->firstOrFail();
            
        $product = ShopProduct::where('shop_id', $shop->id)
            ->where('slug', $productSlug)
            ->where('status', 'active')
            ->firstOrFail();
            
        // Get related products from the same shop and category (if available)
        $relatedQuery = ShopProduct::where('shop_id', $shop->id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->latest();
            
        // If product has a category, prioritize products from same category
        if ($product->shop_category_id) {
            $relatedQuery->orderByRaw("CASE WHEN shop_category_id = ? THEN 0 ELSE 1 END", [$product->shop_category_id]);
        }
        
        $relatedProducts = $relatedQuery->limit(4)->get();
            
        return view('shops.products.show', compact('shop', 'product', 'relatedProducts'));
    }
    
    /**
     * Check if user has permission to manage the shop
     * 
     * @param Shop $shop
     * @return bool
     */
    protected function canManageShop(Shop $shop)
    {
        $user = Auth::user();
        
        // Owner has full permissions
        if ($shop->owner_id == $user->id) {
            return true;
        }
        
        // Staff permissions based on role
        $staffRole = $shop->staff()->where('user_id', $user->id)->first()?->pivot?->role;
        
        return $staffRole === 'manager' || $user->hasRole('admin') || $user->hasRole('superadmin');
    }
}
