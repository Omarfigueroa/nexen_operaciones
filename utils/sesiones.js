
/*
function rastrear_click(){
    var links = document.querySelectorAll('a');
    for (var i = 0; i < links.length; i++) {
    links[i].addEventListener('click', function(event) { 
        console.log('Hola');
        // Evita el comportamiento predeterminado del enlace
        event.preventDefault();
        
        // Obtén la URL de destino del enlace
        targetUrl = this.href;
        
        // Realiza cualquier acción adicional basada en la URL de destino
        console.log('Se hizo clic en el enlace con destino a:', targetUrl);
        
        // Realiza la redirección manualmente si es necesario o cerrar sesión 
        window.location.href = targetUrl;
    });
    }
}


window.addEventListener('beforeunload', function() {
    // milisegundos que espera el navegador antes de cerrar la pagina
    var x = 200;
    var a = (new Date()).getTime() + x;
 
    //console.log(targetUrl);
    if(targetUrl!=""){
        console.log('Entro'); 
    }else{
        console.log('No Entro');
            //Antes de cerrar la pagina ejecuta el retardo y envia ajax para hacer logout.php
            $.ajax({
                url:'logout.php',
                method:"GET",
                data:{},
                success:function(data)
                {
                    
                }
            })
            while ((new Date()).getTime() < a) {} 
    }

}, false)

*/

var n=0;
var cerrada = 0;
var periodo_sesion = window.setInterval(checa_sesion, 1000); //Revisa cada 1 segundo
//alert('Hola');
function checa_sesion() {
    
 
    document.onmousemove = function(){
        n=0;
    }; 

    n++;
    
    if(n>=600){
 
        $.ajax({
            url:'logout.php',
            method:"GET",
            data:{},
            success:function(data)
            {
            }
        });
        
        const container_cerrar = document.getElementById("cerrarSesion");
        const modal_cerrar = new bootstrap.Modal(container_cerrar);
        modal_cerrar.show();

        window.clearInterval(periodo_sesion);
    }
}