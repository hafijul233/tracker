@extends('layouts.app')

@section('title', 'Invoices')

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
    {!! \Html::linkButton('Add Invoice', 'core.settings.invoices.create', [], 'mdi mdi-plus', 'success') !!}
    {!! \Html::bulkDropdown('core.settings.invoices', 0, ['color' => 'warning']) !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($invoices))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'core.settings.invoices.index',
                            ['placeholder' => 'Search Invoice Name etc.',
                            'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'invoice-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="invoice-table">
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
                                    @forelse($invoices as $index => $invoice)
                                        <tr @if($invoice->deleted_at != null) class="table-danger" @endif>
                                            <td class="exclude-search align-middle">
                                                {{ $invoice->id }}
                                            </td>
                                            <td class="text-left">
                                                @can('core.settings.invoices.show')
                                                    <a href="{{ route('core.settings.invoices.show', $invoice->id) }}">
                                                        {{ $invoice->name }}
                                                    </a>
                                                @else
                                                    {{ $invoice->name }}
                                                @endcan
                                            </td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($invoice) !!}
                                            </td>
                                            <td class="text-center">{{ $invoice->created_at->format(config('app.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('core.settings.invoices', $invoice->id, array_merge(['show', 'edit'], ($invoice->deleted_at == null) ? ['delete'] : ['restore'])) !!}
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
                            {!! \App\Supports\CHTML::pagination($invoices) !!}
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
    {!! \App\Supports\CHTML::confirmModal('Invoice', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush
