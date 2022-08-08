function pagina(){
    var can_reg=document.getElementById("can_reg").value;
    titulo = 'Atención'
    parrafo = "<span class='text-success'>Espere por favor<p  align='center'><img src='../../assets/images/wait2.gif' width='50' height='50'></span>"
    $('#title_modal').html(titulo)
    $('#content_modal').html(parrafo)
    $('#myModal').modal()
    document.location = '/publico/public_categoria/?can_reg='+can_reg+'&pag=1';  
    

}
