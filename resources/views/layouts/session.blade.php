@if (session('message'))
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <span class="alert-inner--icon">
            <i class="fe fe-info"></i>
        </span>
        <span class="alert-inner--text">
            {{ session('message') }}
        </span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <span class="alert-inner--icon">
            <i class="fe fe-slash"></i>
        </span>
        <span class="alert-inner--text">
            {{ session('error') }}
        </span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <span class="alert-inner--icon">
            <i class="fe fe-thumbs-up"></i>
        </span>
        <span class="alert-inner--text">
            {{ session('success') }}
        </span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@endif
