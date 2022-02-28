@extends('layouts.app')

@section('title', 'Employees')

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
    {!! \Html::linkButton('Add Employee', 'core.settings.employees.create', [], 'fas fa-plus', 'success') !!}
    {!! \Html::bulkDropdown('core.settings.employees', 0, ['color' => 'warning']) !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($employees))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'core.settings.employees.index',
                            ['placeholder' => 'Search Employee Name etc.',
                            'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'employee-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="employee-table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle">
                                            @sortablelink('id', '#')
                                        </th>
                                        <th>@sortablelink('name', __('common.Name'))</th>
                                        <th class="text-center">@sortablelink('enabled', __('common.Enabled'))</th>
                                        <th class="text-center">@sortablelink('created_at', __('common.Created'))</th>
                                        <th class="text-center">{!! __('common.Actions') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($employees as $index => $employee)
                                        <tr @if($employee->deleted_at != null) class="table-danger" @endif>
                                            <td class="exclude-search align-middle">
                                                {{ $employee->id }}
                                            </td>
                                            <td class="text-left">
                                                @can('core.settings.employees.show')
                                                    <a href="{{ route('core.settings.employees.show', $employee->id) }}">
                                                        {{ $employee->name }}
                                                    </a>
                                                @else
                                                    {{ $employee->name }}
                                                @endcan
                                            </td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($employee) !!}
                                            </td>
                                            <td class="text-center">{{ $employee->created_at->format(config('backend.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('core.settings.employees', $employee->id, array_merge(['show', 'edit'], ($employee->deleted_at == null) ? ['delete'] : ['restore'])) !!}
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
                            {!! \App\Supports\CHTML::pagination($employees) !!}
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
    {!! \App\Supports\CHTML::confirmModal('Employee', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush
