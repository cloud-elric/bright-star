$(document).ready(function(){
    $(".js-cupo").on("change", function(){
        var elemento = $(this);
        var token = elemento.data("token");
        var value = elemento.val();

        $.ajax({
            url:baseUrl+"horarios-areas/update-cupo?token="+token,
            method: "POST",
            data: {
                cupo: value,
            },
            success:function(e){
                toastr.success("Datos guardados correctamente.");
            },error:function(xhr, text, error){
                
                toastr.warning("No se pudieron guardar los datos: "+xhr.responseText);
            }
        });
    });
});

function configToastr(){
    toastr.options = {
        "closeButton": true,
        
        "positionClass": "toast-top-full-width",
        "preventDuplicates": true,
        
        "showDuration": "3000",
        "hideDuration": "10000",
        "timeOut": "5000",
        "extendedTimeOut": "10000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }
}
