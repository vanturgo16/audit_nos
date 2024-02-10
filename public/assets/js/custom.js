$(document).on("shown.bs.modal", ".modal", function () {
    $(".js-example-basic-single").select2({
        dropdownParent: this,
    });
});

$(document).ready(function () {
    $(".data-select2").select2({
        width: "resolve", // need to override the changed default
        theme: "classic",
    });
    $(".js-example-basic-single").select2();
});
