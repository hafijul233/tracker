<section class="mb-4">
    <div class="d-flex justify-content-between">
        <span class="d-flex lead font-weight-bold">
            <i class="fas fa-address-card-o"></i>
        </span>
        <button class="btn btn-outline-warning"
                type="button" data-toggle="modal" data-target="#staticBackdrop">
            <i class="fas fa-edit"></i>
            Edit
        </button>
    </div>
    <div class="row">
        {!! \Form::hidden("address[{$type}][type]", $type) !!}
        <div class="col-md-6">
            {!! \Form::nText("address[{$type}][representative]", 'Representative', null, false) !!}
            {!! \Form::nTel("address[{$type}][phone]", 'Phone', null, false) !!}
            {!! \Form::nTextarea("address[{$type}][street_address]", 'Street Address', old('street_address', $contact->first_name ?? null), false, ['rows' => 4]) !!}
        </div>
        <div class="col-md-6">
            {!! \Form::nSelect("address[{$type}][country_id]", 'Country', $countries ?? [], null, false) !!}
            {!! \Form::nSelect("address[{$type}][state_id]", 'State', $states ?? [], null, false) !!}
            {!! \Form::nSelect("address[{$type}][city_id]", 'City', $cities ?? [], null, false) !!}
            {!! \Form::nText("address[{$type}][post_code]", 'Post/Zip Code', null, false) !!}
        </div>
    </div>
</section>