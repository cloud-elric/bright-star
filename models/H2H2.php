<?php
namespace app\models;

class H2H2
{

    const URL_API = "https://h2hls.azurewebsites.net/System/Brightstar";
    const ACTION_CREAR = "Evt0001";
    const ACTION_CONSULTAR = "Evt0002";
    const ACTION_CONSULTAR_HISTORICO = "Evt0003";

    public $numServicio;
    public $observaciones;
    public $observacionesContenido;
    public $nombre;#
    public $email;
    public $cp;#
    public $calle;
    public $numInterior;
    public $numExterior;
    public $estado;
    public $municio;
    public $colonia;
    public $direccion;
    public $telefonoRef;
    public $contenido;#
    public $empaque;
    public $cantidad = 1;
    public $valor;
    public $peso;
    public $altura;
    public $ancho;
    public $largo;
    public $numeroTracking;
    public $fechaCita;
    public $fechaHora;

    public function crearEnvio($cita)
    {
        $this->setDataCrearEnvio($cita);
        
        return $this->crearEnvioCall();
    }

    public function consultarEnvio($tracking){
        $this->setDataConsultarEnvio($tracking);

        return $this->consultarEnvioCall();
    }

    public function consultarHistorico($tracking){
        $this->setDataConsultarEnvio($tracking);

        return $this->consultaHistoricoCall();
    }

    public function setDataCrearEnvio($cita){
        $this->numServicio = $cita->txt_identificador_cliente;
        $this->observaciones = $cita->txt_entre_calles." ".$cita->txt_observaciones_punto_referencia;
        $this->observacionesContenido = $cita->promocionalesData;
        $this->nombre = $cita->txt_nombre." ".$cita->txt_apellido_paterno." ".$cita->txt_apellido_materno;
        $this->email = $cita->txt_email;
        $this->cp = $cita->txt_codigo_postal;
        $this->calle = $cita->txt_calle_numero;
        $this->numInterior = "";
        $this->numExterior = "";
        $this->estado = $cita->idArea->txt_nombre;
        $this->municipio = $cita->txt_municipio;
        $this->colonia = $cita->txt_colonia;
        $this->direccion = "";
        $this->telefonoRef = $cita->txt_telefono."|".$cita->txt_numero_referencia."|".$cita->txt_numero_referencia_2."|".$cita->txt_numero_referencia_3;
        $this->contenido = $cita->txt_equipo;
        $this->empaque = "";
        $this->valor = $cita->txt_tpv?$cita->txt_tpv:0;
        $this->fechaCita = $cita->fch_cita;

        $horario = "";
        if($cita->b_entrega_cat && $cita->id_cat){
            $horario = $cita->txt_horario_entrega_cat;
        }else{
            $horario = $cita->idHorario?$cita->idHorario->txt_hora_inicial." - ".$cita->idHorario->txt_hora_final:"";
        }
        $this->fechaHora = $horario;
    }


    public function setDataConsultarEnvio($tracking){
        $this->numeroTracking = $tracking;
    }

    public function getParamsCrear()
    {
        $parametros = [
            'Nombre' => $this->nombre,
            'CP' => $this->cp,
            'CalleNumero' => $this->calle,
            'Estado' => $this->estado,
            'Municipio' => $this->municipio,
            'Colonia' => $this->colonia,
            'Telefonos' => $this->telefonoRef,
            'Contenido' => $this->contenido,
            'Cantidad' => $this->cantidad,
            'Valor' => $this->valor,
            'NoServicio' => $this->numServicio,
            'ObservacionesContenido' => $this->observacionesContenido,
            'EMail'=>$this->email,
            'Referencias'=>$this->observaciones,
            "CitaFecha"=>$this->fechaCita,
            "CitaHora"=>$this->fechaHora
            
        ];

        return $parametros;
    }

    public function getParamsConsultar()
    {
        $parametros = [
            'NoTracking' => $this->numeroTracking,
        ];

        return $parametros;
    } 


    public function crearEnvioCall()
    {

        $parametros = $this->getParamsCrear();
        
        $fields = [
            "Code" => 'u7Ig3QwPM+0mPESDkcZQ2zjyLHIbiMBdoRiVY0YMbGs=',
            "Action" => self::ACTION_CREAR,
            "Parameters" => $parametros
        ];
                                                                    
        $data_string = json_encode($fields); 

        // echo $data_string;

        // exit;

        //url-ify the data for the POST
        //$field_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::URL_API);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER,[                                                                          
            'Content-Type: application/json',                                                                                
            //'Content-Length: ' . strlen($data_string))                                                                       
        ]); 

        //execute post
        $result = curl_exec($ch);
        
        $info = curl_getinfo($ch);

        //  print_r($result);
        //  exit;
        //close connection
        curl_close($ch);

        return $result;
    }

    public function consultarEnvioCall()
    {
        $parametros = $this->getParamsConsultar();
        $fields = [
            "Code" => 'RGq1WOgyoyJBtj88FwjfecfoFujTQyg+sW8pyQ275xg=',
            "Action" => self::ACTION_CONSULTAR,
            "Parameters" => $parametros
        ];
                                                                    
        $data_string = json_encode($fields); 

        //url-ify the data for the POST
        //$field_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::URL_API);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER,[                                                                          
            'Content-Type: application/json',                                                                                
            //'Content-Length: ' . strlen($data_string))                                                                       
        ]); 

        //execute post
        $result = curl_exec($ch);
        
        $info = curl_getinfo($ch);

        #print_r($info);
        //close connection
        curl_close($ch);

        #print_r($result);
        #exit;
        return $result;
    }

    public function consultaHistoricoCall(){

        $parametros = $this->getParamsConsultar();
       
        $fields = [
            "Code" => 'G8C0z4oYaHlTiVE8km3+a7xSnAKQA5iwsJQvVim2Sz4=',
            "Action" => self::ACTION_CONSULTAR_HISTORICO,
            "Parameters" => $parametros
        ];
                                                                    
        $data_string = json_encode($fields); 

        //url-ify the data for the POST
        //$field_string = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, self::URL_API);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($ch, CURLOPT_HTTPHEADER,[                                                                          
            'Content-Type: application/json',                                                                                
            //'Content-Length: ' . strlen($data_string))                                                                       
        ]); 

        //execute post
        $result = curl_exec($ch);
        
        $info = curl_getinfo($ch);
       
        #print_r($info);
        //close connection
        curl_close($ch);

        #print_r($result);
        #exit;
        return $result;
    }
}