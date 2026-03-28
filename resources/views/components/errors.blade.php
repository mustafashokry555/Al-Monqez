@error("$id")
    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
        <span class="alert-inner--icon">
            <i class="fe fe-slash"></i>
        </span>
        <span class="alert-inner--text">
            {{ $message }}
        </span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
@enderror