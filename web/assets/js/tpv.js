var path_assets = $('input[name="path_assets"]').val();
$(document).ready(function () {

    if ($('input[name="type_ticket"]').val() == 'product') {
        paginateProducts('list');
        $('#load_products').addClass('active');
        $('#load_treatments').removeClass('active');
    } else {
        paginateTreatments('list');
        $('#load_products').removeClass('active');
        $('#load_treatments').addClass('active');
    }

    /*Vista de productos y tratamientos vista cuadricula y lista*/
    $('#bt-list').on('click', function () {
        $(this).addClass('active');
        $('#bt-grid').removeClass('active');
        $('#grid-view').addClass('d-none');
        $('#list-view').removeClass('d-none');

        if ($('input[name="type_ticket"]').val() == 'product') {
            paginateProducts('list');
        } else {
            paginateTreatments('list');
        }

    });

    $('#bt-grid').on('click', function () {
        $(this).addClass('active');
        $('#bt-list').removeClass('active');
        $('#list-view').addClass('d-none');
        $('#grid-view').removeClass('d-none');

        if ($('input[name="type_ticket"]').val() == 'product') {
            paginateProducts('grid');
        } else {
            paginateTreatments('grid');
        }

    });

    /*tooltip de productos y tratamientos en vista grid*/
    $('*[data-toggle="tooltip"]').tooltip();

    //cancelar venta (resetear el carrito)
    $('#reset_ticket').on('click', function () {
        manageCart(null, null, 'reset', null);
        resetTable();
    });

    //Facturar la compra
    $('#check_in').on('click', checkIn);


    //Ver productos (limpia carro)
    $('#load_products').on('click', function (e) {
        e.preventDefault();
        $('#load_products').addClass('active');
        $('#load_treatments').removeClass('active');
        paginateProducts();
        $('input[name="type_ticket"]').val('product');
    });
    //Ver tratamientos (limpia carro)
    $('#load_treatments').on('click', function (e) {
        e.preventDefault();
        $('#load_products').removeClass('active');
        $('#load_treatments').addClass('active');
        paginateTreatments();
        $('input[name="type_ticket"]').val('treatment');
    });


    $('#table_sale').find('tbody').find('tr').find('.sum').on('click', sum);
    $('#table_sale').find('tbody').find('tr').find('.minus').on('click', minus);
    $('#table_sale').find('tbody').find('tr').find('.rm').on('click', removeRow);

});

