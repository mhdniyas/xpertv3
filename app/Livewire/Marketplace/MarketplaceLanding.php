<?php

namespace App\Livewire\Marketplace;

use Livewire\Component;

class MarketplaceLanding extends Component
{
    public function render()
    {
        return view('livewire.marketplace.marketplace-landing')
            ->layout('layouts.app', [
                'title' => 'Marketplace - Discover Amazing Products',
                'description' => 'Browse our marketplace for the best products from trusted sellers.',
            ]);
    }
}
