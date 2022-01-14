@extends('layouts.app')

@section('title', 'Edit CheckPoint')

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


@section('breadcrumbs', \Breadcrumbs::render(Route::getCurrentRoute()->getName(), $checkpoint))

@section('actions')
    {!! \Html::backButton('core.settings.checkpoints.index') !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    {!! \Form::open(['route' => ['core.settings.checkpoints.update', $checkpoint->id], 'method' => 'put', 'id' => 'checkpoint-form']) !!}
                    @include('setting.checkpoint.form')
                    {!! \Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush
