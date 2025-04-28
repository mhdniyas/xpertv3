<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogger extends Component
{
    use WithPagination;

    public $search = '';
    public $user_filter = '';
    public $action_filter = '';
    public $model_filter = '';
    public $date_start = '';
    public $date_end = '';

    public function render()
    {
        $query = ActivityLog::query()
            ->with('user')
            ->when($this->search, function($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function($q) {
                        $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->user_filter, function($query) {
                $query->where('user_id', $this->user_filter);
            })
            ->when($this->action_filter, function($query) {
                $query->where('action', $this->action_filter);
            })
            ->when($this->model_filter, function($query) {
                $query->where('model_type', $this->model_filter);
            })
            ->when($this->date_start && $this->date_end, function($query) {
                $query->whereBetween('created_at', [$this->date_start, $this->date_end]);
            });

        $logs = $query->latest()->paginate(20);

        // Get unique actions and model types for filters
        $actions = ActivityLog::distinct()->pluck('action')->toArray();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->toArray();
        $users = User::orderBy('name')->get();

        // Format model type names for display
        $formattedModelTypes = [];
        foreach ($modelTypes as $type) {
            $parts = explode('\\', $type);
            $formattedModelTypes[$type] = end($parts);
        }

        return view('livewire.admin.activity-logger', [
            'logs' => $logs,
            'actions' => $actions,
            'modelTypes' => $formattedModelTypes,
            'users' => $users,
        ]);
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'user_filter',
            'action_filter',
            'model_filter',
            'date_start',
            'date_end',
        ]);
    }
}
