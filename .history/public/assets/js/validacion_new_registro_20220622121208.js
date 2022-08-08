function nuevo_registro(enteId) {
    $.ajax({
        url: "/carga/new2/",
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

function categorias(enteId) {
    $.ajax({
        url: "/categoria/index2/",
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

function pagina2(){
    var can_reg=document.getElementById("can_reg").value;
    var enteId=document.getElementById("enteid").value;    
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/categoria/index3/?can_reg='+can_reg+'&page=1&&enteId='+enteId; 
}
