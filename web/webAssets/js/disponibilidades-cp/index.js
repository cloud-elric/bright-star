

$(document).ready(function (){
    $("#pjax-disponibilidades-cp").on('pjax:complete', function() {
        recargarSelect2(".js-filtro-municipio");
        recargarSelect2(".js-filtro-area");
        
        
      })

});

function recargarSelect2(identificador){
    $(".kv-plugin-loading").remove();
    var $el = $(identificador), // your input id for the HTML select input
    settings = $el.attr("data-krajee-select2");
    settings = window[settings];
    // reinitialize plugin
    $el.select2(settings);
}


$(document).on({
    'change': function(){
        
        var elemento = $(this);
        var token = $(this).data("token");
        $.ajax({
            url:baseUrl+"codigo-postal-disponibilidad/habilitar-horario?id="+token,
            success: function(r){
               
                    toastr.success("Habilitado");
                
            },
            error:function(x,y,z){
                toastr.warning("Ocurrio un problema");
            }
            
        });
    }
}, ".btn-active:not(.active)");

$(document).on({
    'click': function(){
        
        var elemento = $(this);
        var token = $(this).data("token");
        $.ajax({
            url:baseUrl+"codigo-postal-disponibilidad/deshabilitar-horario?id="+token,
            success: function(r){
                toastr.success("Deshabilitado");
            },
            error:function(x,y,z){
                toastr.warning("Ocurrio un problema");
            }
            
        });
    }
}, ".btn-inactive:not(.active)");