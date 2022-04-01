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
 true, ['placeholder' => 'Select Sender']) !!}
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
                    <th width="40%">Items</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="invoice-body">
                @php $index = 0; @endphp
                <input type="hidden" id="item_index" value="{{ $index }}"/>
                <tr>
                    <td style="width: 40px;" class="align-content-center text-center pr-0 pb-0">
                        <button class="btn btn-outline-secondary  btn-sm" onclick="removeRow(this)">
                            <i class="fas fa-times-circle"></i>
                        </button>
                        <input type="hidden" id="item[{{$index}}][id]" value="">
                    </td>
                    <td class="pb-0">
                        {!! \Form::iText("item[{$index}][name]", 'Name', null, true, null, 'before', ['placeholder' => 'Enter Item Name']) !!}
                        <div class="detail-panel">
                            {!! \Form::iTextarea("item[{$index}][description]", 'Description', null, false, null, 'before', ['placeholder' => 'Enter Item Description', 'rows' => 2 ]) !!}
                        </div>
                    </td>
                    <td class="pb-0">
                        {!! \Form::iNumber("item[{$index}][quantity]", 'Quantity', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Quantity', 'class' => 'form-control text-right']) !!}
                        <div class="detail-panel">
                            {!! \Form::nText("item[{$index}][dimension]", 'Dimension', null, false, ['placeholder' => 'Enter Item Dimension', 'class' => 'form-control dimension-field']) !!}
                        </div>
                    </td>
                    <td class="pb-0">
                        {!! \Form::iNumber("item[{$index}][price]", 'Price', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Price', 'class' => 'form-control text-right']) !!}
                        <div class="detail-panel">
                            {!! \Form::nNumber("item[{$index}][weight]", 'Weight', '0.00', false, ['placeholder' => 'Enter Item Weight', 'class' => 'form-control text-right']) !!}
                        </div>
                    </td>
                    <td class="pb-0">
                        {!! \Form::iNumber("item[{$index}][total]", 'Amount', '0.00', false, null, 'before', ['placeholder' => 'Enter Item Total', 'class' => 'form-control bg-white text-right']) !!}
                    </td>
                    <td>
                        <a href="#" class="text-secondary" onclick="toggleDetailPanel(this); return false;">
                            <i class="fas fa-angle-double-down"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    {{--                    <td colspan="2">
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                                    data-target="#staticBackdrop">
                                                Add New Item
                                            </button>
                                        </td>--}}
                    <td colspan="6" class="px-0">
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
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{--@include('backend.shipment.item.form')--}}
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

        function addNewItem(index, item) {
             $("#invoice-body").append('<tr>\
                    <td style="width: 40px;" class="align-content-center text-center pr-0 pb-0">\
                        <button class="btn btn-outline-secondary  btn-sm" onclick="removeRow(this)">\
                            <i class="fas fa-times-circle"></i>\
                        </button>\
                        <input type="hidden" id="item[' + index + '][id]" value="' + item.id + '">\
                    </td>\
                    <td class="pb-0">\
                        <div class="form-group">\
    <label for="item[' + index + '][name]" class="sr-only d-none">Name<span style="color: #dc3545; font-weight:700">*</span></label>\
            <input class="form-control" required="required" placeholder="Enter Item Name" name="item[' + index + '][name]" type="text" id="item[' + index + '][name]" value="' + item.name + '">\
        <span id="item[' + index + '][name]-error" class="invalid-feedback"></span>\
</div>\
                        <div class="detail-panel d-none">\
                            <div class="form-group">\
    <label for="item[' + index + '][description]" class="sr-only d-none">Description</label>\
            <textarea class="form-control" rows="2" placeholder="Enter Item Description" name="item[' + index + '][description]" cols="50" id="item[' + index + '][description]">' + item.description + '</textarea>\
        <span id="item[' + index + '][description]-error" class="invalid-feedback"></span>\
</div>\
                        </div>\
                    </td>\
                    <td class="pb-0">\
                        <div class="form-group">\
    <label for="item[' + index + '][quantity]" class="sr-only d-none">Quantity<span style="color: #dc3545; font-weight:700">*</span></label>\
            <input class="form-control text-right" required="required" placeholder="Enter Item Quantity" name="item[' + index + '][quantity]" type="number" value="' + item.quantity + '" id="item[' + index + '][quantity]">\
    <span id="item[' + index + '][quantity]-error" class="invalid-feedback"></span>\
</div>\
                        <div class="detail-panel d-none">\
                            <div class="form-group">\
    <label for="item[' + index + '][dimension]">Dimension</label>\
    <input class="form-control dimension-field" placeholder="Enter Item Dimension" name="item[' + index + '][dimension]" type="text" id="item[' + index + '][dimension]" value="' + item.quantity + '" inputmode="text">\
    <span id="item[' + index + '][dimension]-error" class="invalid-feedback"></span>\
</div>\
                        </div>\
                    </td>\
                    <td class="pb-0">\
                        <div class="form-group">\
    <label for="item[' + index + '][price]" class="sr-only d-none">Price<span style="color: #dc3545; font-weight:700">*</span></label>\
            <input class="form-control text-right" required="required" placeholder="Enter Item Price" name="item[' + index + '][price]" type="number" value="' + item.price + '" id="item[' + index + '][price]">\
    <span id="item[' + index + '][price]-error" class="invalid-feedback"></span>\
</div>\
                        <div class="detail-panel d-none">\
                            <div class="form-group">\
    <label for="item[' + index + '][weight]">Weight</label>\
    <input class="form-control text-right" placeholder="Enter Item Weight" name="item[' + index + '][weight]" type="number" value="' + item.weight + '" id="item[' + index + '][weight]">\
    <span id="item[' + index + '][weight]-error" class="invalid-feedback"></span>\
</div>\
                        </div>\
                    </td>\
                    <td class="pb-0">\
                        <div class="form-group">\
    <label for="item[' + index + '][total]" class="sr-only d-none">Amount</label>\
            <input class="form-control bg-white text-right" placeholder="Enter Item Total" name="item[' + index + '][total]" type="number" value="' + item.total + '" id="item[' + index + '][total]">\
    <span id="item[' + index + '][total]-error" class="invalid-feedback"></span>\
</div>\
                    </td>\
                    <td>\
                        <a href="#" class="text-secondary" onclick="toggleDetailPanel(this); return false;">\
                            <i class="fas fa-angle-double-down"></i>\
                        </a>\
                    </td>\
                </tr>');
            index++;

            $("#item_index").val(index);

        }

        $(document).ready(function () {
            userSelectDropdown({
                target: "user_id",
                placeholder: "Select a Sender",
                route: "{{ route('backend.shipment.customers.ajax') }}",
                selected: selected_user_id
            });

            userSelectDropdown({
                target: "receiver_id",
                placeholder: "Select a Receiver",
                route: "{{ route('backend.shipment.customers.ajax') }}",
                selected: selected_receiver_id
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
                    "weight": (textArray[4] !== undefined) ? textArray[4] : null,
                    "price": parseFloat(textArray[2]).toFixed(2),
                    "quantity": parseFloat('1.00').toFixed(2),
                    "total": (parseFloat(textArray[2])).toFixed(2),
                }

                addNewItem($("#item_index").val(), item);
/*
                var lastRow = $("#invoice-body>tr:last-child");
                var item_invoice_id = lastRow.data('invoice-item-id');
                lastRow.find("#item_id_" + item_invoice_id).val(item.id);
                lastRow.find("#item_name_" + item_invoice_id).val(item.name);
                lastRow.find("#item_description_" + item_invoice_id).val(item.description);
                lastRow.find("#item_dimension_" + item_invoice_id).val(item.dimension);
                lastRow.find("#item_quantity_" + item_invoice_id).val(item.quantity);
                lastRow.find("#item_price_" + item_invoice_id).val(item.price);
                lastRow.find("#item_total_" + item_invoice_id).val(item.total);*/

            });

        });
    </script>
@endpush
