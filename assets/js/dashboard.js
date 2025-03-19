$(document).ready(function () {
    $(".menu-item").on("click touchstart", function (e) {
        e.preventDefault();
        $(".menu-item").removeClass("active");
        $(this).addClass("active");
    });
});


