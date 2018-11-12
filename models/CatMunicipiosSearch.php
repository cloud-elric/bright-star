<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CatMunicipios;

/**
 * CatMunicipiosSearch represents the model behind the search form about `app\models\CatMunicipios`.
 */
class CatMunicipiosSearch extends CatMunicipios
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_municipio', 'id_tipo', 'id_area', 'b_lunes', 'b_martes', 'b_miercoles', 'b_jueves', 'b_viernes', 'b_sabado', 'b_domingo'], 'integer'],
            [['txt_nombre'], 'safe'],
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
        $query = CatMunicipios::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_municipio' => $this->id_municipio,
            'id_tipo' => $this->id_tipo,
            'id_area' => $this->id_area,
            'b_lunes' => $this->b_lunes,
            'b_martes' => $this->b_martes,
            'b_miercoles' => $this->b_miercoles,
            'b_jueves' => $this->b_jueves,
            'b_viernes' => $this->b_viernes,
            'b_sabado' => $this->b_sabado,
            'b_domingo' => $this->b_domingo,
        ]);

        $query->andFilterWhere(['like', 'txt_nombre', $this->txt_nombre]);

        return $dataProvider;
    }
}
