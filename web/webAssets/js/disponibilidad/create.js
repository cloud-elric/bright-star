$(document).ready(function(){
    $("#js-btn-submit-disponibilidad").on('click', function(){
        var form = $("#js-crear-disponibilidad").serializeArray();
        var url = $(this).data('url');

        var dias = [];
		$("input[name=check1]:checked").each( function () {
            dias.push( $(this).val() );
        });
        if(dias.length > 0){
            form.push({name:'dias', value: dias});
        }console.log(form);

        $.ajax({
            url: url+'/codigo-postal-disponibilidad/crear-disponibilidad',
            type: 'POST',
            data: form,
            success: function(resp){
                if(resp.status == 'error'){
                    toastr.warning('Hubo un error intentelo de nuevo por favor.')
                }
            },
            error: function(){

            }
        });
    });
});