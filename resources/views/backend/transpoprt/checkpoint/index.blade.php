@extends('layouts.app')

@section('title', 'CheckPoints')

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



@section('breadcrumbs', \Breadcrumbs::render())

@section('actions')
    {!! \Html::linkButton('Add CheckPoint', 'core.settings.checkpoints.create', [], 'mdi mdi-plus', 'success') !!}
    {!! \Html::bulkDropdown('core.settings.checkpoints', 0, ['color' => 'warning']) !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($checkpoints))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'core.settings.checkpoints.index',
                            ['placeholder' => 'Search CheckPoint Name etc.',
                            'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'checkpoint-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="checkpoint-table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle">
                                            @sortablelink('id', '#')
                                        </th>
                                        <th>@sortablelink('name', 'Name')</th>
                                        <th class="text-center">@sortablelink('enabled', 'Enabled')</th>
                                        <th class="text-center">@sortablelink('created_at', 'Created')</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($checkpoints as $index => $checkpoint)
                                        <tr @if($checkpoint->deleted_at != null) class="table-danger" @endif>
                                            <td class="exclude-search align-middle">
                                                {{ $checkpoint->id }}
                                            </td>
                                            <td class="text-left">
                                                @can('core.settings.checkpoints.show')
                                                    <a href="{{ route('core.settings.checkpoints.show', $checkpoint->id) }}">
                                                        {{ $checkpoint->name }}
                                                    </a>
                                                @else
                                                    {{ $checkpoint->name }}
                                                @endcan
                                            </td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($checkpoint) !!}
                                            </td>
                                            <td class="text-center">{{ $checkpoint->created_at->format(config('app.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('core.settings.checkpoints', $checkpoint->id, array_merge(['show', 'edit'], ($checkpoint->deleted_at == null) ? ['delete'] : ['restore'])) !!}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="exclude-search text-center">No data to display</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent pb-0">
                            {!! \App\Supports\CHTML::pagination($checkpoints) !!}
                        </div>
                    @else
                        <div class="card-body min-vh-100">

                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    {!! \App\Supports\CHTML::confirmModal('CheckPoint', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush
