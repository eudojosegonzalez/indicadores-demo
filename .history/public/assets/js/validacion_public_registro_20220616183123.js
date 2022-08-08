function search_registro(enteId) {
    $.ajax({
        url: "/publico/public_busca_datos/",
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
