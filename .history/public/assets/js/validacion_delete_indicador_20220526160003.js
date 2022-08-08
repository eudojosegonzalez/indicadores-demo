function borrar(idproceso) {
    var rrr=confirm("¿Está seguro de que desea eliminar este Organismo Acreditador?");
    if (rrr==true){
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    $.ajax({
        url: "/ente/delete_ente/",
        type: "post",
        dataType: "html",
        data: {
            id: idproceso,
        },
    }).done(function (res) {
        var data = JSON.parse(res)
        console.log(data);
        if (data.length > 0) {
            switch (data[0]) {
                case "1": // --- todo salio bien
                    titulo = 'Atención'
                    parrafo = "<span class='text-success'>Se eliminó el Organismo de forma exitosa, actualizando contenido de la página</span><p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></p>"
                    $('#title_modal').html(titulo)
                    $('#content_modal').html(parrafo)
                    $('#myModal').modal()
                    setTimeout(function () {
                    $('#myModal').modal('hide');
                    document.location = '/periodo/'
                    }, 5000);
                    break;
                case "-1": // --- error
                    titulo = 'Atención'
                    parrafo = "<span class='text-danger'>Existen Indicadores dependientes de este Organismo<br>No puede eliminarlos hasta que estos sean removidos previamente</span>"
                    $('#title_modal').html(titulo)
                    $('#content_modal').html(parrafo)
                    $('#myModal').modal()
                    break;                    
                case "0": // --- error
                    titulo = 'Atención'
                    parrafo = "<span class='text-danger'>Ocurrió un error que no pudo ser controlado<br>Inténtelo de nuevo</span>"
                    $('#title_modal').html(titulo)
                    $('#content_modal').html(parrafo)
                    $('#myModal').modal()
                    break;
            }
        
        } else {
            titulo = 'Atención'
            parrafo = "<span class='text-danger'>Ocurrió un error que no pudo ser controlado<br>Inténtelo de nuevo</span>"
            $('#title_modal').html(titulo)
            $('#content_modal').html(parrafo)
            $('#myModal').modal()
        }
    });
} else {
    titulo = 'Atención'
    parrafo = "<span class='text-info'>No se efectuo ningun cambio</span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()    
}
        
}
function pagina(){
    var can_reg=document.getElementById("can_reg").value;
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/indicador/?can_reg='+can_reg+'&page=1';  
    

}

function cambio_ente(){
    var ente_id=document.getElementById("ente").value;
    if (ente_id!="-99") {
        $.ajax({
            url: "get_categoria/",
            type: "POST",
            dataType: "html",
            data: {enteid: ente_id}
        }).done(function (res) {
            var data = JSON.parse(res)  
            //console.log(data);  
            document.getElementById("categoria").length=0;
            var html = "<option value='-99'>Todas</option>";
            if (data.length > 0) {
                for (var i = 0; i < data.length; i++) {
                       html += "<option value='" + data[i]['id'] + "'>" + data[i]['nombre'] + "</option>"
                }

            } else {
                titulo = 'Atención'
                parrafo = "<span class='text-danger'>No se consiguieron categorias para este Organismo Certificador<br>Inténtelo de nuevo</span>"
                $('#title_modal').html(titulo)
                $('#content_modal').html(parrafo)
                $('#myModal').modal()                
            }
            document.getElementById("categoria").innerHTML = html;
        });
    }else{
        document.getElementById("categoria").length=0;
        var html = "<option value='-99'>Todas</option>";
        document.getElementById("categoria").innerHTML = html;        
    }
}

function filtrar(){
    var can_reg=document.getElementById("can_reg").value;
    var categoriaId=document.getElementById("categoria").value;
    var enteId=document.getElementById("ente").value;

    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/indicador/?can_reg='+can_reg+'&pag=1&categoriaId='+categoriaId+'&enteId='+enteId; 

}

function limpiar(){
    var can_reg=document.getElementById("can_reg").value;
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/indicador/?can_reg='+can_reg+'&pag=1' ;    
}