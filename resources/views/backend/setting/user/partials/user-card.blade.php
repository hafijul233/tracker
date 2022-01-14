@push('plugin-style')
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{ asset('plugins/ekko-lightbox/ekko-lightbox.css') }}">
@endpush

<div class="card border-0">
    <div class="card-body box-profile border-0">
        <div class="text-center">
            <a data-remote="{{ $user->getFirstMediaUrl('avatars') }}" data-toggle="lightbox" data-title="{{ $user->name }}" data-type="image">
                <img class="profile-user-img img-fluid img-circle"
                     src="{{ $user->getFirstMediaUrl('avatars') }}"
                     alt="{{ $user->name }}" width="128">
            </a>
        </div>
        <h3 class="profile-username text-center text-truncate"
            data-toggle="tooltip" data-placement="top" title="{{ $user->name }}">
            {{ $user->name }}
        </h3>

        <p class="text-muted text-center mb-3">
            @<span>{{ $user->username }}</span>
        </p>

        @if($user->email != null)
            <strong><i class="fas fa-envelope mr-1"></i> Email Address</strong>
            <p class="text-muted">
                {{ $user->email }}
            </p>
            <hr>
        @endif

        @if($user->mobile != null)
            <strong><i class="fas fa-mobile mr-1"></i> Mobile Number</strong>
            <p class="text-muted">
                {{ $user->mobile }}
            </p>
            <hr>
        @endif

        @if($user->roles->count() > 0)
            <strong><i class="fas fa-user-check mr-1"></i> Role(s)</strong>
            <p class="text-muted">
                @foreach($user->roles as $role)
                    <a href="{{ route('backend.settings.roles.show', $role->id) }}">{{ $role->name }}</a>,
                @endforeach
            </p>
            <hr>
        @endif

        <strong><i class="far fa-file-alt mr-1"></i> Remarks</strong>

        <p class="text-muted">
            {{ $user->remarks }}
        </p>
    </div>
    <!-- /.card-body -->
</div>

@push('plugin-script')
    <!-- Ekko Lightbox -->
    <script src="{{ asset('plugins/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
@endpush

@push('page-script')
    <script>
        $(function () {
            $(document).on('click', '[data-toggle="lightbox"]', function (event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
        });
    </script>
@endpush
