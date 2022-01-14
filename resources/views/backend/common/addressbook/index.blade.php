@extends('core::layouts.app')

@section('title', 'AddressBooks')

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
    {!! \Html::linkButton('Add AddressBook', 'core.settings.addressbooks.create', [], 'mdi mdi-plus', 'success') !!}
    {!! \Html::bulkDropdown('core.settings.addressbooks', 0, ['color' => 'warning']) !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($addressbooks))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'core.settings.addressbooks.index',
                            ['placeholder' => 'Search AddressBook Name etc.',
                            'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'addressbook-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="addressbook-table">
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
                                    @forelse($addressbooks as $index => $addressbook)
                                        <tr @if($addressbook->deleted_at != null) class="table-danger" @endif>
                                            <td class="exclude-search align-middle">
                                                {{ $addressbook->id }}
                                            </td>
                                            <td class="text-left">
                                                @can('core.settings.addressbooks.show')
                                                    <a href="{{ route('core.settings.addressbooks.show', $addressbook->id) }}">
                                                        {{ $addressbook->name }}
                                                    </a>
                                                @else
                                                    {{ $addressbook->name }}
                                                @endcan
                                            </td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($addressbook) !!}
                                            </td>
                                            <td class="text-center">{{ $addressbook->created_at->format(config('app.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('core.settings.addressbooks', $addressbook->id, array_merge(['show', 'edit'], ($addressbook->deleted_at == null) ? ['delete'] : ['restore'])) !!}
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
                            {!! \Modules\Core\Supports\CHTML::pagination($addressbooks) !!}
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
    {!! \Modules\Core\Supports\CHTML::confirmModal('AddressBook', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush
