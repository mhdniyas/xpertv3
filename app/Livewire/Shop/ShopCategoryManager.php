<?php

namespace App\Livewire\Shop;

use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShopCategoryManager extends Component
{
    use WithPagination, AuthorizesRequests;

    public $shop;
    public $showModal = false;
    public $editMode = false;
    public $categoryId;
    public $name;
    public $parentId;
    public $status = 'active';
    public $searchTerm = '';
    public $perPage = 10;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'parentId' => 'nullable|exists:shop_categories,id',
        'status' => 'required|in:active,inactive',
    ];

    public function mount(Shop $shop)
    {
        $this->shop = $shop;

        // Authorize that the current user can manage categories for this shop
        $this->authorize('manageCategories', $shop);
    }

    public function render()
    {
        $parentCategories = ShopCategory::where('shop_id', $this->shop->id)
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $categories = ShopCategory::where('shop_id', $this->shop->id)
            ->when($this->searchTerm, function($query) {
                return $query->where('name', 'like', '%' . $this->searchTerm . '%');
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.shop.shop-category-manager', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ]);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function resetForm()
    {
        $this->editMode = false;
        $this->categoryId = null;
        $this->name = '';
        $this->parentId = null;
        $this->status = 'active';
    }

    public function edit(ShopCategory $category)
    {
        $this->authorize('update', $category);

        $this->resetValidation();
        $this->editMode = true;
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->parentId = $category->parent_id;
        $this->status = $category->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editMode) {
            $category = ShopCategory::findOrFail($this->categoryId);
            $this->authorize('update', $category);

            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'parent_id' => $this->parentId,
                'status' => $this->status,
            ]);

            $this->dispatch('toast', 'Category updated successfully!', 'success');
        } else {
            // Authorize creation
            $this->authorize('manageCategories', $this->shop);

            // Check for circular reference when creating nested categories
            if ($this->parentId && $this->isCircularReference($this->parentId)) {
                $this->addError('parentId', 'This would create a circular reference');
                return;
            }

            ShopCategory::create([
                'shop_id' => $this->shop->id,
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'parent_id' => $this->parentId,
                'status' => $this->status,
                'created_by' => auth()->id(),
            ]);

            $this->dispatch('toast', 'Category created successfully!', 'success');
        }

        $this->closeModal();
    }

    public function delete(ShopCategory $category)
    {
        $this->authorize('delete', $category);

        // Check if the category has products or children
        if ($category->products()->count() > 0) {
            $this->dispatch('toast', 'Cannot delete category with associated products!', 'error');
            return;
        }

        if ($category->children()->count() > 0) {
            $this->dispatch('toast', 'Cannot delete category with child categories!', 'error');
            return;
        }

        $category->delete();
        $this->dispatch('toast', 'Category deleted successfully!', 'success');
    }

    public function toggleStatus(ShopCategory $category)
    {
        $this->authorize('update', $category);

        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update(['status' => $newStatus]);

        $statusText = $newStatus === 'active' ? 'activated' : 'deactivated';
        $this->dispatch('toast', "Category {$statusText} successfully!", 'success');
    }

    private function isCircularReference($parentId, $childId = null)
    {
        if (is_null($childId)) {
            return false;
        }

        if ($parentId == $childId) {
            return true;
        }

        $parent = ShopCategory::find($parentId);

        if (!$parent || is_null($parent->parent_id)) {
            return false;
        }

        return $this->isCircularReference($parent->parent_id, $childId);
    }
}
