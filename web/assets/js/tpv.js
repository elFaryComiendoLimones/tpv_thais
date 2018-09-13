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
        html += '<a href="#" class="list-group-item list-group-item-action product"data-id="' + item.id + '" data-price="' + item.price + '">' + item.name + '</a>';
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

function tpv() {
    var table = $('#table_sale');
    var product = $(this);
    var idProduct = product.data('id');
    var productName = product.text();
    var price = parseFloat(product.data('price')).toFixed(2);

    //Comprobar si el producto ya se había insertado en la tabla
    var exists = false;
    $.each(table.find('tbody').find('tr'), function () {
        if ($(this).data('id_product') == idProduct) {
            exists = true;
            return false;
        }
    });

    //Pintar la nueva fila si existe o modificarla si ya existe
    var cant = null;
    var totalPrice = null;

    var tr = null;
    if (!exists) {

        cant = 1;
        totalPrice = price;

        tr = $('<tr data-id_product="' + idProduct + '">' +
            '<td class="td_name" title="'+ productName +'"><span>' + productName + '</span></td>' +
            '<td class="td_cant">' + cant + '</td>' +
            '<td class="td_price">' + price + '</td>' +
            '<td class="td_total_price">' + totalPrice + '</td>' +
            '<td>' +
            '  <span class="sum action"><i class="fas fa-plus-square"></i></span>' +
            '  <span class="minus action"><i class="fas fa-minus-square"></i></span>' +
            '  <span class="rm action"><i class="fas fa-window-close"></i></span>' +
            '</td>' +
            '</tr>');
        table.find('tbody').append(tr);

        /*asignar eventos a iconos de acción*/
        tr.find('.sum').on('click', sum);

    }else{

        tr = table.find('tbody').find('tr[data-id_product="'+ idProduct +'"]');

        cant = parseInt(tr.find('.td_cant').text());
        cant++;
        totalPrice = parseFloat(cant * price).toFixed(2);

        tr.find('.td_cant').text(cant);
        tr.find('.td_total_price').text(totalPrice);
    }

    //Calcular y pintar el precio total de todos los artículos
    var totalPriceArticles = 0;
    $.each(table.find('tbody').find('tr').find('.td_total_price'), function(){
        totalPriceArticles = parseFloat(totalPriceArticles) + parseFloat($(this).text());
    });
    $('#table_total').find('.total_cant_articles').text(table.find('tbody').find('tr').length);
    $('#table_total').find('.total_price_articles').text(parseFloat(totalPriceArticles).toFixed(2) + ' €');

}

function sum(){
    tr = $(this).closest('tr');
    var cant = parseInt(tr.find('.td_cant').text());
    cant++;
    var price = parseFloat(tr.find('.td_price').text()).toFixed(2);
    var totalPrice = parseFloat(cant * price).toFixed(2);
    tr.find('.td_cant').text(cant);
    tr.find('.td_total_price').text(totalPrice);
}