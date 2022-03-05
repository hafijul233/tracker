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

        span#select2-user_id-container,
        span#select2-receiver_id-container {
            margin-top: calc(2.50rem);
        }
    </style>
@endpush

<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            {!! \Form::nText('number', 'Invoice Number', old('number', $invoice->number ?? null), true) !!}
            {!! \Form::nText('invoiced_at', 'Invoice Date',
                    old('invoiced_at', $invoice->invoiced_at ?? \Carbon\Carbon::now()->format(config('backend.date'))), true,
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
                    <th>Quantity</th>
                    <th>Items</th>
                    <th>Weight</th>
                    <th>Price</th>
                    <th>Amount</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="invoice-body">
                <tr class="invoice-item">
                    <td style="width: 40px;" class="align-content-center text-center pr-0 pb-0">
                        <button class="btn btn-outline-secondary  btn-sm remove-btn">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </td>
                    <td class="pb-0">
                        {!! \Form::iNumber('item_quantity_0', 'Quantity', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Quantity', 'class' => 'form-control text-right']) !!}
                    </td>
                    <td class="pb-0">
                        {!! \Form::iText('item_name_0', 'Name', null, true, null, 'before', ['placeholder' => 'Enter Item Name']) !!}
                        <div class="detail-panel">
                            {!! \Form::iTextarea('item_description_0', 'Description', null, false, null, 'before', ['placeholder' => 'Enter Item Description', 'rows' => 2 ]) !!}
                        </div>
                    </td>
                    <td class="pb-0" colspan="2">
                        <div class="row">
                            <div class="col-6">
                                {!! \Form::iNumber('item_weight_0', 'Weight', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Weight', 'class' => 'form-control text-right']) !!}
                            </div>
                            <div class="col-6">
                                {!! \Form::iNumber('item_price_0', 'Price', '0.00', true, null, 'before', ['placeholder' => 'Enter Item Price', 'class' => 'form-control text-right']) !!}
                            </div>
                        </div>
                        <div class="row detail-panel">
                            <div class="col-12 ">
                                {!! \Form::nText('item_dimension_0', 'Dimension', null, false, ['placeholder' => 'Enter Item Dimension', 'class' => 'form-control dimension-field text-right']) !!}
                            </div>
                        </div>
                    </td>
                    <td class="pb-0">
                        <input type="text" class="form-control bg-white text-right" disabled="" placeholder="0.00"
                               value="0.00">
                    </td>
                    <td>
                        <a href="#" class="text-secondary detail-panel-btn">
                            <i class="fas fa-angle-double-down"></i>
                        </a>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        {!! \Form::iSelect('item_query', 'Items', [], null, true, null, 'before', ['class' => 'form-control custom-select item-query-select2']) !!}
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            {!! \Form::nTextarea('remarks', 'Remarks', old('remarks', $invoice->remarks ?? null), false) !!}
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 justify-content-between d-flex">
            {!! \Form::nCancel(__('common.Cancel')) !!}
            {!! \Form::nSubmit('submit', __('common.Save')) !!}
        </div>
    </div>
</div>

@push('page-script')
    <script type="text/javascript" src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script>
        const customer_ajax_route = '{{ route('backend.shipment.customers.ajax') }}';
        var selected_user_id = '{{ old('user_id', $invoice->user_id ?? null) }}';
        var selected_receiver_id = '{{ old('receiver_id', $invoice->receiver_id ?? null) }}';

        $(function () {
            function selectedResult(option) {
                if (!option.id) {
                    return option.text;
                }
                var optionValues = option.text.trim().split("##");
                return $('<div class="media">\
                                <img class="align-self-center mr-1 img-circle direct-chat-img elevation-1"\
                                 src="' + optionValues[0] + '" alt="' + optionValues[1] + '">\
                                <div class="media-body">\
                                    <p class="my-0 text-dark">' + optionValues[1] + '</p>\
                                    <p class="mb-0 small">\
                                    <span class="text-muted"><i class="fas fa-user"></i> ' + optionValues[3] + '</span>\
                                    <span class="ml-1 text-muted"><i class="fas fa-phone"></i> ' + optionValues[2] + '</span>\
                                    </p>\
                                </div>\
                            </div>');
            }

            function selectedOption(option) {
                if (!option.id) {
                    return option.text;
                }
                var optionValues = option.text.trim().split("##");
                return $('<p class="my-0 text-dark font-weight-bold d-flex justify-content-between align-content-center">\
                    <span><i class="fas fa-user text-muted"></i> ' + optionValues[1] + '</span>\
                        <span><i class="fas fa-phone text-muted"></i> ' + optionValues[2] + '</span></p>');
            }

            $("#user_id").select2({
                width: "100%",
                placeholder: "Select a Sender",
                minimumInputLength: 3,
                ajax: {
                    url: customer_ajax_route,
                    data: function (params) {
                        return {
                            search: params.term,
                            type: 'public'
                        }
                    },
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    cache: false,
                    processResults: function (response) {
                        var returnObject = {results: []};
                        if (response.status === true) {
                            var options = [];
                            response.data.forEach(function (customer) {
                                console.log("customer", customer);

                                const defaultMedia = '{{ asset(\App\Supports\Constant::USER_PROFILE_IMAGE) }}' + "##";
                                var id = customer.id;
                                var text = '';
                                if (customer.media.length > 0) {
                                    var avatarImage = customer.media.pop();
                                    text = avatarImage.original_url + "##";
                                } else {
                                    text = defaultMedia + "##";
                                }

                                text += (customer.name + "##") + (customer.mobile + "##") + (customer.username + "##");

                                options.push({
                                    "id": id,
                                    "text": text
                                });
                            });
                            returnObject.results = options;
                        } else {
                            notify("No Active Senders Found", 'warning', 'Alert!');
                        }
                        return returnObject;
                    }
                },
                templateResult: selectedResult,
                templateSelection: selectedOption
            });

            $("#receiver_id").select2({
                width: "100%",
                placeholder: "Select a Receiver",
                ajax: {
                    url: customer_ajax_route,
                    dataType: 'json'
                    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
                },
                templateResult: selectedResult,
                templateSelection: selectedOption
            });

            $(".item-query-select2").select2({
                width: "100%",
                placeholder: "Please Select Item"
            });
            $(".dimension-field").inputmask('999X999X999', {'placeholder': '___X___X___'});

            $(".detail-panel").addClass('d-none');
            $(".detail-panel-btn").on("click", function () {
               $(".detail-panel").toggleClass('d-none');
            });
        });
    </script>
@endpush
