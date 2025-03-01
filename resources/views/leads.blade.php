<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Leads Management</h1>
                    <button type="button" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Lead
                    </button>
                </div>
                
                @livewire('leads-table')
            </div>
        </div>
    </div>
</x-app-layout> 