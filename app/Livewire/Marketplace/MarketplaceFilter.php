<?php

namespace App\Livewire\Marketplace;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class MarketplaceFilter extends Component
{
    use WithPagination;

    // Search Properties
    public $search = '';

    // Filter Properties
    public $category = '';
    public $priceRange = '';
    public $inStock = false;
    public $featured = false;
    public $minRating = 0;

    // Sort Properties
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Listeners for updating filters from other components
    protected $listeners = ['categorySelected' => 'updateCategory'];

    public function mount()
    {
        // Initialize properties if needed
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function updatedPriceRange()
    {
        $this->resetPage();
    }

    public function updateCategory($categoryId)
    {
        $this->category = $categoryId;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->priceRange = '';
        $this->inStock = false;
        $this->featured = false;
        $this->minRating = 0;
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
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

    public function render()
    {
        $categories = Category::all();

        $products = Product::query()
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->when($this->category, fn($query) => $query->where('category_id', $this->category))
            ->when($this->priceRange, function($query) {
                $range = explode('-', $this->priceRange);
                if (count($range) == 2) {
                    return $query->whereBetween('price', [$range[0], $range[1]]);
                }
                return $query;
            })
            ->when($this->inStock, fn($query) => $query->where('stock', '>', 0))
            ->when($this->featured, fn($query) => $query->where('is_featured', true))
            ->when($this->minRating > 0, fn($query) => $query->where('rating', '>=', $this->minRating))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);

        return view('livewire.marketplace.marketplace-filter', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
