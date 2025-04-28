<?php

namespace App\Livewire;

use App\Models\Shop;
use Livewire\Component;
use Livewire\WithPagination;

class ShopMarketplace extends Component
{
    use WithPagination;
    
    public $searchTerm = '';
    public $locationFilter = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    public $perPage = 12;
    
    public function mount()
    {
        // Component initialization logic if needed
    }
    
    public function render()
    {
        // Get all approved and active shops
        $query = Shop::where('status', 'approved')
            ->where('is_active', true);
            
        // Apply search filter if provided
        if ($this->searchTerm) {
            $query->where(function($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('location', 'like', '%' . $this->searchTerm . '%');
            });
        }
        
        // Apply location filter if provided
        if ($this->locationFilter) {
            $query->where('location', 'like', '%' . $this->locationFilter . '%');
        }
        
        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);
        
        // Get shops with pagination
        $shops = $query->paginate($this->perPage);
        
        // Get unique locations for filter dropdown
        $locations = Shop::where('status', 'approved')
            ->where('is_active', true)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->toArray();
            
        return view('livewire.shop-marketplace', [
            'shops' => $shops,
            'locations' => $locations,
        ]);
    }
    
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }
    
    public function updatedLocationFilter()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
    }
}
