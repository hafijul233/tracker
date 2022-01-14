@foreach($addressBooks as $addressBook)
    <section class="mb-4">
        <div class="d-flex justify-content-between">
        <span class="d-flex lead font-weight-bold">
            <i class="fas fa-address-card-o"></i>
                # {{ $loop->first }} {{ config("contact.address_type.{$addressBook->type}") }} Address
        </span>
            <button class="btn btn-outline-warning" data-address-book-id="{{ $addressBook->id }}"
                    type="button" data-toggle="modal" data-target="#staticBackdrop">
                <i class="fas fa-edit"></i>
                Edit
            </button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Representative</label>
                    <p data-address-book-name="{{ $addressBook->name }}">
                        {{ $addressBook->name }}
                    </p>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <p data-address-book-phone="{{ $addressBook->phone }}">
                        {{ $addressBook->phone }}
                    </p>
                </div>
                <div class="form-group">
                    <label>Street Address</label>
                    <p data-address-book-address="{{ $addressBook->address }}">
                        {{ $addressBook->address }}
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Post/Zip Code</label>
                    <p data-address-book-post_code="{{ $addressBook->post_code }}">
                        {{ $addressBook->post_code }}
                    </p>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <p data-address-book-city="{{ $addressBook->city->id }}">
                        {{ $addressBook->city->name }}
                    </p>
                </div>
                <div class="form-group">
                    <label>State</label>
                    <p data-address-book-state="{{ $addressBook->state->id }}">
                        {{ $addressBook->state->name }}
                    </p>
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <p data-address-book-country="{{ $addressBook->country->id }}">
                        {{ $addressBook->country->name }}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endforeach