function sendMessage(){
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'sendmsg'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            var today = new Date();
            if (bandera == 0) {
                alertify.error(res);
                //cargandoHide();
            } else {
                alert(datos);
                //cargandoHide();
            }
        }
    });
}

function crearINI() {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: "com.sine.enlace/enlaceinicio.php",
        type: "POST",
        data: {transaccion: "ini"},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
            } else {
                alert(datos);
                alertify.success(datos);
                //cambiarboton();

            }
            cargandoHide();
        }
    });
}

function getSaldo() {
    cargandoHide();
    cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'getsaldo'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            var today = new Date();
            if (bandera == 0) {
                //alert(datos);
                alertify.error(res);
            } else {
                var array = datos.split("</tr>");
                //alert(datos);
                changeText("#contenedor-timbres", array[3]);
                changeText("#contenedor-usados", array[2]);
                changeText("#contenedor-plan", array[0]);
            }
            cargandoHide();
        }
    });
}

function copyFolder() {
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'copyfolder'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            var today = new Date();
            if (bandera == 0) {
                alertify.error(res);
                //cargandoHide();
            } else {
                alert(datos);
                //cargandoHide();
            }
        }
    });
}

function datosGrafica() {
    cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'datosgrafica'},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            var today = new Date();
            changeText("#contenedor-titulo-facturas-emitidas", "Facturas emitidas en " + today.getFullYear());
            grafica(datos);
            //cargandoHide();
        }
    });
}

function dynamicColors() {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return "rgba(" + r + "," + g + "," + b + ", 1)";
}

function poolColors(a) {
    var pool = [];
    for (i = 0; i < a; i++) {
        pool.push(dynamicColors());
    }
    return pool;
}

function grafica(datos) {
    var array = datos.split("<dataset>");
    var data1 = array[0].split("</tr>");
    var data2 = array[1].split("</tr>");
    var data3 = array[2].split("</tr>");
    var data4 = array[3].split("</tr>");
    var colors = poolColors(data2.length);

    var popCanvas = $("#chart1");
    var barChart = new Chart(popCanvas, {
        type: 'bar',
        data: {
            labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            datasets: [{
                    label: 'Montos en MXN',
                    data: data2,
                    backgroundColor: colors,
                    borderColor: colors,
                    borderWidth: 2
                }]
        },
        options: {

            plugins: {
                labels: {
                    render: function (args) {
                        return '$' + args.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var montoT = "Total del Mes: $ " + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        var emitidoT = "Facturas Timbradas: " + data1[tooltipItem.index];
                        var canceladoT = "Facturas Canceladas: " + data3[tooltipItem.index];
                        var sintimbreT = "Facturas sin Timbrar: " + data4[tooltipItem.index];
                        var tooltip = [montoT, emitidoT, canceladoT, sintimbreT]; //storing all the value here
                        return tooltip; //return Array back to function to show out
                    }
                }
            },
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            },
            animation: {
                duration: 500,
                easing: 'linear'
            }
        }
    });
    cargandoHide();
}

function buscarGrafica() {
    var ano = $("#opciones-ano").val();
    var d = new Date();
    var y = d.getFullYear();
    if (ano == '') {
        ano = y;
    }
    cargandoShow();
    $.ajax({
        url: 'com.sine.enlace/enlaceinicio.php',
        type: 'POST',
        data: {transaccion: 'buscargrafica', ano: ano},
        success: function (datos) {
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 5000);
            changeText("#contenedor-titulo-facturas-emitidas", "Facturas emitidas en " + ano);
            $("#chart1").remove();
            $("#chart-div").append("<canvas id='chart1' style='height:100px;width: 300px;'></canvas>");
            grafica(datos);
        }
    });
}

function filtrarNotificaciones(pag = "") {
    cargandoHide();
    cargandoShow();

    $.ajax({
        url: "com.sine.enlace/enlaceinicio.php",
        type: "POST",
        data: {transaccion: "filtrarnotificaciones", pag: pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                
                $("#body-lista-notificacion").html(datos);
                cargandoHide();
            }
        }
    });
}

function buscarNotificaciones(pag = "") {
    cargandoHide();
    cargandoShow();

    $.ajax({
        url: "com.sine.enlace/enlaceinicio.php",
        type: "POST",
        data: {transaccion: "filtrarnotificaciones", pag: pag},
        success: function (datos) {
            //alert(datos);
            var texto = datos.toString();
            var bandera = texto.substring(0, 1);
            var res = texto.substring(1, 1000);
            if (bandera == '0') {
                alertify.error(res);
                cargandoHide();
            } else {
                
                $("#body-lista-notificacion").html(datos);
                cargandoHide();
            }
        }
    });
}