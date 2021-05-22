$(document).ready(function () {

    // stop issue livewire when
    $(document).on('focus', '.form-control', function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });

    });
})