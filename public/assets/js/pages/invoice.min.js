/**
 * init sender and receiver select2 dropdown
 *
 * @param options
 */
function userSelectDropdown(options) {
    if (jQuery.fn.select2) {
        $("#" + options.target).select2({
            width: "100%",
            placeholder: options.placeholder,
            minimumResultsForSearch: Infinity,
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
                            var roleStr = "";

                            if (customer.media.length > 0) {
                                var avatarImage = customer.media.pop();
                                text = avatarImage.original_url + "##";
                            } else {
                                text = defaultMedia + "##";
                            }

                            if (customer.roles.length > 0) {
                                roleStr = customer.roles.pop().name;
                            } else {
                                roleStr = "Guest";
                            }

                            text += (customer.name + "##") + (customer.mobile + "##") + (customer.username + "##") + (roleStr + "##");

                            options.push({
                                "id": id,
                                "text": text,
                                "selected": (options.selected === id)
                            });
                        });
                        returnObject.results = options;
                    } else {
                        notify("No Active Senders Found", 'warning', 'Alert!');
                    }
                    return returnObject;
                }
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
                return $('<div class="media" style="padding-top: 0.75rem;">\
                                <img class="align-self-center mr-3 ml-0 img-circle elevation-1" style="max-width: 65px; max-height: 65px;" \
                                 src="' + itemValues[0] + '" alt="User">\
                                <div class="media-body">\
                                    <h5 class="text-dark font-weight-bold">' + itemValues[1] + '</h5>\
                                    <p class="mb-0">\
                                    <span class="badge badge-pill badge-success">' + itemValues[4] + '</span>\
                                    </p>\
                                    <p class="mb-0">\
                                        <span class="text-muted">\
                                        <span class="text-dark d-none d-lg-inline-block">Username: </span><i class="fas fa-user d-inline-block d-lg-none"></i> ' + itemValues[3] + '</span>\
                                        <span class="ml-1 text-muted">\
                                        <span class="text-dark d-none d-lg-inline-block">Phone: </span><i class="fas fa-phone d-inline-block d-lg-none"></i> ' + itemValues[2] + '</span>\
                                    </p>\
                                </div>\
                            </div>');
                /*            return $('<p class="my-0 text-dark font-weight-bold d-flex justify-content-between align-content-center">\
                                    <span><i class="fas fa-user text-muted"></i> ' + itemValues[1] + '</span>\
                                        <span><i class="fas fa-phone text-muted"></i> ' + itemValues[2] + '</span></p>');*/
            }
        });
    }
}

/**
 * init item select2 dropdown
 * @param options
 */
function itemSelectDropdown(options) {
    if (jQuery.fn.select2) {
        $("#" + options.target).select2({
            width: "100%",
            placeholder: options.placeholder,
            minimumResultsForSearch: Infinity,
            ajax: {
                url: options.route,
                data: function (params) {
                    return {
                        user: $("#user_id").val(),
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
                    console.log(response);
                    if (response.status === true) {
                        var options = [];
                        response.data.forEach(function (item) {
                            var id = item.id;
                            var text = "";

                            text += (item.name + "##") + (item.dimension + "##") + (item.rate + " " + item.currency + "##") + (item.description + "##") + (item.weight + "##");

                            options.push({
                                "id": id,
                                "text": text,
                                "name": item.name,
                                "rate": item.rate,
                                "dimension": item.dimension
                            });
                        });
                        returnObject.results = options;
                    } else {
                        notify("No Active Items Found", 'warning', 'Alert!');
                    }
                    return returnObject;
                }
            },
            templateResult: function (item) {
                if (!item.id) {
                    return item.text;
                }
                var itemValues = item.text.trim().split("##");
                return $('<div class="media">\
                                <div class="media-body">\
                                    <h5 class="mt-0">' + itemValues[0] + '</h5>\
                                    <p class="mb-0">\
                                        <span class="text-muted">\
                                            <span class="text-dark d-none d-lg-inline-block">Dimension: </span>\
                                            <i class="fas fa-box d-inline-block d-lg-none"></i> ' + itemValues[1] + '</span>\
                                        <span class="ml-1 text-muted">\
                                            <span class="text-dark d-none d-lg-inline-block">Rate: </span>\
                                            <i class="fas fa-usd d-inline-block d-lg-none"></i> ' + itemValues[2] + '</span>\
                                    </p>\
                                </div>\
                            </div>');
            },
            templateSelection: function (item) {
                if (!item.id) {
                    return item.text;
                }
                var itemValues = item.text.trim().split("##");

                return $('<div class="media">\
                                <div class="media-body">\
                                    <h5 class="text-dark font-weight-bold">' + itemValues[0] + '</h5>\
                                </div>\
                            </div>');
            }
        })
            .on('select2:open', function () {
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
            });
    }
}

function updateInvoice() {
    var subTotalCol = $('#sub-total');
    var shipCostCol = $('#ship-cost');
    var discountCol = $('#discount');
    var grandTotalCol = $('#grand-total');

    var subTotal = 0, grandTotal = 0;

    $('table#invoice-table tbody tr').each(function () {
        var row = $(this);
        var priceCol = row.find('td input.price');
        var qtyCol = row.find('td input.quantity');
        var totalCol = row.find('td input.total');

        if (!isNaN(priceCol.val()) || !isNaN(qtyCol.val())) {
            var rate = parseFloat(priceCol.val());
            var qty = parseFloat(qtyCol.val());
            var total = rate * qty;
            subTotal += total;
            totalCol.val(total.toFixed(2));
        }
    });
    subTotalCol.val(subTotal.toFixed(2));

    if (!isNaN(discountCol.val())) {
        grandTotal = subTotal - parseFloat(discountCol.val());
    }

    console.log(shipCostCol.val());

    if (!isNaN(shipCostCol.val())) {

        grandTotal += parseFloat(shipCostCol.val());
    }

    grandTotalCol.val(grandTotal.toFixed(2));
}

function addRow(element) {
    var jqelement = $(element);
    var index = parseInt(jqelement.data('current-index'));
    $(getRowTemplate(index)).insertBefore(jqelement.parent().parent());
    jqelement.data('current-index', index + 1);

    updateInvoice();

    initItemDropDown();
}

function removeRow(element) {
    var r = $(element).parent().parent().remove();
    //updateInvoice();
}

function toggleDetailPanel(element) {
    $(element).parent().parent().find('.detail-panel').each(function () {
        $(this).toggleClass('d-none');
    })
}

$(document).ready(function () {
    $(".dimension-field").inputmask('999X999X999', {'placeholder': '___X___X___'});
    $(".detail-panel").addClass('d-none');
});