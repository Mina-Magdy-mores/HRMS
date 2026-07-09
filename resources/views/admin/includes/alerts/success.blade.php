@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show text-right shadow-sm" role="alert">
        <i class="fas fa-check-circle"></i> {{ session()->get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
