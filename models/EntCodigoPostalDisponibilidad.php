<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_codigo_postal_disponibilidad".
 *
 * @property integer $id_disponiblidad
 * @property integer $id_municipio
 * @property integer $id_area
 * @property integer $id_horario
 * @property string $txt_codigo_postal
 * @property integer $b_habilitado
 *
 * @property CatMunicipios $idMunicipio
 * @property CatCodigosPostales $txtCodigoPostal
 * @property CatAreas $idArea
 * @property EntHorariosAreas $idHorario
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
            [['id_municipio', 'id_area', 'id_horario', 'b_habilitado'], 'integer'],
            [['id_area', 'id_horario'], 'required'],
            [['txt_codigo_postal'], 'string', 'max' => 5],
            [['id_municipio'], 'exist', 'skipOnError' => true, 'targetClass' => CatMunicipios::className(), 'targetAttribute' => ['id_municipio' => 'id_municipio']],
            [['txt_codigo_postal'], 'exist', 'skipOnError' => true, 'targetClass' => CatCodigosPostales::className(), 'targetAttribute' => ['txt_codigo_postal' => 'txt_codigo_postal']],
            [['id_area'], 'exist', 'skipOnError' => true, 'targetClass' => CatAreas::className(), 'targetAttribute' => ['id_area' => 'id_area']],
            [['id_horario'], 'exist', 'skipOnError' => true, 'targetClass' => EntHorariosAreas::className(), 'targetAttribute' => ['id_horario' => 'id_horario_area']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_disponiblidad' => 'Id Disponiblidad',
            'id_municipio' => 'Municipio',
            'id_area' => 'Area',
            'id_horario' => 'Horario',
            'txt_codigo_postal' => 'Código Postal',
            'b_habilitado' => 'Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMunicipio()
    {
        return $this->hasOne(CatMunicipios::className(), ['id_municipio' => 'id_municipio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTxtCodigoPostal()
    {
        return $this->hasOne(CatCodigosPostales::className(), ['txt_codigo_postal' => 'txt_codigo_postal']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdArea()
    {
        return $this->hasOne(CatAreas::className(), ['id_area' => 'id_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdHorario()
    {
        return $this->hasOne(EntHorariosAreas::className(), ['id_horario_area' => 'id_horario']);
    }
}
