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
                    old('invoiced_at', $invoice->invoiced_at ?? \Carbon\Carbon::now()->format(config('backend.datetime'))), true,
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
        <div class="col-12">
            {!! \Form::nTextarea('remarks', 'Remarks', old('remarks', $invoice->remarks ?? null), false) !!}
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 justify-content-between d-flex">
            {!! \Form::nCancel('Cancel') !!}
            {!! \Form::nSubmit('submit', 'Save') !!}
        </div>
    </div>
</div>

@push('page-script')
    <script type="text/javascript" src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
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

            $("#select2-user_id-results").append('<li role="alert" aria-live="assertive" class="select2-results__option select2-results__message">\
                <a href="#" class="btn btn-outline-primary btn-block">Add New Customer</a>\
                </li>');

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

            /*            $("#invoice-form").validate({
                            rules: {
                                name: {
                                    required: true,
                                    minlength: 3,
                                    maxlength: 255
                                },
                                enabled: {
                                    required: true
                                },
                                remarks: {
                                },
                            }
                        });*/
        });
    </script>
@endpush
