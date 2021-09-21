document.querySelectorAll('[data-toggle="variant"]').forEach(function (variantToggle) {
    variantToggle.addEventListener("mouseover", function (event) {
        var thumbnailEl = document.querySelector(event.target.dataset.target + ' .thumbnail-image');
        if (thumbnailEl) {
            thumbnailEl.style.backgroundImage = "url('" + event.target.dataset.image + "')";
        }
        var priceEl = document.querySelector(event.target.dataset.target + ' .variant-price');
        if (priceEl) {
            priceEl.textContent = event.target.dataset.price;
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
