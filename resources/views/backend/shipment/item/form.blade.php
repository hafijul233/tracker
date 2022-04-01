    @push('plugin-style')
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" type="text/css">
    @endpush
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                {!! \Form::nSelect('user_id', 'Customer', [], old('user_id', ($invoice->user_id ?? null)), true, ['placeholder' => 'Select Customer']) !!}
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
        <script>
            const selected_user_id = '{{ old('user_id', $item->user_id ?? null) }}';

            /**
             * init sender and receiver select2 dropdown
             *
             * @param options
             */
            function customerSelectDropdown(options) {
                if (jQuery.fn.select2) {
                    $("#" + options.target).select2({
                        width: "100%",
                        placeholder: options.placeholder,
                        /*minimumResultsForSearch: Infinity,*/
                        ajax: {
                            url: options.route,
                            data: function (params) {
                                return {
                                    search: params.term,
                                    enabled: 'yes'
                                }
                            },
                            dataType: 'json',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            cache: false,
                            delay: 250,
                            processResults: function (response) {
                                var returnObject = {results: []};
                                if (response.status === true) {
                                    var options = [];
                                    response.data.forEach(function (customer) {
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
                                            "text": text,
                                            "selected": (options.selected == id)
                                        });
                                    });
                                    returnObject.results = options;
                                } else {
                                    notify("No Active Customer Found", 'warning', 'Alert!');
                                }
                                return returnObject;
                            }
                        },
                        /*                    language: {
                                                noResults: function () {
                                                    return `<button style="width: 100%" type="button"
                                class="btn btn-primary"
                                onClick='task()'>+ Add New Item</button>
                                </li>`;
                                                }
                                            },*/
                        escapeMarkup: function (html) {
                            return html;
                        },
                        templateResult: function (item) {
                            if (!item.id) {
                                return item.text;
                            }
                            var itemValues = item.text.trim().split("##");
                            return $('<div class="media">\
                                    <img class="align-self-center mr-1 img-circle direct-chat-img elevation-1"\
                                     src="' + itemValues[0] + '" alt="' + itemValues[1] + '">\
                                    <div class="media-body">\
                                        <p class="my-0">' + itemValues[1] + '</p>\
                                        <p class="mb-0 small">\
                                        <span class="text-muted"><i class="fas fa-user"></i> ' + itemValues[3] + '</span>\
                                        <span class="ml-1 text-muted"><i class="fas fa-phone"></i> ' + itemValues[2] + '</span>\
                                        </p>\
                                    </div>\
                                </div>');
                        },
                        templateSelection: function (item) {
                            if (!item.id) {
                                return item.text;
                            }
                            var itemValues = item.text.trim().split("##");
                            return $('<p class="my-0 text-dark font-weight-bold d-flex justify-content-between align-content-center">\
                        <span><i class="fas fa-user text-muted"></i> ' + itemValues[1] + '</span>\
                            <span><i class="fas fa-phone text-muted"></i> ' + itemValues[2] + '</span></p>');
                        }
                    });

/*                        .on('select2:open', function () {
                            let a = $(this).data('select2');
                            if (!$('.select2-link').length) {
                                var select2results = a.$results.parents('.select2-results');
                                if (select2results.find(".add-new-btn").length === 0) {
                                    select2results.append('<div class="select2-link2 select2-close p-2">\
                                            <button type="button" class="btn btn-primary btn-block add-new-btn">\
                                                Add New Customer\
                                            </button>\
                                        </div>')
                                        .on('click', function (b) {
                                            $("#user_id").trigger({
                                                type: 'select2:closing',
                                                params: {
                                                    data: {
                                                        "id": 1,
                                                        "text": "Tyto alba",
                                                        "genus": "Tyto",
                                                        "species": "alba"
                                                    }
                                                }
                                            })
                                            $("#staticBackdrop").modal({
                                                backdrop: 'static'
                                            });
                                        });
                                }
                            }
                        });*/
                }
            }

            $(document).ready(function () {
                customerSelectDropdown({
                    target: "user_id",
                    placeholder: "Select a Customer",
                    route: "{{ route('backend.shipment.customers.ajax') }}",
                    selected: selected_user_id
                });

                $('#dimension').inputmask('999X999X999', {'placeholder': '___X___X___'});

                $("#item-form").validate({
                    rules: {
                        name: {
                            required: true,
                            mindimension: 3,
                            maxdimension: 255
                        },
                        enabled: {
                            required: true
                        },
                        remarks: {},
                    }
                });
            });
        </script>
    @endpush
