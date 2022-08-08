function nuevo_registro(enteId) {
    $.ajax({
        url: "/registro/new2/",
        type: "post",
        dataType: "html",
        data: {
            id: enteId,
        },
    }).done(function (res) { // console.log(res);
        document.location = res;
    });    
}
