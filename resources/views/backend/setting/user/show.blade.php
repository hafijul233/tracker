@extends('layouts.profile')

@section('title', $user->name ?? 'Details')

@push('meta')

@endpush

@push('webfont')

@endpush

@push('icon')

@endpush

@push('plugin-style')

@endpush

@push('page-style')

@endpush

@section('breadcrumbs', \Breadcrumbs::render(\Route::getCurrentRoute()->getName(), $user))

@section('actions')
    {!! \Html::backButton('backend.settings.users.index') !!}
    {{--    @can('backend.settings.roles.user')
            <a href="#!" data-toggle="modal" data-target="#bd-example-modal-lg"
               class="btn btn-primary m-1 m-md-0">
                <i class="mdi mdi-account-convert-outline"></i>
                <span class="d-none d-md-inline-flex">Add / Remove Roles</span>
            </a>
        @endcan--}}
    {!! \Html::modelDropdown('backend.settings.users', $user->id, ['color' => 'success',
    'actions' => array_merge(['edit'], ($user->deleted_at == null) ? ['delete'] : ['restore'])]) !!}
@endsection

@section('sub-content')
    <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
         aria-labelledby="pills-home-tab">
        <div class="row">
            <div class="col-md-4">
                <label class="d-block">Name</label>
                <p class="fw-bolder">{{ $user->name ?? null }}</p>
            </div>
            <div class="col-md-4">
                <label class="d-block">Guard(s)</label>
                <p class="fw-bolder">{{ $user->guard_name ?? null }}</p>
            </div>
            <div class="col-md-4">
                <label class="d-block">Enabled</label>
                <p class="fw-bolder">{{ \App\Supports\Constant::ENABLED_OPTIONS[$user->enabled] ?? null }}</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <label class="d-block">Remarks</label>
                <p class="fw-bolder">{{ $user->remarks ?? null }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {!! \Form::nText('birth', 'Birth Date', null, false, ['type'=>'text']) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::nDate('anniversary', 'Anniversary', null, false, ['type'=>'text']) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::nSelect('sensitivity', 'Sensitivity', config('contact.sensitivity'), null, false, ['placeholder' => 'Select an Option']) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::nSelect('priority', 'Priority', config('contact.priority'), null, false, ['placeholder' => 'Select an Option']) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::nText('language', 'Language', null, false) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::nUrl('website', 'Website', null, false) !!}
            </div>

        </div>
    </div>
@endsection
<div class="tab-pane fade" id="pills-address-book" role="tabpanel"
     aria-labelledby="pills-address-book-tab">
    <div id="address-book-container">

    </div>
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary">
            Add New Address
        </button>
    </div>
</div>
{{--<div class="tab-pane fade" id="pills-permission" role="tabpanel"
     aria-labelledby="pills-permission-tab">
    <div class="accordion" id="accordionExample">
        @forelse($user->roles as $role)
            <div class="card">
                <h4 class="card-header mb-0 px-1 py-2" id="heading{{ $role->id }}"
                    data-toggle="collapse" data-target="#collapse{{ $role->id }}"
                    aria-expanded="true" aria-controls="collapse{{ $role->id }}">
                    <i class="fa fa-user-check"></i>
                    {{ $role->name }}
                </h4>
                <div id="collapse{{ $role->id }}" class="collapse"
                     aria-labelledby="heading{{ $role->id }}"
                     data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            @forelse($role->permissions as $permission)
                                <div class="col-md-6">
                                    <p class="text-dark fw-bold"
                                       title="{{ $permission->name }}">
                                        <i class="mdi mdi-account-key m-2"></i> {{ $permission->display_name }}
                                    </p>
                                </div>
                            @empty
                                <div class="col-12 text-center fw-bolder">This Role Don't
                                    have any
                                    Permission/Privileges
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center font-weight-bolder">
                This user don't have any role(s) assigned.
            </div>
        @endforelse
    </div>
</div>
<div class="tab-pane fade" id="pills-timeline" role="tabpanel"
     aria-labelledby="pills-timeline-tab">
    @include('layouts.partials.timeline', $timeline)
</div>--}}

@push('plugin-script')

@endpush


@push('page-script')

@endpush
