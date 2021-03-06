@extends('layouts.app')

@section('title', 'Add User')

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


@section('breadcrumbs', Breadcrumbs::render(Route::getCurrentRoute()->getName()))

@section('actions')
    {!! \Html::backButton('backend.settings.users.index') !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            {!! \Form::open(['route' => 'backend.settings.users.store', 'files' => true, 'id' => 'user-form']) !!}
            @include('setting.user.form')
            {!! \Form::close() !!}
        </div>
    </div>
@endsection

@push('component-scripts')

@endpush


@push('page-scripts')

@endpush
