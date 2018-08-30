$(document).ready(function () {

    $('a.message-confirm').on('click', function(e){
        e.preventDefault();
        var linkURL = $(this).attr("href");
        confirm(linkURL, $(this));
    });

});

function confirm(linkURL, element) {
    swal({
        title: element.data('confirm'),
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal(element.data('confirmed'), {
                    icon: "success",
                }).then((value) => {
                    window.location.href = linkURL;
                });
            }
        });
}