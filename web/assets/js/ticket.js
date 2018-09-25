$(document).ready(function(){
    //$('a.view_details').on('click', getInfoTicket);
});

function getInfoTicket(e){
    e.preventDefault();
    $.ajax({
        url: $('input[name="path_view_info_ticket"]').val(),
        data: {
            id_ticket: $(this).data('id_ticket')
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {

        },
        error: function (xhr, status) {
            swal({
                title: "Error",
                text: status,
                icon: "error",
            });
        }
    });
}