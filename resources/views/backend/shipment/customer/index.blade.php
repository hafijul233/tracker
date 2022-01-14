@extends('layouts.app')

@section('title', 'Customers')

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
    {!! \Html::linkButton('Add Customer', 'backend.shipment.customers.create', [], 'fas fa-plus', 'success') !!}
    {!! \Html::bulkDropdown('backend.shipment.customers', 0, ['color' => 'warning']) !!}

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    @if(!empty($customers))
                        <div class="card-body p-0">
                            {!! \Html::cardSearch('search', 'backend.shipment.customers.index',
        ['placeholder' => 'Search Role Name, Code, Guard, Status, etc.',
        'class' => 'form-control', 'id' => 'search', 'data-target-table' => 'customer-table']) !!}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="customer-table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="align-middle">
                                            @sortablelink('id', '#')
                                        </th>
                                        <th class="pl-0">@sortablelink('name', 'Name')</th>
                                        <th class="text-center">@sortablelink('mobile', 'Mobile')</th>
                                        <th class="text-center">@sortablelink('roles.name', 'Role')</th>
                                        <th class="text-center">@sortablelink('email', 'Email')</th>
                                        <th class="text-center">@sortablelink('enabled', 'Enabled')</th>
                                        <th class="text-center">@sortablelink('created_at', 'Created')</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($customers as $customer)
                                        <tr @if($customer->deleted_at != null) class="table-danger" @endif >
                                            <td class="exclude-search align-middle">
                                                {{ $customer->id }}
                                            </td>
                                            <td class="text-left pl-0">
                                                <div class="media">
                                                    <img class="align-self-center mr-1 img-circle direct-chat-img elevation-1"
                                                         src="{{ $customer->getFirstMediaUrl('avatars') }}"
                                                         alt="{{ $customer->name }}">
                                                    <div class="media-body">
                                                        <p class="my-0">
                                                            @if(auth()->user()->can('backend.shipment.customers.show') || $customer->id == auth()->user()->id)
                                                                <a href="{{ route('backend.shipment.customers.show', $customer->id) }}">
                                                                    {{ $customer->name }}
                                                                </a>
                                                            @else
                                                                {{ $customer->name }}
                                                            @endif
                                                        </p>
                                                        <p class="mb-0 small">{{ $customer->customername }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $customer->mobile ?? '-' }}</td>
                                            <td class="text-center">
                                                {!! \App\Supports\CHTML::displayTags($customer->roles->pluck('name')->toArray(), 'fas fa-customer-secret') !!}
                                            </td>
                                            <td class="text-left">{{ $customer->email ?? '-' }}</td>
                                            <td class="text-center exclude-search">
                                                {!! \Html::enableToggle($customer) !!}
                                            </td>
                                            <td class="text-center">{{ $customer->created_at->format(config('backend.datetime')) ?? '' }}</td>
                                            <td class="exclude-search pr-3 text-center align-middle">
                                                {!! \Html::actionDropdown('backend.shipment.customers', $customer->id, array_merge(['show', 'edit'], ($customer->deleted_at == null) ? ['delete'] : ['restore'])) !!}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="exclude-search text-center">No data to display</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent pb-0">
                            {!! \App\Supports\CHTML::pagination($customers) !!}
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
    {!! \App\Supports\CHTML::confirmModal('Customer', ['export', 'delete', 'restore']) !!}
@endsection


@push('plugin-script')

@endpush

@push('page-script')

@endpush