$(document).ready(function () {

    $('button[data-target="#dataClient"]').on('click', getData);

});

function getData() {
    $.ajax({
        url: $(this).data('path'),
        type: 'GET',
        data: {
            'id': $(this).data('id')
        },
        dataType: 'json',
        method: 'post',
        success: function (data) {
            var client = data[0];

            $('#dataClient').find('.modal-body').children().remove();

            var dni = client.dni !== null ? client.dni : '';
            var name = client.name !== null ? client.name : '';
            var surname1 = client.surname1 !== null ? client.surname1 : '';
            var surname2 = client.surname2 !== null ? client.surname2 : '';
            var province = client.province !== null ? client.province : '';
            var town = client.town !== null ? client.town : '';
            var postcode = client.postcode !== null ? client.postcode : '';
            var street = client.street !== null ? client.street : '';
            var numStreet = client.numStreet !== null ? client.numStreet : '';

            var address = '';
            if (street !== '' && numStreet !== '') {
                address = street + ' nº ' + numStreet;
            } else if (street !== '' && numStreet == '') {
                address = street;
            } else if (street == '' && numStreet !== '') {
                address = 'nº ' + numStreet
            } else {
                address = '';
            }

            var birthdate = client.birthdate !== null ? new Date(parseFloat(client.birthdate) * 1000) : '';
            var telephone = client.birthdate !== null ? client.telephone : '';
            var email = client.email !== null ? client.email : '';

            var macro = $('<ul class="list-group list-group-flush">' +
                '<li class="list-group-item"><b>DNI : </b> ' + dni + '</li>' +
                '<li class="list-group-item"><b>Nombre : </b>  ' + name + '</li>' +
                '<li class="list-group-item"><b>Apellidos : </b>  ' + surname1 + ' ' + surname2 + '</li>' +
                '<li class="list-group-item"><b>Provincia : </b>  ' + province + '</li>' +
                '<li class="list-group-item"><b>Localidad : </b>  ' + town + '</li>' +
                '<li class="list-group-item"><b>Código postal : </b>  ' + postcode + '</li>' +
                '<li class="list-group-item"><b>Dirección : </b>  ' + address + '</li>' +
                '<li class="list-group-item"><b>Fecha de nacimiento : </b>  ' + getFormattedDate(birthdate) + '</li>' +
                '<li class="list-group-item"><b>Teléfono : </b>  ' + telephone + '</li>' +
                '<li class="list-group-item"><b>Email : </b>  ' + email + '</li>' +
                '</ul>');

            $('#dataClient').find('.modal-body').html(macro);
        },
        error: function (request, error) {
            //alert("Request: "+JSON.stringify(request));
            swal("Error", 'Algo ha fallado al recoger los datos', "error");
        }
    });
}

function getFormattedDate(date) {
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return day + '/' + month + '/' + year;
}