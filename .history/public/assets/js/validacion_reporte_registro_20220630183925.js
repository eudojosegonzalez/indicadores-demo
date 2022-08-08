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
            console.log (data[0].length);
            var cadena ="<div class='row'>";
            cadena+="<div class='col-12 text-right'><button  class='btn boton02' onClick=window.open('"+data[1]+"')>Descarga <img src='../assets/images/pdf.png' width='36' heigth='auto'></button>";
            cadena+="&nbsp;<button  class='btn boton02' onClick=window.open('"+data[2]+"')>Descarga <img src='../assets/images/excel.png' width='36' heigth='auto'></button></div></div>";            
            cadena+="<div class='row'><table class='table table-striped table-bordered'><thead><tr align='center' class='boton02'><th>Item</th><th>Nombre</th>";
            cadena+="<th>Definición</th><th>Fórmula</th><th>Periodo</th><th>Valor</th></tr></thead><tbody>";
            for (i=0; i < data[0].length; i++){
                cadena+="<tr><td align='center'>"+(i+1)+"</td><td>"+data[0][i]['concepto']+"</td>";
                cadena+="<td>"+data[0][i]['definicion']+"</td>";
                cadena+="<td>"+data[0][i]['formula']+"</td>";
                cadena+="<td  align='center'>"+data[0][i]['periodo']+"</td>";
                if (data[0][i]['unidad']!='%') {
                    cadena+="<td align='right'><label>"+(Math.abs(data[0][i]['valor'])*100)+"%";
                } else {               
                    cadena+="<td align='right'><label>"+data[0][i]['valor'];
                }
                cadena+"</label></td></tr>";                
            }
            cadena+="</table></div><div class='row'>&nbsp;</div>";
            cadena+="<div class='row'>";
            cadena+="<div class='col-12 text-right'><button  class='btn boton02' onClick=window.open('"+data[1]+"')>Descarga <img src='../assets/images/pdf.png' width='36' heigth='auto'></button>";
            cadena+="&nbsp;<button  class='btn boton02' onClick=window.open('"+data[2]+"')>Descarga <img src='../assets/images/excel.png' width='36' heigth='auto'></button></div></div>";           

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
