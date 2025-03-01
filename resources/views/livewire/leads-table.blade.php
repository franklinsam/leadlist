<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search leads...">
                    <button wire:click="toggleShowFilters" class="btn btn-link">
                        <i class="fas fa-filter"></i> {{ $showFilters ? 'Hide' : 'Show' }} Filters
                    </button>
                </div>
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i> Actions
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" wire:click="export('csv')">
                                <i class="fas fa-file-csv"></i> Export CSV
                            </a></li>
                            @if(count($selectedLeads) > 0)
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" wire:click="deleteSelected">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <div class="dropdown-header">Update Status</div>
                                    @foreach($statusOptions as $value => $label)
                                        <a class="dropdown-item" href="#" wire:click="updateStatus('{{ $value }}')">
                                            {{ $label }}
                                        </a>
                                    @endforeach
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-columns"></i> Columns
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($columns as $column => $enabled)
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" wire:model.live="columns.{{ $column }}">
                                        {{ ucfirst(str_replace('_', ' ', $column)) }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            @if($showFilters)
                <div class="mt-3">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <select wire:model.live="filters.status" class="form-select">
                                <option value="">All Status</option>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" wire:model.live="filters.date_from" class="form-control" placeholder="Date From">
                        </div>
                        <div class="col-md-2">
                            <input type="date" wire:model.live="filters.date_to" class="form-control" placeholder="Date To">
                        </div>
                        <div class="col-md-2">
                            <input type="number" wire:model.live="filters.value_min" class="form-control" placeholder="Min Value">
                        </div>
                        <div class="col-md-2">
                            <input type="number" wire:model.live="filters.value_max" class="form-control" placeholder="Max Value">
                        </div>
                        <div class="col-md-2">
                            <button wire:click="resetFilters" class="btn btn-secondary">Reset Filters</button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" wire:model.live="selectAll">
                            </th>
                            @if($columns['name'])
                                <th wire:click="sortBy('name')" style="cursor: pointer;">
                                    Name
                                    @if($sortField === 'name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            @endif
                            @if($columns['email'])
                                <th wire:click="sortBy('email')" style="cursor: pointer;">
                                    Email
                                    @if($sortField === 'email')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            @endif
                            @if($columns['phone'])
                                <th>Phone</th>
                            @endif
                            @if($columns['company'])
                                <th>Company</th>
                            @endif
                            @if($columns['status'])
                                <th wire:click="sortBy('status')" style="cursor: pointer;">
                                    Status
                                    @if($sortField === 'status')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            @endif
                            @if($columns['value'])
                                <th wire:click="sortBy('value')" style="cursor: pointer;">
                                    Value
                                    @if($sortField === 'value')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            @endif
                            @if($columns['expected_close_date'])
                                <th wire:click="sortBy('expected_close_date')" style="cursor: pointer;">
                                    Expected Close
                                    @if($sortField === 'expected_close_date')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            @endif
                            @if($columns['assigned_to'])
                                <th>Assigned To</th>
                            @endif
                            @if($columns['created_at'])
                                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                                    Created
                                    @if($sortField === 'created_at')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr>
                                <td>
                                    <input type="checkbox" value="{{ $lead->id }}" wire:model.live="selectedLeads">
                                </td>
                                @if($columns['name'])
                                    <td>{{ $lead->name }}</td>
                                @endif
                                @if($columns['email'])
                                    <td>{{ $lead->email }}</td>
                                @endif
                                @if($columns['phone'])
                                    <td>{{ $lead->phone }}</td>
                                @endif
                                @if($columns['company'])
                                    <td>{{ $lead->company }}</td>
                                @endif
                                @if($columns['status'])
                                    <td>{!! $lead->status_badge !!}</td>
                                @endif
                                @if($columns['value'])
                                    <td>${{ number_format($lead->value, 2) }}</td>
                                @endif
                                @if($columns['expected_close_date'])
                                    <td>{{ $lead->expected_close_date?->format('Y-m-d') }}</td>
                                @endif
                                @if($columns['assigned_to'])
                                    <td>{{ $lead->assigned_to }}</td>
                                @endif
                                @if($columns['created_at'])
                                    <td>{{ $lead->created_at->format('Y-m-d') }}</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">
                                                <i class="fas fa-edit"></i> Edit
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="#" 
                                                wire:click="deleteSelected(['{{ $lead->id }}'])"
                                                wire:confirm="Are you sure you want to delete this lead?">
                                                <i class="fas fa-trash"></i> Delete
                                            </a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="text-muted">No leads found</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div>
                {{ $leads->links() }}
            </div>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        </div>
    @endif
</div> 