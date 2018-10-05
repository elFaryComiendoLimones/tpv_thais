$(document).ready(function(){

    var dataPoints = [];

    var options =  {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Resumen ventas últimos 10 días"
        },
        axisX: {
            valueFormatString: "DD MMM YYYY",
        },
        axisY: {
            title: "",
            titleFontSize: 24,
            includeZero: false
        },
        data: [{
            type: "spline",
            yValueFormatString: "#,###.##€",
            dataPoints: dataPoints
        }]
    };

    function addData(data) {
        for (var i = 0; i < data.length; i++) {
            dataPoints.push({
                x: new Date(data[i].date),
                y: data[i].units
            });
        }
        var chart = new CanvasJS.Chart('chartContainer', options);
        chart.render();

    }

    $.ajax({
        url: path_data_chart,
        data: {},
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            //console.log(data);
            addData(data);
        },
        error: function (xhr, status) {
            swal({
                title: "Error al obtener datos",
                text: status,
                icon: "error",
            });
        }
    });


});