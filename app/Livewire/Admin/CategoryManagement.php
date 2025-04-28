<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryManagement extends Component
{
    use WithPagination;

    public $name;
    public $slug;
    public $description;
    public $parent_id = null;
    public $is_active = true;
    public $category_id;
    public $isOpen = false;
    public $isDeleteOpen = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|min:3',
        'slug' => 'nullable',
        'description' => 'nullable',
        'parent_id' => 'nullable|exists:categories,id',
        'is_active' => 'boolean',
    ];

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        $parentCategories = Category::parents()->orderBy('name')->get();

        return view('livewire.admin.category-management', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
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
        $this->category_id = $id;
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
        $this->parent_id = null;
        $this->is_active = true;
        $this->category_id = null;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }

        $category = Category::updateOrCreate(['id' => $this->category_id], [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'is_active' => $this->is_active,
        ]);

        // Log activity
        ActivityLog::log(
            auth()->id(),
            $this->category_id ? 'updated' : 'created',
            $category,
            $this->category_id ? 'Updated category: ' . $category->name : 'Created category: ' . $category->name
        );

        session()->flash('message', $this->category_id ? 'Category updated successfully.' : 'Category created successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->parent_id = $category->parent_id;
        $this->is_active = $category->is_active;

        $this->openModal();
    }

    public function delete()
    {
        $category = Category::findOrFail($this->category_id);

        // Check if category has children or products
        if ($category->children()->count() > 0 || $category->products()->count() > 0) {
            session()->flash('error', 'Cannot delete category with associated subcategories or products.');
            $this->closeDeleteModal();
            return;
        }

        // Log activity before deletion
        ActivityLog::log(
            auth()->id(),
            'deleted',
            $category,
            'Deleted category: ' . $category->name
        );

        $category->delete();

        session()->flash('message', 'Category deleted successfully.');
        $this->closeDeleteModal();
    }

    public function toggleActive($id)
    {
        $category = Category::findOrFail($id);
        $oldStatus = $category->is_active;
        $category->is_active = !$category->is_active;
        $category->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'updated',
            $category,
            'Changed category status: ' . $category->name . ' from ' . ($oldStatus ? 'active' : 'inactive') . ' to ' . ($category->is_active ? 'active' : 'inactive'),
            ['is_active' => $oldStatus],
            ['is_active' => $category->is_active]
        );

        session()->flash('message', 'Category status updated successfully.');
    }
}
