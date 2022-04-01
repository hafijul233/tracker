<div class="row">
    <div class="col-md-6">
        {!! \Form::nSelect('customer_id', 'Customer', [], old('user_id', ($invoice->user_id ?? null)),
true, ['placeholder' => 'Select Customer']) !!}
    </div>
    <div class="col-md-6">
        {!! \Form::nText('name', __('common.Name'), old('name', $item->name ?? null), true) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        {!! \Form::nNumber('rate', 'Rate', old('rate', $item->rate ?? null), true, ['min' => 0, 'step' => '0.01']) !!}
    </div>
    <div class="col-md-6">
        {!! \Form::nText('dimension', 'Dimension (Length X Width X Height) CM', old('dimension', $item->dimension ?? null), false) !!}
    </div>
    <div class="col-md-6">
        {!! \Form::nSelect('tax', 'Tax', \App\Supports\Constant::ENABLED_OPTIONS,
            old('tax', ($item->enabled ?? \App\Supports\Constant::ENABLED_OPTION)), false) !!}
    </div>
    <div class="col-md-6">
        {!! \Form::nSelect('enabled', __('common.Enabled'), \App\Supports\Constant::ENABLED_OPTIONS,
            old('enabled', ($item->enabled ?? \App\Supports\Constant::ENABLED_OPTION)), true) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {!! \Form::nText('description', 'Description', old('remarks', $item->remarks ?? null), false) !!}
    </div>
</div>
<div class="row mt-3">
    <div class="col-12 justify-content-between d-flex">
        {!! \Form::nCancel(__('common.Cancel')) !!}
        {!! \Form::nSubmit('submit', __('common.Save')) !!}
    </div>
</div>
