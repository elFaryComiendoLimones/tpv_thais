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

}

function templateList(data) {
    var html = '';
    $.each(data, function (index, item) {
        html += '<a href="#" class="list-group-item list-group-item-action">' + item.name + '</a>';
    });
    return html;
}