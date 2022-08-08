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
