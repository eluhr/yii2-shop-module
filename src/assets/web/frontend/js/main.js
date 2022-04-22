$('[data-toggle="variant"]').on("mouseover", function () {
    var targetEl = $(this);
    var targetSelector = targetEl.data('target');
    var wrapperEl = $(targetSelector);
    if (wrapperEl) {
        if (targetEl.data('has-discount') === 1) {
            wrapperEl.addClass('has-discount');
        } else {
            wrapperEl.removeClass('has-discount');
        }
        if (targetEl.data('is-affiliate') === 1) {
            wrapperEl.addClass('is-affiliate')
        } else {
            wrapperEl.removeClass('is-affiliate')
        }
    }
    var thumbnailEl = $(targetSelector + ' .thumbnail-image');
    if (thumbnailEl) {
        thumbnailEl.css('background-image', "url('" + targetEl.data('image') + "')");
    }
    var priceEl = $(targetSelector + ' .variant-price');
    if (priceEl) {
        priceEl[0].outerHTML = targetEl.data('price');
    }
});

$('input[type="text"][name="q"]').on('blur', function() {
    this.form.submit();
});

$('[data-filter="filter-form"]').on('change', function() {
    this.form.submit();
});
$('.filter > label').on('click', function() {
    $(this).parent('.filter').toggleClass('show');
});

$('#checkout-form').on("beforeSubmit", function () {
    $('#submit-checkout').button('loading')
});


$('select[name="stored-addresses"]').on("change", function () {
    var idPrefix = "shoppingcartcheckout-";
    var json = JSON.parse($(this).val());
    if (json === 0) {
        $('#' + idPrefix + 'first_name').val('');
        $('#' + idPrefix + 'surname').val('');
        $('#' + idPrefix + 'email').val('');
        $('#' + idPrefix + 'street_name').val('');
        $('#' + idPrefix + 'house_number').val('');
        $('#' + idPrefix + 'postal').val('');
        $('#' + idPrefix + 'city').val('');
        $('#' + idPrefix + '-has_different_delivery_address').attr('checked', false);
        $('#' + idPrefix + 'delivery_first_name').val('');
        $('#' + idPrefix + 'delivery_surname').val('');
        $('#' + idPrefix + 'delivery_street_name').val('');
        $('#' + idPrefix + 'delivery_house_number').val('');
        $('#' + idPrefix + 'delivery_postal').val('');
        $('#' + idPrefix + 'delivery_city').val('');
    } else {
        var keys = Object.keys(json);
        for (var i = 0; i < keys.length; i++) {
            var attribute = keys[i];
            var input = $("#" + idPrefix + attribute);
            if (input.attr('type') === 'checkbox') {
                var checked = json[attribute] === '1';
                input.attr('checked',checked);
                $("#different-delivery-address").collapse(checked ? 'show' : 'hide')
            } else {
                input.val(json[attribute]);
            }
        }
    }
});