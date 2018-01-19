<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_colonias".
 *
 * @property string $id_colonia
 * @property string $id_municipio
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property string $txt_codigo_postal
 * @property string $b_habilitado
 *
 * @property CatCodigosPostales $txtCodigoPostal
 */
class CatColonias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_colonias';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_municipio', 'txt_nombre'], 'required'],
            [['id_municipio', 'b_habilitado'], 'integer'],
            [['txt_nombre'], 'string', 'max' => 110],
            [['txt_descripcion'], 'string', 'max' => 2500],
            [['txt_codigo_postal'], 'string', 'max' => 5],
            [['txt_codigo_postal'], 'exist', 'skipOnError' => true, 'targetClass' => CatCodigosPostales::className(), 'targetAttribute' => ['txt_codigo_postal' => 'txt_codigo_postal']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_colonia' => 'Id Colonia',
            'id_municipio' => 'Id Municipio',
            'txt_nombre' => 'Txt Nombre',
            'txt_descripcion' => 'Txt Descripcion',
            'txt_codigo_postal' => 'Txt Codigo Postal',
            'b_habilitado' => 'B Habilitado',
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
