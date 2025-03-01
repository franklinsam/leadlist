<?php

namespace App\Livewire;

use App\Models\Lead;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;

class LeadsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $selectedLeads = [];
    public $selectAll = false;
    public $showFilters = false;
    public $filters = [
        'status' => '',
        'date_from' => '',
        'date_to' => '',
        'value_min' => '',
        'value_max' => '',
    ];
    public $columns = [
        'name' => true,
        'email' => true,
        'phone' => true,
        'company' => true,
        'status' => true,
        'value' => true,
        'expected_close_date' => true,
        'assigned_to' => true,
        'created_at' => true,
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedLeads = $this->leads->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedLeads = [];
        }
    }

    public function updatedSelectedLeads()
    {
        $this->selectAll = false;
    }

    public function toggleShowFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function deleteSelected()
    {
        Lead::whereIn('id', $this->selectedLeads)->delete();
        $this->selectedLeads = [];
        $this->selectAll = false;
        session()->flash('message', 'Selected leads have been deleted.');
    }

    public function updateStatus($status)
    {
        Lead::whereIn('id', $this->selectedLeads)->update(['status' => $status]);
        $this->selectedLeads = [];
        $this->selectAll = false;
        session()->flash('message', 'Status updated for selected leads.');
    }

    public function export($format)
    {
        return response()->streamDownload(function() use ($format) {
            echo $this->generateExport($format);
        }, 'leads.' . $format);
    }

    private function generateExport($format)
    {
        $leads = $this->leads;
        $headers = ['ID', 'Name', 'Email', 'Phone', 'Company', 'Status', 'Value', 'Expected Close Date', 'Assigned To', 'Created At'];
        
        if ($format === 'csv') {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            
            foreach ($leads as $lead) {
                fputcsv($output, [
                    $lead->id,
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->company,
                    $lead->status,
                    $lead->value,
                    $lead->expected_close_date,
                    $lead->assigned_to,
                    $lead->created_at
                ]);
            }
            fclose($output);
        }
    }

    public function getLeadsProperty()
    {
        return Lead::query()
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('company', 'like', '%'.$this->search.'%')
                        ->orWhere('phone', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filters['status'], fn($query) => 
                $query->where('status', $this->filters['status'])
            )
            ->when($this->filters['date_from'], fn($query) => 
                $query->where('created_at', '>=', Carbon::parse($this->filters['date_from']))
            )
            ->when($this->filters['date_to'], fn($query) => 
                $query->where('created_at', '<=', Carbon::parse($this->filters['date_to']))
            )
            ->when($this->filters['value_min'], fn($query) => 
                $query->where('value', '>=', $this->filters['value_min'])
            )
            ->when($this->filters['value_max'], fn($query) => 
                $query->where('value', '<=', $this->filters['value_max'])
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.leads-table', [
            'leads' => $this->leads,
            'statusOptions' => Lead::getStatusOptions(),
        ]);
    }
} 