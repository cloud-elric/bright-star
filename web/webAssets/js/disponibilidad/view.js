$(document).ready(function(){
    $(".js-eliminar-disponibilidad").on('click', function(){
        var id = $(this).data('id');
        var url = $(this).data('url');

        $.ajax({
            url: url+'/codigo-postal-disponibilidad/delete-cp?id='+id,
            success: function(){
                
            }
        });
    });
});