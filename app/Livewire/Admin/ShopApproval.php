<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Shop;
use Livewire\Component;
use Livewire\WithPagination;

class ShopApproval extends Component
{
    use WithPagination;

    public $shop_id;
    public $rejection_reason;
    public $isViewOpen = false;
    public $isRejectOpen = false;
    public $search = '';
    public $status_filter = '';
    public $selectedShop;

    protected $rules = [
        'rejection_reason' => 'required|min:10',
    ];

    public function render()
    {
        $query = Shop::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            })
            ->when($this->status_filter, function($query) {
                $query->where('status', $this->status_filter);
            });

        $shops = $query->with('owner')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.shop-approval', [
            'shops' => $shops,
        ]);
    }

    public function openViewModal($id)
    {
        $this->selectedShop = Shop::with('owner')->findOrFail($id);
        $this->isViewOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewOpen = false;
        $this->selectedShop = null;
    }

    public function openRejectModal($id)
    {
        $this->shop_id = $id;
        $this->rejection_reason = '';
        $this->isRejectOpen = true;
    }

    public function closeRejectModal()
    {
        $this->isRejectOpen = false;
        $this->rejection_reason = '';
        $this->resetValidation();
    }

    public function approveShop($id)
    {
        $shop = Shop::findOrFail($id);

        if ($shop->status === 'approved') {
            session()->flash('error', 'Shop is already approved.');
            return;
        }

        $oldStatus = $shop->status;
        $shop->status = 'approved';
        $shop->is_active = true;
        $shop->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'approved',
            $shop,
            'Approved shop: ' . $shop->name,
            ['status' => $oldStatus, 'is_active' => false],
            ['status' => 'approved', 'is_active' => true]
        );

        session()->flash('message', 'Shop approved successfully.');
    }

    public function rejectShop()
    {
        $this->validate();

        $shop = Shop::findOrFail($this->shop_id);

        if ($shop->status === 'rejected') {
            session()->flash('error', 'Shop is already rejected.');
            $this->closeRejectModal();
            return;
        }

        $oldStatus = $shop->status;
        $shop->status = 'rejected';
        $shop->rejection_reason = $this->rejection_reason;
        $shop->is_active = false;
        $shop->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'rejected',
            $shop,
            'Rejected shop: ' . $shop->name . ' - Reason: ' . $this->rejection_reason,
            ['status' => $oldStatus],
            ['status' => 'rejected', 'rejection_reason' => $this->rejection_reason]
        );

        session()->flash('message', 'Shop rejected successfully.');
        $this->closeRejectModal();
    }

    public function toggleActive($id)
    {
        $shop = Shop::findOrFail($id);
        $oldStatus = $shop->is_active;
        $shop->is_active = !$shop->is_active;
        $shop->save();

        // Log activity
        ActivityLog::log(
            auth()->id(),
            'updated',
            $shop,
            'Changed shop status: ' . $shop->name . ' from ' . ($oldStatus ? 'active' : 'inactive') . ' to ' . ($shop->is_active ? 'active' : 'inactive'),
            ['is_active' => $oldStatus],
            ['is_active' => $shop->is_active]
        );

        session()->flash('message', 'Shop status updated successfully.');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'status_filter']);
    }
}
