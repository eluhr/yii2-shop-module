document.querySelectorAll('[data-toggle="variant"]').forEach(function (variantToggle) {
    variantToggle.addEventListener("mouseover", function (event) {
        var wrapperEl = document.querySelector(event.target.dataset.target);
        if (wrapperEl) {
            if (event.target.dataset.hasDiscount === '1') {
                wrapperEl.classList.add('has-discount');
            } else {
                wrapperEl.classList.remove('has-discount');
            }
            if (event.target.dataset.isAffiliate === "1") {
                wrapperEl.classList.add('is-affiliate')
            } else {
                wrapperEl.classList.remove('is-affiliate')
            }
        }
        var thumbnailEl = document.querySelector(event.target.dataset.target + ' .thumbnail-image');
        if (thumbnailEl) {
            thumbnailEl.style.backgroundImage = "url('" + event.target.dataset.image + "')";
        }
        var priceEl = document.querySelector(event.target.dataset.target + ' .variant-price');
        if (priceEl) {
            priceEl.outerHTML = event.target.dataset.price;
        }
    });
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

$('#checkout-form').on("beforeSubmit", function (event) {
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