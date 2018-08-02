<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntCitas;
use app\modules\ModUsuarios\models\Utils;
use yii\db\Expression;

/**
 * EntCitasSearch represents the model behind the search form about `app\models\EntCitas`.
 */
class EntCitasSearch extends EntCitas
{
    public $startDate;
    public $endDate;
    public $nombreCompleto;
    public $txtTracking;
    public $pageSize;
    public $startDateCita;
    public $endDateCita;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_cita', 'id_tipo_tramite', 'id_equipo', 'id_area', 'id_tipo_entrega', 'id_usuario', 'id_status',  'id_tipo_cliente', 'id_tipo_identificacion', 'id_horario'], 'integer'],
            [['txtTracking','pageSize','startDateCita','endDateCita', 'nombreCompleto','startDate','endDate','txt_telefono', 'txt_identificador_cliente','txt_nombre', 'txt_apellido_paterno', 'txt_apellido_materno', 'txt_rfc', 'txt_numero_telefonico_nuevo', 'txt_email', 'txt_folio_identificacion', 'fch_nacimiento', 'num_dias_servicio', 'txt_token', 'txt_iccid', 'txt_imei', 'txt_numero_referencia', 'txt_numero_referencia_2', 'txt_numero_referencia_3', 'txt_estado', 'txt_calle_numero', 'txt_colonia', 'txt_codigo_postal', 'txt_municipio', 'txt_entre_calles', 'txt_observaciones_punto_referencia', 'txt_motivo_cancelacion_rechazo', 'fch_cita', 'fch_creacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchMes($params)
    {
        $query = EntCitas::find()->with("idStatus")->leftJoin("ent_envios", "ent_envios.id_envio = ent_citas.id_envio");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize' => 30,
                
                
            ],
            
            'sort' => [
                'defaultOrder' => [
                    'fch_creacion' => \SORT_DESC
                ]
            ],
            
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['nombreCompleto'] = [
            
                'asc' => ['txt_nombre' => SORT_ASC, 'txt_apellido_paterno' => SORT_ASC],
                'desc' => ['txt_nombre' => SORT_DESC, 'txt_apellido_paterno' => SORT_DESC],
                
                
            
        ];

        $dataProvider->sort->attributes['txtTracking']=[
            'asc' => ['ent_envios.txt_tracking' => SORT_ASC],
                'desc' => ['ent_envios.txt_tracking' => SORT_DESC],
        ];
      

        $this->load($params);

        if($this->fch_cita){
            
            $this->fch_cita = Utils::changeFormatDateInputShort($this->fch_cita);
            
        }

        if($this->fch_creacion){
            
            $this->fch_creacion = Utils::changeFormatDateInputShort($this->fch_creacion);
            
        }

        $usuario = Yii::$app->user->identity;
        
        $query = Permisos::getCitasByRole($query);
        
           
        $query->andFilterWhere(['id_status' => $this->id_status]);
            
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            
            'id_tipo_tramite' => $this->id_tipo_tramite,
            
            
            
        
        ]);

        if(!$this->fch_creacion){
            //$this->fch_creacion = Utils::changeFormatDateInputShort($this->fch_creacion);
            //$query->andFilterWhere(['>', new Expression('date_format(fch_creacion, "%Y-%m-%d")'), new Expression('date_format((CURRENT_DATE- INTERVAL 1 MONTH), "%Y-%m-%d")')]);
        }

        $query
        
            ->andFilterWhere(['like', 'txt_token', $this->txt_token])
            ->andFilterWhere(['like', 'txt_iccid', $this->txt_iccid])
            ->andFilterWhere(['like', 'txt_imei', $this->txt_imei])
            ->andFilterWhere(['like', 'txt_telefono', $this->txt_telefono])
            ->andFilterWhere(['like', 'txt_identificador_cliente', $this->txt_identificador_cliente])
            ->andFilterWhere(['like', 'txt_numero_referencia', $this->txt_numero_referencia])
            ->andFilterWhere(['like', 'txt_numero_referencia_2', $this->txt_numero_referencia_2])
            ->andFilterWhere(['like', 'txt_numero_referencia_3', $this->txt_numero_referencia_3])
            ->andFilterWhere(['like', 'txt_calle_numero', $this->txt_calle_numero])
            ->andFilterWhere(['like', 'txt_colonia', $this->txt_colonia])
            ->andFilterWhere(['like', 'txt_codigo_postal', $this->txt_codigo_postal])
            ->andFilterWhere(['like', 'txt_municipio', $this->txt_municipio])
            ->andFilterWhere(['like', 'txt_entre_calles', $this->txt_entre_calles])
            ->andFilterWhere(['like', 'txt_oservaciones_punto_referencia', $this->txt_observaciones_punto_referencia])
            ->andFilterWhere(['like', 'fch_cita', $this->fch_cita])
            ->andFilterWhere(['like', 'ent_envios.txt_tracking', $this->txtTracking]);

           

            // filter by person full name
            if($this->nombreCompleto){
                $query->andFilterWhere(['like','CONCAT(txt_nombre, " ", txt_apellido_paterno)',  $this->nombreCompleto]);
            }    

            if($this->fch_cita){
            
                $this->fch_cita = Utils::changeFormatDate($this->fch_cita);
                
            }
    
            if($this->fch_creacion){
                
                $this->fch_creacion = Utils::changeFormatDate($this->fch_creacion);
                
            }

            //   $query->joinWith(['entEnvios' => function ($q) {
            //       if($this->txtTracking){
            //         $q->where('ent_envios.txt_tracking LIKE "%' . $this->txtTracking . '%"');
            //       }
            //   }]);
        
