<?php

namespace App\Livewire\Marketplace;

use App\Models\Product;
use Livewire\Component;

class FeaturedItems extends Component
{
    public $limit = 10;

    // We'll use Livewire polling to keep featured items updated in real-time
    public function render()
    {
        $featuredProducts = Product::where('is_featured', true)
                                ->orderBy('updated_at', 'desc')
                                ->take($this->limit)
                                ->get();

        return view('livewire.marketplace.featured-items', [
            'featuredProducts' => $featuredProducts
        ]);
    }
}
