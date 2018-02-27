<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_datos_facturacion".
 *
 * @property int $id_dato_facturacion
 * @property int $id_usuairo
 * @property string $txt_rfc
 * @property string $txt_nombre
 * @property int $b_habilitado
 */
class EntDatosFacturacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_datos_facturacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuairo', 'b_habilitado'], 'integer'],
            [['txt_rfc'], 'string', 'max' => 13],
            [['txt_nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_dato_facturacion' => 'Id Dato Facturacion',
            'id_usuairo' => 'Id Usuairo',
            'txt_rfc' => 'RFC',
            'txt_nombre' => 'Nombre compelto',
            'b_habilitado' => 'B Habilitado',
        ];
    }
}
