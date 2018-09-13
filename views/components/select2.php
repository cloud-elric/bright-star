<?php

use yii\web\View;
$resultsJs = <<< JS
function (data, params) {
    
    params.page = params.page || 1;
    return {
        results: data.results,
        pagination: {
            more: (params.page * 10) < data.total_count
        }
    };
}
JS;

$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    var ajaxResults = $('#entcitas-id_horario').depdrop('getAjaxResults');
    console.log(ajaxResults);
    var cantidadDisponible = 0;
   
    if (repo.loading) {
        return repo.text;
    }


    if(!repo.loading){
        for($i=0; $i<ajaxResults["output"].length; $i++){

            if(ajaxResults["output"][$i]['id'] == repo.id){
                cantidadDisponible = ajaxResults["output"][$i]['cantidad'];
            }
        }
    }
    var markup =
'<div class="row">' + 
    '<div class="col-md-12">' +
        '<b style="margin-left:5px">' + repo.text + '</b>' + 
    '</div>' +
    // '<div class="col-md-4">' + cantidadDisponible + '</div>' +
'</div>';
    
    return '<div style="overflow:hidden;">' + markup + '</div>';
};

var formatRepoEquipo = function (repo) {
    console.log(repo);
    if (repo.loading) {
        return repo.text;
    }

    var markup =
        '<div class="row">' + 
            '<div class="col-md-12">' +
                '<b style="margin-left:5px">' + repo.txt_nombre + '</b>' + 
            '</div>' +
            
        '</div>';
    
    return '<div style="overflow:hidden;">' + markup + '</div>';
};


var formatRepoCat = function (repo) {
    
    if (repo.loading) {
        return repo.text;
    }

    var markup =
        '<div class="row">' + 
            '<div class="col-md-12">' +
                '<b style="margin-left:5px">' + repo.txt_nombre + '</b>' + 
            '</div>' +
            
        '</div>';
    
    return '<div style="overflow:hidden;">' + markup + '</div>';
};

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
?>