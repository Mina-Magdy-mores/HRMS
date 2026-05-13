$(document).ready(function () {
    $(document).on('click', '.are_you_sure', function (e) {
        var res = confirm('هل انت متأكد؟');
        if (!res) {
            e.preventDefault();
        }
    })
});
