function reporte(enteId) {
    $.ajax({
        url: "/reporte/",
        type: "POST",
        dataType: "html",
        data: {
            id: enteId,
        },
    }).done(function (res) { // 
        console.log(res);
        document.location = res;
    });    
}

function filtrar(){
    var enteId=document.getElementById("enteid").value;
    var reporteId=document.getElementById("reporte").value;
    var periodoId=document.getElementById("periodo").value;
    console.log(reporteId);
    $.ajax({
        url: "/reporte/busca_indicadores/",
        type: "post",
        dataType: "html",
        data: {
            enteId: enteId,
            reporteId: reporteId,
            periodoId: periodoId,
        },
    }).done(function (res) {
        var data = JSON.parse(res);
        console.log(data);
        $('#myModal').modal('hide');
        if (data[0].length > 0) {
            var cadena="<div class='row'><table class='table table-striped table-bordered'><thead><tr align='center' class='boton02'><th>Nombre</th><th>Valor</th></tr></thead><tbody>";
            for (i=0; i<data[0].length; i++){
                cadena+="<tr><td>"+data[0][i]['concepto']+"</td><td>";
                cadena+="<input type='text' class='form-control text-right' id='ind_0"+i+"' name='ind_0"+i+"' value='"+data[0][i]['valor']+"' size='10'></td></tr>";
            }
            cadena+="</table></div><div class='row'>&nbsp;</div>";
            cadena+="<div class='row'>";
            cadena+="<div class='col-12 text-right'><input type='button' class='btn boton02' value='Descarga PDF' conClick='window.open("+data[1]+")'></div>";
            cadena+="</div>";

            document.getElementById("div001").innerHTML=cadena;
            $('#myModal').modal('hide');
            console.log(data[1])
        } else {
            titulo = 'Atención'
            parrafo = "<span class='text-danger'>Ocurrió un error que no pudo ser controlado<br>Inténtelo de nuevo</span>"
            $('#title_modal').html(titulo)
            $('#content_modal').html(parrafo)
            $('#myModal').modal()
        }
    });        
}

function limpiar(){
    document.getElementById("div001").innerHTML="";
    document.location.reload();        
}
