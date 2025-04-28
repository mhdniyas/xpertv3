<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSuggestionApproval extends Component
{
    use WithPagination;

    public $product_id;
    public $rejection_reason;
    public $isViewOpen = false;
    public $isRejectOpen = false;
    public $search = '';
    public $category_filter = '';
    public $selectedProduct;

    protected $rules = [
        'rejection_reason' => 'required|min:10',
    ];

    public function render()
    {
        $query = Product::query()
            ->where('global_suggestion', true)
            ->where('is_global', false)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_filter, function($query) {
                $query->where('category_id', $this->category_filter);
            });

        $products = $query->with(['category', 'shop'])->orderBy('created_at', 'desc')->paginate(10);
        $categories = \App\Models\Category::active()->orderBy('name')->get();

        return view('livewire.admin.product-suggestion-approval', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function openViewModal($id)
    {
        $this->selectedProduct = Product::with(['category', 'shop', 'shop.owner'])->findOrFail($id);
        $this->isViewOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedProduct = null;
    }

    public function openRejectModal($id)
    {
        $this->product_id = $id;
        $this->rejection_reason = '';
        $this->isRejectOpen = true;
    }

    public function closeRejectModal()
    {
        $this->isRejectOpen = false;
        $this->rejection_reason = '';
        $this->resetValidation();
    }

    public function approveGlobalProduct($id)
    {
        $product = Product::findOrFail($id);

        // Create a copy of the product as a global product
        $globalProduct = Product::create([
            'name' => $product->name,
            'slug' => $product->slug . '-global',
            'description' => $product->description,
            'price' => $product->price,
            'category_id' => $product->category_id,
            'is_global' => true,
            'is_active' => true,
            'status' => 'approved',
        ]);

        // Update the original product
        $product->global_suggestion = false;
        $product->status = 'approved';
        $product->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'approved',
            $product,
            'Approved product for global catalog: ' . $product->name,
            ['global_suggestion' => true],
            ['global_suggestion' => false, 'status' => 'approved']
        );

        session()->flash('message', 'Product approved and added to global catalog successfully.');
    }

    public function rejectGlobalProduct()
    {
        $this->validate();

        $product = Product::findOrFail($this->product_id);

        // Update the product
        $product->global_suggestion = false;
        $product->status = 'rejected';
        $product->rejection_reason = $this->rejection_reason;
        $product->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'rejected',
            $product,
            'Rejected product from global catalog: ' . $product->name . ' - Reason: ' . $this->rejection_reason,
            ['global_suggestion' => true],
            ['global_suggestion' => false, 'status' => 'rejected', 'rejection_reason' => $this->rejection_reason]
        );

        session()->flash('message', 'Global product suggestion rejected successfully.');
        $this->closeRejectModal();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category_filter']);
    }
}
