<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "ent_boletos".
 *
 * @property string $id_boleto
 * @property string $id_orden_compra
 * @property string $id_pago_recibido
 * @property string $id_usuario
 * @property string $txt_codigo
 * @property string $fch_creacion
 *
 * @property EntOrdenesCompras $idOrdenCompra
 * @property EntPagosRecibidos $idPagoRecibido
 * @property EntUsuarios $idUsuario
 */
class EntBoletos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_boletos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_orden_compra', 'id_pago_recibido', 'id_usuario'], 'required'],
            [['id_orden_compra', 'id_pago_recibido', 'id_usuario'], 'integer'],
            [['fch_creacion'], 'safe'],
            [['txt_codigo'], 'string', 'max' => 30],
            [['id_orden_compra'], 'exist', 'skipOnError' => true, 'targetClass' => EntOrdenesCompras::className(), 'targetAttribute' => ['id_orden_compra' => 'id_orden_compra']],
            [['id_pago_recibido'], 'exist', 'skipOnError' => true, 'targetClass' => EntPagosRecibidos::className(), 'targetAttribute' => ['id_pago_recibido' => 'id_pago_recibido']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_boleto' => 'Id Boleto',
            'id_orden_compra' => 'Id Orden Compra',
            'id_pago_recibido' => 'Id Pago Recibido',
            'id_usuario' => 'Id Usuario',
            'txt_codigo' => 'Txt Codigo',
            'fch_creacion' => 'Fch Creacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOrdenCompra()
    {
        return $this->hasOne(EntOrdenesCompras::className(), ['id_orden_compra' => 'id_orden_compra']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPagoRecibido()
    {
        return $this->hasOne(EntPagosRecibidos::className(), ['id_pago_recibido' => 'id_pago_recibido']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsuario()
    {
        return $this->hasOne(ModUsuariosEntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }
}
