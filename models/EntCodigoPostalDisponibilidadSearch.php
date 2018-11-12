<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EntCodigoPostalDisponibilidad;

/**
 * EntCodigoPostalDisponibilidadSearch represents the model behind the search form about `app\models\EntCodigoPostalDisponibilidad`.
 */
class EntCodigoPostalDisponibilidadSearch extends EntCodigoPostalDisponibilidad
{
    public $num_dia;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_disponiblidad','num_dia', 'id_municipio', 'id_area', 'b_habilitado'], 'integer'],
            [['txt_codigo_postal'], 'safe'],
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
    public function search($params)
    {
        $query = EntCodigoPostalDisponibilidad::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['id_municipio'] = [    
            'asc' => ['cat_municipios.txt_nombre' => SORT_ASC],
            'desc' => ['cat_municipios.txt_nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['id_area'] = [    
            'asc' => ['cat_areas.txt_nombre' => SORT_ASC],
            'desc' => ['cat_areas.txt_nombre' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['num_dia'] = [    
            'asc' => ['ent_horarios_areas.id_dia' => SORT_ASC],
            'desc' => ['ent_horarios_areas.id_dia' => SORT_DESC],
        ];
        $query->joinWith("idMunicipio");
        $query->joinWith("idArea");
        $query->joinWith("idHorario");
        $query->joinWith("txtCodigoPostal");

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_disponiblidad' => $this->id_disponiblidad,
            'ent_codigo_postal_disponibilidad.id_municipio' => $this->id_municipio,
            'ent_codigo_postal_disponibilidad.id_area' => $this->id_area,
            'ent_codigo_postal_disponibilidad.id_horario' => $this->id_horario,
            'ent_horarios_areas.id_dia' => $this->num_dia,
            'ent_codigo_postal_disponibilidad.b_habilitado' => $this->b_habilitado,
        ]);

        $query->andFilterWhere(['like', 'ent_codigo_postal_disponibilidad.txt_codigo_postal', $this->txt_codigo_postal]);

        return $dataProvider;
    }
}
