@push('plugin-style')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" type="text/css">
@endpush

@push('page-style')
    <style>
        span[aria-labelledby="select2-user_id-container"],
        span[aria-labelledby="select2-receiver_id-container"] {
            height: calc(7.60rem + 2px) !important;
        }

        span[aria-labelledby="select2-user_id-container"] span.select2-selection__arrow,
        span[aria-labelledby="select2-receiver_id-container"] span.select2-selection__arrow {
            margin-top: calc(2.50rem);
        }
    </style>
@endpush

<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            {!! \Form::nText('number', 'Invoice Number', old('number', $invoice->number ?? null), true) !!}
            {!! \Form::nDate('invoiced_at', 'Invoice Date',
                    old('invoiced_at', $invoice->invoiced_at ?? \Carbon\Carbon::now()->format('Y-m-d')), true,
                     ['class' => 'form-control date-range-picker']) !!}
        </div>
        <div class="col-md-4">
            {!! \Form::nSelect('user_id', 'Sender', [], old('user_id', ($invoice->user_id ?? null)),
 true, ['placeholder' => 'Select Sender', 'custom-select customer-select2']) !!}
        </div>
        <div class="col-md-4">
            {!! \Form::nSelect('receiver_id', 'Receiver', [], old('receiver_id', ($invoice->receiver_id ?? null)), true, ['placeholder' => 'Select Receiver']) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-12 table-responsive">
            <table class="table table-center table-hover">
                <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th>Items</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="invoice-body">
                @for($index=0;$index<10; $index++)
                    <tr class="invoice-item" data-invoice-item-id="{{ $index }}">
                        <td style="width: 40px;" class="align-content-center text-center pr-0 pb-0">
                            <button class="btn btn-outline-secondary  btn-sm" onclick="removeRow(this)">
                                <i class="fas fa-times-circle"></i>
                            </button>
                            <input type="hidden" id="item_id_{{$index}}" value="">
                        </td>
                        <td class="pb-0">
                            {!! \Form::iText("item_name_{$index}", 'Name', null, true, null, 'before', ['placeholder' => 'Enter Item Name']) !!}
                            <div class="detail-panel">
                                {!! \Form::iTextarea("item_description_{$index}", 'Description', null, false, null, 'before', ['placeholder' => 'Enter Item Description', 'rows' => 2 ]) !!}
                            </div>
                        </td>
                        <td class="pb-0">
                            {!! \Form::iNumber("item_quantity_{$index}", 'Quantity', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Quantity', 'class' => 'form-control text-right']) !!}
                            <div class="detail-panel">
                                {!! \Form::nText("item_dimension_{$index}", 'Dimension', null, false, ['placeholder' => 'Enter Item Dimension', 'class' => 'form-control dimension-field']) !!}
                            </div>
                        </td>
                        <td class="pb-0">
                            {!! \Form::iNumber("item_price_{$index}", 'Price', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Price', 'class' => 'form-control text-right']) !!}
                            <div class="detail-panel">
                                {!! \Form::nNumber("item_weight_{$index}", 'Weight', '0.00', false, ['placeholder' => 'Enter Item Weight', 'class' => 'form-control text-right']) !!}
                            </div>
                        </td>
                        <td class="pb-0">
                            {!! \Form::iNumber("item_total_{$index}", 'Amount', '0.00', false, null, 'before', ['placeholder' => 'Enter Item Total', 'class' => 'form-control bg-white text-right']) !!}
                        </td>
                        <td>
                            <a href="#" class="text-secondary detail-panel-btn">
                                <i class="fas fa-angle-double-down"></i>
                            </a>
                        </td>
                    </tr>
                @endfor
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#staticBackdrop">
                            Add New Item
                        </button>
                    </td>
                    <td colspan="4" class="px-0">
                        {!! \Form::iSelect('item_query', 'Items', [], null, true, null, 'before', ['class' => 'form-control custom-select item-query-select2']) !!}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            {!! \Form::nTextarea('remarks', __('common.Remarks'), old('remarks', $invoice->remarks ?? null), false) !!}
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 justify-content-between d-flex">
            {!! \Form::nCancel(__('common.Cancel')) !!}
            {!! \Form::nSubmit('submit', __('common.Save')) !!}
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>

@push('page-script')
    <script type="text/javascript" src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/pages/invoice.min.js') }}"></script>
    <script>
        var selected_user_id = '{{ old('user_id', $invoice->user_id ?? null) }}';
        var selected_receiver_id = '{{ old('receiver_id', $invoice->receiver_id ?? null) }}';
        const defaultMedia = '{{ asset(\App\Supports\Constant::USER_PROFILE_IMAGE) }}' + "##";

        $(function () {
            $(".dimension-field").inputmask('999X999X999', {'placeholder': '___X___X___'});
            $(".detail-panel").addClass('d-none');
            $(".detail-panel-btn").on("click", function () {
                $(".detail-panel").toggleClass('d-none');
            });

            userSelectDropdown({
                target: "user_id",
                placeholder: "Select a Sender",
                route: "{{ route('backend.shipment.customers.ajax') }}"
            });

            userSelectDropdown({
                target: "receiver_id",
                placeholder: "Select a Receiver",
                route: "{{ route('backend.shipment.customers.ajax') }}"
            });

            itemSelectDropdown({
                target: 'item_query',
                placeholder: "Please Select Item",
                route: "{{ route('backend.shipment.items.ajax') }}"
            });

            /*$("#user_id").on("change.select2", function () {
                alert("select2 event triggered");
            });*/

            $("#item_query").on("change.select2", function () {
                var option = $('#item_query').find(':selected');
                var text = option.text().trim();
                var textArray = text.split("##");
                var id = option.val().trim();

                var item = {
                    "id": id,
                    "text": text,
                    "name": textArray[0],
                    "dimension": textArray[1],
                    "description": textArray[3],
/*                    "weight": (textArray[4] !== undefined) ? textArray[4] : null,*/
                    "price": parseFloat(textArray[2]).toFixed(2),
                    "quantity": parseFloat('1.00').toFixed(2),
                    "total": (parseFloat(textArray[2])).toFixed(2),
                }

                var lastRow = $("#invoice-body>tr:last-child");
                var item_invoice_id = lastRow.data('invoice-item-id');
                lastRow.find("#item_id_" + item_invoice_id).val(item.id);
                lastRow.find("#item_name_" + item_invoice_id).val(item.name);
                lastRow.find("#item_description_" + item_invoice_id).val(item.description);
                lastRow.find("#item_dimension_" + item_invoice_id).val(item.dimension);
                lastRow.find("#item_quantity_" + item_invoice_id).val(item.quantity);
                lastRow.find("#item_price_" + item_invoice_id).val(item.price);
                lastRow.find("#item_total_" + item_invoice_id).val(item.total);

            });

        });
    </script>
@endpush
