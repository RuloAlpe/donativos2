<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_tipos_pago".
 *
 * @property string $id_tipo_pago
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property integer $b_habilitado
 *
 * @property EntOrdenesCompras[] $entOrdenesCompras
 * @property EntPagosRecibidos[] $entPagosRecibidos
 */
class CatTiposPago extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cat_tipos_pago';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_nombre', 'txt_descripcion'], 'required'],
            [['b_habilitado'], 'integer'],
            [['txt_nombre'], 'string', 'max' => 100],
            [['txt_descripcion'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_pago' => 'Id Tipo Pago',
            'txt_nombre' => 'Txt Nombre',
            'txt_descripcion' => 'Txt Descripcion',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntOrdenesCompras()
    {
        return $this->hasMany(EntOrdenesCompras::className(), ['id_tipo_pago' => 'id_tipo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntPagosRecibidos()
    {
        return $this->hasMany(EntPagosRecibidos::className(), ['id_tipo_pago' => 'id_tipo_pago']);
    }
}