function paginateProducts(template, page = null) {
    $.ajax({
        url: $('#pagination-products').data('path'),
        data: {
            page: page
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (typeof template !== 'undefinded' && template == 'grid') {
                $('#grid-view').html(templateGrid(data.products));
            } else {
                $('#list-view').find('.list-group').html(templateList(data.products));
            }
            $('#pagination-products').html(paginationTemplate(data.pagination));
            $('.bt-pag-tpv:not(.disabled)').on('click', function () {
                paginateProducts(template, $(this).data('page'));
            });
            $('.product').off();
            $('.product').on('click', tpv);
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

function paginateTreatments(template, page = null) {
    $.ajax({
        url: $('#pagination-products').data('path_treatments'),
        data: {
            page: page
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (typeof template !== 'undefinded' && template == 'grid') {
                $('#grid-view').html(templateGrid(data.treatments));
            } else {
                $('#list-view').find('.list-group').html(templateList(data.treatments));
            }
            $('#pagination-products').html(paginationTemplate(data.pagination));
            $('.bt-pag-tpv:not(.disabled)').on('click', function () {
                paginateTreatments(template, $(this).data('page'));
            });
            $('.product').off();
            $('.product').on('click', tpv);
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

function templateList(data) {
    var html = '';
    $.each(data, function (index, item) {
        html += '<a href="#" class="list-group-item list-group-item-action product" data-id="' + item.id + '" data-price="' + item.price + '">' + item.name + '</a>';
    });
    return html;
}

function templateGrid(data) {
    var html = '';
    $.each(data, function (index, item) {

        var image = '';
        if (typeof data.image !== 'undefined') {
            image = '<img title="' + item.name + '" alt="imagen producto" src="' + path_assets + '/img/product/' + data.image + '">';
        } else {
            image = '<img title="' + item.name + '" alt="imagen producto" src="' + path_assets + '/img/product/letter-p.png">';
        }

        html += '<div class="col-3 mt-2 cont-img product" data-id="' + item.id + '" data-price="' + item.price + '">' +
            image +
            '   <span class="d-block text-center">' + item.name + '</span>' +
            '   </div>';
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
    var type = $('input[name="type_ticket"]').val();

    //Comprobar si el producto ya se había insertado en la tabla
    var exists = false;
    $.each(table.find('tbody').find('tr'), function () {
        if (($(this).data('id_product') == idProduct) && ($(this).data('type') == type)) {
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

        tr = $('<tr data-type="' + type + '" data-id_product="' + idProduct + '">' +
            '<td class="td_name" title="' + productName + '"><span>' + productName + '</span></td>' +
            '<td class="td_cant">' + cant + '</td>' +
            '<td class="td_price">' + price + '</td>' +
            '<td class="td_total_price">' + totalPrice + '</td>' +
            '<td>' +
            '  <button class="sum action"><i class="fas fa-plus-square"></i></button>' +
            '  <button class="minus action"><i class="fas fa-minus-square"></i></button>' +
            '  <button class="rm action"><i class="fas fa-window-close"></i></button>' +
            '</td>' +
            '</tr>');
        table.find('tbody').append(tr);

        /*asignar eventos a iconos de acción*/
        tr.find('.sum').on('click', sum);
        tr.find('.minus').on('click', minus);
        tr.find('.rm').on('click', removeRow);

    } else {

        tr = table.find('tbody').find('tr[data-id_product="' + idProduct + '"][data-type="' + type + '"]');

        cant = parseInt(tr.find('.td_cant').text());
        cant++;
        totalPrice = parseFloat(cant * price).toFixed(2);

        tr.find('.td_cant').text(cant);
        tr.find('.td_total_price').text(totalPrice);
    }

    //Calcular y pintar el precio total de todos los artículos
    var totalPriceArticles = 0;
    $.each(table.find('tbody').find('tr').find('.td_total_price'), function () {
        totalPriceArticles = parseFloat(totalPriceArticles) + parseFloat($(this).text());
    });
    $('#table_total').find('.total_cant_articles').text(table.find('tbody').find('tr').length);
    $('#table_total').find('.total_price_articles').text(parseFloat(totalPriceArticles).toFixed(2) + ' €');

    //añadir línea al carrito
    manageCart(idProduct, cant, 'sum', type);
}


function sum() {
    tr = $(this).closest('tr');
    var cant = parseInt(tr.find('.td_cant').text());
    cant++;
    var price = parseFloat(tr.find('.td_price').text()).toFixed(2);
    var totalPrice = parseFloat(cant * price).toFixed(2);
    tr.find('.td_cant').text(cant);
    tr.find('.td_total_price').text(totalPrice);

    //sumar al carrito
    manageCart(tr.data('id_product'), cant, 'sum', $('input[name="type_ticket"]').val());
}

function minus() {
    tr = $(this).closest('tr');
    var cant = parseInt(tr.find('.td_cant').text());
    if (cant > 1) {
        cant--;
        var price = parseFloat(tr.find('.td_price').text()).toFixed(2);
        var totalPrice = parseFloat(cant * price).toFixed(2);
        tr.find('.td_cant').text(cant);
        tr.find('.td_total_price').text(totalPrice);

        //restar cantidad al carrito
        manageCart(tr.data('id_product'), cant, 'minus', $('input[name="type_ticket"]').val());
    }
}

function removeRow() {
    tr = $(this).closest('tr');

    manageCart(tr.data('id_product'), null, 'del', $('input[name="type_ticket"]').val());

    tr.remove();
}

/*Funciones del carrito*/
function manageCart(id, cant, action, type) {
    $.ajax({
        url: $('input[name="path_manage_shopping_cart"]').val(),
        data: {
            //id producto
            id: id,
            //cantidad
            cant: cant,
            type: type,
            action: action
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            console.log(data);
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

//Genera un ticket con los datos del carrito y luego lo resetea
function checkIn() {
    $('.spinner').removeClass('d-none');
    $.ajax({
        url: $('input[name="path_check_in"]').val(),
        data: {},
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            //Si el ticket se ha guardado
            if (typeof data !== 'undefined') {
                saveDetails(data);
            }
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

function saveDetails(idTicket) {
    $.ajax({
        url: $('input[name="path_save_details"]').val(),
        data: {
            id: idTicket
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            if (data.length > 0) {
                //alert('se han guardado ' + data.length + ' detalles');
                swal({
                    title: "Venta realizada",
                    text: "¿Quieres imprimir el ticket?",
                    icon: "success",
                    buttons: {
                        cancel: {
                            text: "Cancelar",
                            value: null,
                            visible: true,
                            className: "",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Imprimir Ticket",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    }
                })
                    .then((confirm) => {
                        if (confirm) {
                            swal("Aparecería la plantilla del ticket", {
                                icon: "success",
                            });
                        }
                    });
            }
            resetTable();
        },
        error: function (request, status, error) {
            swal({
                title: "Error",
                text: status,
                icon: "error",
            });
        },
        complete: function (data) {
            $('.spinner').addClass('d-none');
        }
    });
}

function resetTable() {
    $('#table_sale').find('tbody').children().remove();
    $('.total_cant_articles').text(0);
    $('.total_price_articles').text('0.00 €');
}