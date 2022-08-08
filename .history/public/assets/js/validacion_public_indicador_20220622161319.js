function pagina(){
    var can_reg=document.getElementById("can_reg").value;
    var categoriaId=document.getElementById("categoria").value;
    var enteId=document.getElementById("ente").value;    
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    //document.location = '/indicador/?can_reg='+can_reg+'&page=1';  
    document.location = '/publico/public_indicador/?can_reg='+can_reg+'&page=1&categoriaId='+categoriaId+'&enteId='+enteId; 
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
    var enteId=document.getElementById("enteid").value;

    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/publico/public_indicador/?can_reg='+can_reg+'&page=1&categoriaId='+categoriaId+'&enteId='+enteId; 

}

function limpiar(){
    var can_reg=document.getElementById("can_reg").value;
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/publico/public_indicador/?can_reg='+can_reg+'&page=1' ;    
}