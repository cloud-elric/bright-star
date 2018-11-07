<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_codigo_postal_disponibilidad".
 *
 * @property string $id_disponiblidad
 * @property string $txt_codigo_postal
 * @property string $num_dia
 * @property string $txt_hora_inicial
 * @property string $txt_hora_final
 * @property string $b_habilitado
 *
 * @property CatCodigosPostales $txtCodigoPostal
 */
class EntCodigoPostalDisponibilidad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_codigo_postal_disponibilidad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['num_dia', 'txt_hora_inicial', 'txt_hora_final'], 'required'],
            [['num_dia', 'b_habilitado'], 'integer'],
            [['txt_codigo_postal'], 'string', 'max' => 5],
            [['txt_hora_inicial', 'txt_hora_final'], 'string', 'max' => 100],
            [['txt_codigo_postal'], 'exist', 'skipOnError' => true, 'targetClass' => CatCodigosPostales::className(), 'targetAttribute' => ['txt_codigo_postal' => 'txt_codigo_postal']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_disponiblidad' => 'Id Disponiblidad',
            'txt_codigo_postal' => 'Codigo Postal',
            'num_dia' => 'Numero del Dia',
            'txt_hora_inicial' => 'Hora Inicial',
            'txt_hora_final' => 'Hora Final',
            'b_habilitado' => 'Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTxtCodigoPostal()
    {
        return $this->hasOne(CatCodigosPostales::className(), ['txt_codigo_postal' => 'txt_codigo_postal']);
    }
}
