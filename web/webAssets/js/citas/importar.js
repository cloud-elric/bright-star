$(document).ready(function(){
    $(".js-input-file").on('change', function(){
        var url = $(this).data("url");
        var formulario = $(this).parents("form");
        var formData = new FormData(formulario.get(0));
        
        var input = $(this);

        $.ajax({
            url: url + '/citas/import-data-municipios',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(resp){
                if(resp.status == "success"){
                    swal("Correcto", "Los datos se guardaron correctamente", "success");
                }else{
                    swal("Espera", "Hubo un error, intentelo de nuevo", "warning");
                }
                input.val('');
            },
            error: function(){
                swal("Espera", "Hubo un error, intentelo de nuevo", "warning");                
                input.val('');
            }
        });
    });
});