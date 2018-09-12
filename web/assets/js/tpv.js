$(document).ready(function () {

    paginateProducts();

    /*Vista de productos y tratamientos vista cuadricula y lista*/
    $('#bt-list').on('click', function () {
        $(this).addClass('active');
        $('#bt-grid').removeClass('active');
        $('#grid-view').addClass('d-none');
        $('#list-view').removeClass('d-none');
    });

    $('#bt-grid').on('click', function () {
        $(this).addClass('active');
        $('#bt-list').removeClass('active');
        $('#list-view').addClass('d-none');
        $('#grid-view').removeClass('d-none');
    });

    /*tooltip de productos y tratamientos en vista grid*/
    $('*[data-toggle="tooltip"]').tooltip();

});

function paginateProducts() {
    $.ajax({
        url: $('#pagination-products').data('path'),
        data: {
            page: $(this).data('page')
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            $('#list-view').find('.list-group').html(templateList(data.products));
            $('#pagination-products').html(paginationTemplate(data.pagination));
            $('.bt-pag-tpv:not(.disabled)').on('click', paginateProducts);
            $('.product').off();
            $('.product').on('click', tpv);
        },
        error: function (xhr, status) {
            swal({
                title: "Error",
                text: status,
                icon: "error",
            });
        },
        complete: function (xhr, status) {
            //alert('Petición realizada');
        }
    });
}

function templateList(data) {
    var html = '';
    $.each(data, function (index, item) {
        html += '<a href="#" class="list-group-item list-group-item-action product"data-id="' + item.id + '" data-price="' +item.price + '">' + item.name + '</a>';
    });
    return html;
}

function paginationTemplate(pagination) {
    var html = '';
    if (pagination.visible) {
        if (pagination.previous == pagination.actual) {
            html += '<button type="button" class="btn btn-dark w-25 bt-pag-tpv disabled" data-page="' + pagination.previous + '"><i class="fas fa-chevron-left"></i></button>';
            html += '<button type="button" class="btn btn-dark w-25 bt-pag-tpv" data-page="' + pagination.next + '"><i class="fas fa-chevron-right"></i></button>';
        } else if (pagination.next == pagination.actual) {
            html += '<button type="button" class="btn btn-dark w-25 bt-pag-tpv" data-page="' + pagination.previous + '"><i class="fas fa-chevron-left"></i></button>';
            html += '<button type="button" class="btn btn-dark w-25 bt-pag-tpv disabled" data-page="' + pagination.next + '"><i class="fas fa-chevron-right"></i></button>';
        } else {
            html += '<button type="button" class="btn btn-dark w-25 bt-pag-tpv" data-page="' + pagination.previous + '"><i class="fas fa-chevron-left"></i></button>';
            html += '<button type="button" class="btn btn-dark w-25 bt-pag-tpv" data-page="' + pagination.next + '"><i class="fas fa-chevron-right"></i></button>';
        }
    }
    return html;
}

function tpv(){
    var table = $('#table_sale');

}