<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CatAreas;

/**
 * CatAreasSearch represents the model behind the search form about `app\models\CatAreas`.
 */
class CatAreasSearch extends CatAreas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_area', 'id_tipo_entrega', 'b_habilitado'], 'integer'],
            [['txt_token', 'txt_nombre', 'txt_dias_servicio', 'txt_descripcion'], 'safe'],
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
        $query = CatAreas::find();

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
            'id_area' => $this->id_area,
            'id_tipo_entrega' => $this->id_tipo_entrega,
            'b_habilitado' => $this->b_habilitado,
        ]);

        $query->andFilterWhere(['like', 'txt_token', $this->txt_token])
            ->andFilterWhere(['like', 'txt_nombre', $this->txt_nombre])
            ->andFilterWhere(['like', 'txt_dias_servicio', $this->txt_dias_servicio])
            ->andFilterWhere(['like', 'txt_descripcion', $this->txt_descripcion]);

        return $dataProvider;
    }
}
