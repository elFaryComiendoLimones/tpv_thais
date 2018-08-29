$(document).ready(function () {

    $('a.rm-product').on('click', function(e){
        e.preventDefault();
        var linkURL = $(this).attr("href");
        confirm(linkURL);
    });

});

function confirm(linkURL) {
    swal({
        title: "¿Estás seguro/a de borrar el producto?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                swal("El producto se ha borrado correctamente!", {
                    icon: "success",
                }).then((value) => {
                    window.location.href = linkURL;
                });
            }
        });
}