        return $dataProvider;
    }

    

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = EntCitas::find()->leftJoin("ent_envios", "ent_envios.id_envio = ent_citas.id_envio");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                'pageSize' => 30,
            ],
            
            'sort' => [
                'defaultOrder' => [
                    'fch_creacion' => \SORT_DESC
                ]
            ],
            
        ]);

        // Important: here is how we set up the sorting
        // The key is the attribute name on our "TourSearch" instance
        $dataProvider->sort->attributes['nombreCompleto'] = [
            
                'asc' => ['txt_nombre' => SORT_ASC, 'txt_apellido_paterno' => SORT_ASC],
                'desc' => ['txt_nombre' => SORT_DESC, 'txt_apellido_paterno' => SORT_DESC],
                
                
            
        ];

        $dataProvider->sort->attributes['txtTracking']=[
            'asc' => ['ent_envios.txt_tracking' => SORT_ASC],
                'desc' => ['ent_envios.txt_tracking' => SORT_DESC],
        ];
      

        $this->load($params);

        if($this->fch_cita){
            
            $this->fch_cita = Utils::changeFormatDateInputShort($this->fch_cita);
            
        }

        if($this->fch_creacion){
            
            $this->fch_creacion = Utils::changeFormatDateInputShort($this->fch_creacion);
            
        }

        $usuario = Yii::$app->user->identity;
        
        $query = Permisos::getCitasByRole($query);
        
           
        $query->andFilterWhere(['id_status' => $this->id_status]);
            
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            
            'id_tipo_tramite' => $this->id_tipo_tramite,
            
            
            
        
        ]);

        $query
        
            ->andFilterWhere(['like', 'txt_token', $this->txt_token])
            ->andFilterWhere(['like', 'txt_iccid', $this->txt_iccid])
            ->andFilterWhere(['like', 'txt_imei', $this->txt_imei])
            ->andFilterWhere(['like', 'txt_telefono', $this->txt_telefono])
            ->andFilterWhere(['like', 'txt_identificador_cliente', $this->txt_identificador_cliente])
            ->andFilterWhere(['like', 'txt_numero_referencia', $this->txt_numero_referencia])
            ->andFilterWhere(['like', 'txt_numero_referencia_2', $this->txt_numero_referencia_2])
            ->andFilterWhere(['like', 'txt_numero_referencia_3', $this->txt_numero_referencia_3])
            ->andFilterWhere(['like', 'txt_calle_numero', $this->txt_calle_numero])
            ->andFilterWhere(['like', 'txt_colonia', $this->txt_colonia])
            ->andFilterWhere(['like', 'txt_codigo_postal', $this->txt_codigo_postal])
            ->andFilterWhere(['like', 'txt_municipio', $this->txt_municipio])
            ->andFilterWhere(['like', 'txt_entre_calles', $this->txt_entre_calles])
            ->andFilterWhere(['like', 'txt_oservaciones_punto_referencia', $this->txt_observaciones_punto_referencia])
            ->andFilterWhere(['like', 'fch_cita', $this->fch_cita])
            ->andFilterWhere(['like', 'fch_creacion', $this->fch_creacion])
            ->andFilterWhere(['like', 'ent_envios.txt_tracking', $this->txtTracking]);

           

            // filter by person full name
            if($this->nombreCompleto){
                $query->andFilterWhere(['like','CONCAT(txt_nombre, " ", txt_apellido_paterno)',  $this->nombreCompleto]);
            }    

            if($this->fch_cita){
            
                $this->fch_cita = Utils::changeFormatDate($this->fch_cita);
                
            }
    
            if($this->fch_creacion){
                
                $this->fch_creacion = Utils::changeFormatDate($this->fch_creacion);
                
            }

            //   $query->joinWith(['entEnvios' => function ($q) {
            //       if($this->txtTracking){
            //         $q->where('ent_envios.txt_tracking LIKE "%' . $this->txtTracking . '%"');
            //       }
            //   }]);
        
        return $dataProvider;
    }

    public function searchExport($params){
        $query = EntCitas::find()->with(
            "idEnvio", "idArea", "idHorario", 
            "idCat",
            "idStatus", "idTipoCliente", "idMunicipio", 
            "idTipoTramite", "idCallCenter", "idMunicipio.idTipo");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>false,
            
            'sort' => [
                'defaultOrder' => [
                    'fch_creacion' => \SORT_DESC
                ]
            ],
            
        ]);

        $this->load($params);

        if($this->fch_cita){
            
            $this->fch_cita = Utils::changeFormatDateInputShort($this->fch_cita);
            $query->andFilterWhere([
                "=","date_format(fch_cita, '%Y-%m-%d')",$this->fch_cita
            
            ]);
        }

        if($this->fch_creacion){
            
            $this->fch_creacion = Utils::changeFormatDateInputShort($this->fch_creacion);
            $query->andFilterWhere([
                ">=","date_format(fch_creacion, '%Y-%m-%d')",new Expression("'".$this->fch_creacion."' - INTERVAL 7 DAY")
            
            ]);
        }

          
        if($this->startDate && $this->endDate){
            $this->startDate = Utils::changeFormatDateInputShort($this->startDate);
            $this->endDate = Utils::changeFormatDateInputShort($this->endDate);
            $query->andFilterWhere([
                "between", "date_format(fch_creacion, '%Y-%m-%d')",$this->startDate, $this->endDate
            
            ]);

        }

        if($this->startDateCita && $this->endDateCita ){
            $this->startDateCita  = Utils::changeFormatDateInputShort($this->startDateCita );
            $this->endDateCita  = Utils::changeFormatDateInputShort($this->endDateCita );
            $query->andFilterWhere([
                "between", "date_format(fch_cita, '%Y-%m-%d')",$this->startDateCita , $this->endDateCita 
            
            ]);

        }


        
        return $dataProvider;
    }
}
