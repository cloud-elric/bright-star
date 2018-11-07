$(document).on({
    'change': function(){
        
        var elemento = $(this);
        var token = $(this).data("token");
        console.log("2-"+token);        
        $.ajax({
            url:baseUrl+"codigo-postal-disponibilidad/activar-cp?id="+token,
            success: function(r){
                if(r.status=="success"){
                    
                }else{
                    swal("Problema al guardar","No se pudo bloquear al usuario: "+ r.message, "error");
                }
            },
            error:function(x,y,z){
                swal("Problema al guardar","No se pudo bloquear al usuario: "+ y, "error");
            }
            
        });
    }
}, ".btn-active:not(.active)");

$(document).on({
    'click': function(){
        
        var elemento = $(this);
        var token = $(this).data("token");
        console.log("2-"+token);
        $.ajax({
            url:baseUrl+"codigo-postal-disponibilidad/bloquear-cp?id="+token,
            success: function(r){
                if(r.status=="success"){
                    
                }else{
                    swal("Problema al guardar","No se pudo bloquear al usuario: "+ r.message, "error");
                }
            },
            error:function(x,y,z){
                swal("Problema al guardar","No se pudo bloquear al usuario: "+ y, "error");
            }
            
        });
    }
}, ".btn-inactive:not(.active)");