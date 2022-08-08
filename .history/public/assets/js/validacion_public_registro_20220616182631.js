function nuevo_registro(enteId) {
    $.ajax({
        url: "/publico/public_consulta/",
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
