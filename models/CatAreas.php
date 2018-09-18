<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_areas".
 *
 * @property string $id_area
 * @property string $id_tipo_entrega
 * @property string $txt_token
 * @property string $txt_nombre
 * @property string $txt_dias_servicio
 * @property string $txt_descripcion
 * @property string $b_habilitado
 *
 * @property CatTiposEntrega $idTipoEntrega
 * @property EntCitas[] $entCitas
 * @property EntHorariosAreas[] $entHorariosAreas
 */
class CatAreas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_areas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tipo_entrega', 'txt_token', 'txt_nombre', 'txt_dias_servicio'], 'required'],
            [['id_tipo_entrega', 'b_habilitado'], 'integer'],
            [['txt_token'], 'string', 'max' => 60],
            [['txt_nombre'], 'string', 'max' => 100],
            [['txt_dias_servicio'], 'string', 'max' => 30],
            [['txt_descripcion'], 'string', 'max' => 500],
            [['txt_token'], 'unique'],
            [['id_tipo_entrega'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposEntrega::className(), 'targetAttribute' => ['id_tipo_entrega' => 'id_tipo_entrega']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_area' => 'Id Area',
            'id_tipo_entrega' => 'Id Tipo Entrega',
            'txt_token' => 'Txt Token',
            'txt_nombre' => 'Txt Nombre',
            'txt_dias_servicio' => 'Txt Dias Servicio',
            'txt_descripcion' => 'Txt Descripcion',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoEntrega()
    {
        return $this->hasOne(CatTiposEntrega::className(), ['id_tipo_entrega' => 'id_tipo_entrega']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntCitas()
    {
        return $this->hasMany(EntCitas::className(), ['id_area' => 'id_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntHorariosAreas()
    {
        return $this->hasMany(EntHorariosAreas::className(), ['id_area' => 'id_area'])->orderBy("id_dia");
    }
}
