<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManagement extends Component
{
    use WithPagination;

    public $name;
    public $slug;
    public $description;
    public $price;
    public $category_id;
    public $is_active = true;
    public $is_global = true;
    public $product_id;
    public $isOpen = false;
    public $isDeleteOpen = false;
    public $search = '';
    public $category_filter = '';
    public $status_filter = '';

    protected $rules = [
        'name' => 'required|min:3',
        'slug' => 'nullable',
        'description' => 'nullable',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'is_active' => 'boolean',
        'is_global' => 'boolean',
    ];

    public function render()
    {
        $query = Product::query()
            ->where('is_global', true)
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->category_filter, function($query) {
                $query->where('category_id', $this->category_filter);
            })
            ->when($this->status_filter !== '', function($query) {
                $query->where('is_active', (bool) $this->status_filter);
            });

        $products = $query->paginate(10);
        $categories = Category::active()->orderBy('name')->get();

        return view('livewire.admin.product-management', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function openDeleteModal($id)
    {
        $this->product_id = $id;
        $this->isDeleteOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteOpen = false;
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->price = '';
        $this->category_id = '';
        $this->is_active = true;
        $this->is_global = true;
        $this->product_id = null;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }

        $product = Product::updateOrCreate(['id' => $this->product_id], [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
            'is_global' => $this->is_global,
            'status' => 'approved',
        ]);

        // Log activity
        ActivityLog::log(
            auth()->id(),
            $this->product_id ? 'updated' : 'created',
            $product,
            $this->product_id ? 'Updated global product: ' . $product->name : 'Created global product: ' . $product->name
        );

        session()->flash('message', $this->product_id ? 'Product updated successfully.' : 'Product created successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->category_id = $product->category_id;
        $this->is_active = $product->is_active;
        $this->is_global = $product->is_global;

        $this->openModal();
    }

    public function delete()
    {
        $product = Product::findOrFail($this->product_id);

        // Log activity before deletion
        ActivityLog::log(
            auth()->id(),
            'deleted',
            $product,
            'Deleted global product: ' . $product->name
        );

        $product->delete();

        session()->flash('message', 'Product deleted successfully.');
        $this->closeDeleteModal();
    }

    public function toggleActive($id)
    {
        $product = Product::findOrFail($id);
        $oldStatus = $product->is_active;
        $product->is_active = !$product->is_active;
        $product->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'updated',
            $product,
            'Changed product status: ' . $product->name . ' from ' . ($oldStatus ? 'active' : 'inactive') . ' to ' . ($product->is_active ? 'active' : 'inactive'),
            ['is_active' => $oldStatus],
            ['is_active' => $product->is_active]
        );

        session()->flash('message', 'Product status updated successfully.');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category_filter', 'status_filter']);
    }
}
