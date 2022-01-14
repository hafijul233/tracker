@extends('layouts.app')

@section('title', 'Items')

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
    {!! \Html::linkButton('Add Item', 'core.settings.items.create', [], 'mdi mdi-plus', 'success') !!}
    {!! \Html::bulkDropdown('core.settings.items', 0, ['color' => 'warning']) !!}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($items))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'core.settings.items.index',
                            ['placeholder' => 'Search Item Name etc.',
                            'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'item-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="item-table">
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
                                    @forelse($items as $index => $item)
                                        <tr @if($item->deleted_at != null) class="table-danger" @endif>
                                            <td class="exclude-search align-middle">
                                                {{ $item->id }}
                                            </td>
                                            <td class="text-left">
                                                @can('core.settings.items.show')
                                                    <a href="{{ route('core.settings.items.show', $item->id) }}">
                                                        {{ $item->name }}
                                                    </a>
                                                @else
                                                    {{ $item->name }}
                                                @endcan
                                            </td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($item) !!}
                                            </td>
                                            <td class="text-center">{{ $item->created_at->format(config('app.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('core.settings.items', $item->id, array_merge(['show', 'edit'], ($item->deleted_at == null) ? ['delete'] : ['restore'])) !!}
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
                            {!! \Modules\Core\Supports\CHTML::pagination($items) !!}
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
    {!! \Modules\Core\Supports\CHTML::confirmModal('Item', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush