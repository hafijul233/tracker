@extends('layouts.app')

@section('title', 'Track Loads')

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
    {!! \Html::linkButton('Add TruckLoad', 'backend.transport.track-loads.create', [], 'fas fa-plus', 'success') !!}
    {!! \Html::bulkDropdown('backend.transport.track-loads', 0, ['color' => 'warning']) !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($trackLoads))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'backend.transport.track-loads.index',
                            ['placeholder' => 'Search TruckLoad Name etc.',
                            'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'track-load-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="trackload-table">
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
                                    @forelse($trackLoads as $index => $trackLoad)
                                        <tr @if($trackLoad->deleted_at != null) class="table-danger" @endif>
                                            <td class="exclude-search align-middle">
                                                {{ $trackLoad->id }}
                                            </td>
                                            <td class="text-left">
                                                @can('backend.transport.track-loads.show')
                                                    <a href="{{ route('backend.transport.track-loads.show', $trackLoad->id) }}">
                                                        {{ $trackLoad->name }}
                                                    </a>
                                                @else
                                                    {{ $trackLoad->name }}
                                                @endcan
                                            </td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($trackLoad) !!}
                                            </td>
                                            <td class="text-center">{{ $trackLoad->created_at->format(config('backend.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('backend.transport.track-loads', $trackLoad->id, array_merge(['show', 'edit'], ($trackLoad->deleted_at == null) ? ['delete'] : ['restore'])) !!}
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
                            {!! \App\Supports\CHTML::pagination($trackLoads) !!}
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
    {!! \App\Supports\CHTML::confirmModal('Truck Load', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush
