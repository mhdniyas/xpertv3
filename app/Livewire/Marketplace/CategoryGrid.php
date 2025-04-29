<?php

namespace App\Livewire\Marketplace;

use App\Models\Category;
use Livewire\Component;

class CategoryGrid extends Component
{
    // Array of available icons for categories
    protected $icons = [
        'electronics' => 'device-mobile',
        'fashion' => 'shopping-bag',
        'home' => 'home',
        'beauty' => 'sparkles',
        'sports' => 'fire',
        'books' => 'book-open',
        'toys' => 'puzzle',
        'automotive' => 'truck',
        'garden' => 'leaf',
        'health' => 'heart',
        'jewelry' => 'gift',
        'default' => 'collection'
    ];

    public function selectCategory($categoryId)
    {
        // Emit event to the MarketplaceFilter component
        $this->dispatch('categorySelected', $categoryId);
    }

    public function getIconForCategory($slug)
    {
        $slug = strtolower($slug);

        // Try to find a matching icon or use default
        foreach ($this->icons as $key => $icon) {
            if (str_contains($slug, $key)) {
                return $icon;
            }
        }

        return $this->icons['default'];
    }

    public function render()
    {
        $categories = Category::withCount('products')
                            ->having('products_count', '>', 0)
                            ->orderBy('products_count', 'desc')
                            ->take(12)
                            ->get();

        return view('livewire.marketplace.category-grid', [
            'categories' => $categories
        ]);
    }
}
