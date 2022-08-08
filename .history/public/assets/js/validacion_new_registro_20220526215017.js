function nuevo_registro(enteId) {
    $.ajax({
        url: "/registro/new2/",
        type: "post",
        dataType: "html",
        data: {
            id: enteId,
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
}
