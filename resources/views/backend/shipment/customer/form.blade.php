@push('plugin-style')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}" type="text/css">
@endpush

<div class="card-body">
    {!! \Form::hidden('home_page', \App\Supports\Constant::DASHBOARD_ROUTE) !!}
    {!! \Form::hidden('locale', \App\Supports\Constant::LOCALE) !!}

    <div class="row">
        <div class="col-md-6">
            {!! \Form::nText('name', 'Name', old('name', $customer->name ?? null), true) !!}
        </div>
        <div class="col-md-6">
            {!! \Form::nText('username', 'Customername', old('username', $customer->username ?? null),
                (config('auth.credential_field') == \App\Supports\Constant::LOGIN_USERNAME)) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            {!! \Form::nEmail('email', 'Email Address', old('email', $customer->email ?? null),
                (config('auth.credential_field') == \App\Supports\Constant::LOGIN_EMAIL
                || (config('auth.credential_field') == \App\Supports\Constant::LOGIN_OTP
                    && config('auth.credential_otp_field') == \App\Supports\Constant::OTP_EMAIL))) !!}
        </div>
        <div class="col-md-6">
            {!! \Form::nTel('mobile', 'Mobile', old('mobile', $customer->mobile ?? null),
                (config('auth.credential_field') == \App\Supports\Constant::LOGIN_MOBILE
                || (config('auth.credential_field') == \App\Supports\Constant::LOGIN_OTP
                    && config('auth.credential_otp_field') == \App\Supports\Constant::OTP_MOBILE))) !!}
        </div>
    </div>
    @if(config('auth.credential_field') != \App\Supports\Constant::LOGIN_OTP)
        <div class="row">
            <div class="col-md-6">
                {!! \Form::nPassword('password', 'Password', empty($customer->password)) !!}
            </div>
            <div class="col-md-6">
                {!! \Form::nPassword('password_confirmation', 'Retype Password', empty($customer->password)) !!}
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-6">
            {!! \Form::nSelectMulti('role_id', 'Role', $roles,
    old('role_id.*', ($customer_roles ?? [\App\Supports\Constant::GUEST_ROLE_ID])), true,
    ['class' => 'form-control custom-select select2']) !!}

            {!! \Form::nSelect('enabled', 'Enabled', \App\Supports\Constant::ENABLED_OPTIONS,
old('enabled', ($customer->enabled ?? \App\Supports\Constant::ENABLED_OPTION))) !!}
        </div>
        <div class="col-md-6">
            {!! \Form::nImage('photo', 'Photo', false,
                ['preview' => true, 'height' => '69',
                 'default' => (isset($customer))
                 ? $customer->getFirstMediaUrl('avatars')
                 : asset(\App\Supports\Constant::USER_PROFILE_IMAGE)]) !!}
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            {!! \Form::iCheckbox('address[type]', 'Address Type', config('contact.address_type'), ['bill', 'ship']) !!}
            <div class="mt-3">
                {!! \Form::nTel('address[phone]', 'Phone', old('mobile', $defaultAddress->phone ?? null), false) !!}

                {!! \Form::nTextarea('address[address]', 'Street Address',
old('address.address', $defaultAddress->address ?? null), false,
['style' => "height: 84px;"]) !!}
            </div>
        </div>
        <div class="col-md-6">
            {!! \Form::nSelect('address[state_id]', 'State',
        ($states ?? []), old('address.state_id', $defaultAddress->state_id ?? config('contact.default.state')), true,
         ['placeholder' => 'Please select a state', 'class' => 'form-control custom-select select2', 'id' => 'state_id']) !!}

            {!! \Form::nSelect('address[city_id]', 'City',[], old('address.city_id', $defaultAddress->city_id ?? config('contact.default.city')), true,
['placeholder' => 'Please select a city', 'class' => 'form-control custom-select select2', 'id' => 'city_id']) !!}

            {!! \Form::nText('address[post_code]', 'Post/Zip Code',
old('address.post_code', $defaultAddress->address ?? null), false) !!}
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            {!! \Form::nTextarea('remarks', 'Remarks', old('remarks', $customer->remarks ?? null)) !!}
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
        const state_ajax_route = '{{ route('backend.settings.states.ajax') }}';
        const city_ajax_route = '{{ route('backend.settings.cities.ajax') }}';

        var selected_state_id = {{ old('address.state_id', $defaultAddress->state_id ?? config('contact.default.state')) }};
        var selected_city_id = {{ old('address.city_id', $defaultAddress->city_id ?? config('contact.default.city')) }};

        /**
         * @param dest target object
         * @param msg default message to display as placeholder
         */
        function dropdownCleaner(dest, msg) {
            dest.empty();
            var option = $("<option></option>").attr({
                "value": null,
                "selected": "selected"
            }).text(msg);

            dest.append(option);
        }

        /**
         *
         * @param dest target object
         * @param data received data
         * @param id data pointer that will be value
         * @param text data pointer that will be option text
         * @param selected prefill a options
         * @param msg default message to display as placeholder
         */
        function dropdownFiller(dest, data, id, text, selected = null, msg = 'Select an option') {

            dropdownCleaner(dest, msg);
            if (data.length > 0) {
                $.each(data, function (key, value) {

                    var selectedStatus = "";

                    if (selected == value[id]) {
                        selectedStatus = "selected";
                    }

                    var option = $("<option></option>").attr({
                        "value": value[id],
                        "selected": selectedStatus
                    }).text(value[text]);

                    dest.append(option);
                });

                //if destination DOM have select 2 init
                if (selectedStatus.length > 3) {
                    dest.val(selected);

                    if (dest.data('select2-id'))
                        dest.trigger('change.select2');
                    else
                        dest.trigger('change');
                }
            }
        }

        /**
         *
         * @param src country object
         * @param dest dropdown of branch
         * @param selected prefill a options
         */
        /*function getStateDropdown(src, dest, selected = null) {
            //var srcValue = src.val();
            var srcValue = 18; //Bangladesh

            if (!isNaN(srcValue)) {

                $.post(STATE_URL,
                    {country_id: srcValue, 'state_status': 'ACTIVE', '_token': CSRF_TOKEN},
                    function (response) {
                        if (response.status === 200) {
                            dropdownFiller(dest, response.data, 'id', 'state_name', selected, 'Please Select Division');
                        } else {
                            dropdownCleaner(dest, 'Please Select Division');
                        }
                    }, 'json');
            }
        }*/

        /**
         *
         * @param requestData request object
         * @param src country object
         * @param dest dropdown of branch
         * @param selected prefill a options
         */
        function populateCityDropdown(requestData, src, dest, selected = null) {
            $.get(city_ajax_route, requestData,
                function (response) {
                    if (response.status === true) {
                        dropdownFiller(dest, response.data, 'id', 'name', selected, 'Please Select City');
                    } else {
                        dropdownCleaner(dest, 'Please Select City');
                    }
                }, 'json');
        }

        $(document).ready(function () {
            //trigger select2
            $("#role_id").select2({
                placeholder: 'Select Role(s)',
                minimumResultsForSearch: Infinity,
                maximumSelectionLength: 3,
                allowClear: true,
                multiple: true,
                width: "100%"
            });
            $(".select2").select2({width: "100%"});

            $("#state_id").change(function () {
                var state_id = $(this).val();
                populateCityDropdown({
                    'state'
                })
            });

            if (selected_state_id.length > 0) {

            }

            $("#customer-form").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 255
                    },
                    username: {},
                    email: {
                        required: true,
                        email: true,
                        minlength: 2,
                        maxlength: 255
                    },
                    mobile: {
                        required: true,
                        digits: true,
                        minlength: 11,
                        maxlength: 11
                    },
                    password: {
                        required: {{ isset($customer) ? 'false' : 'true' }},
                        minlength: '{{ config('auth.minimum_password_length') }}',
                        maxlength: 255,
                        equalTo: "#password_confirmation"
                    },
                    password_confirmation: {
                        required: {{ isset($customer) ? 'false' : 'true' }},
                        minlength: '{{ config('auth.minimum_password_length') }}',
                        maxlength: 255,
                        equalTo: "#password"
                    }
                }
            });
        });
    </script>
@endpush
