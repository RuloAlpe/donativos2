<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;
/**
 * This is the model class for table "ent_pagos_recibidos".
 *
 * @property string $id_pago_recibido
 * @property string $id_usuario
 * @property string $id_tipo_pago
 * @property string $id_orden_compra
 * @property string $txt_transaccion_local
 * @property string $txt_ip
 * @property string $txt_notas
 * @property string $txt_estatus
 * @property string $txt_transaccion
 * @property string $txt_tipo_transaccion
 * @property string $txt_cadena_comprador
 * @property string $txt_cadena_pago
 * @property string $txt_cadena_producto
 * @property string $txt_monto_pago
 * @property string $verify_sign
 * @property string $fch_pago
 *
 * @property EntBoletos[] $entBoletos
 * @property CatTiposPago $idTipoPago
 * @property EntOrdenesCompras $idOrdenCompra
 * @property EntUsuarios $idUsuario
 */
class EntPagosRecibidos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_pagos_recibidos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_tipo_pago', 'txt_transaccion_local', 'txt_notas', 'txt_estatus', 'txt_transaccion'], 'required'],
            [['id_usuario', 'id_tipo_pago', 'id_orden_compra'], 'integer'],
            [['txt_cadena_comprador'], 'string'],
            [['fch_pago'], 'safe'],
            [['txt_transaccion_local', 'txt_ip'], 'string', 'max' => 32],
            [['txt_notas', 'txt_estatus', 'txt_transaccion', 'txt_tipo_transaccion', 'verify_sign'], 'string', 'max' => 100],
            [['txt_cadena_pago'], 'string', 'max' => 2000],
            [['txt_cadena_producto'], 'string', 'max' => 1000],
            [['id_tipo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposPago::className(), 'targetAttribute' => ['id_tipo_pago' => 'id_tipo_pago']],
            [['id_orden_compra'], 'exist', 'skipOnError' => true, 'targetClass' => EntOrdenesCompras::className(), 'targetAttribute' => ['id_orden_compra' => 'id_orden_compra']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_pago_recibido' => 'Id Pago Recibido',
            'id_usuario' => 'Id Usuario',
            'id_tipo_pago' => 'Id Tipo Pago',
            'id_orden_compra' => 'Id Orden Compra',
            'txt_transaccion_local' => 'Txt Transaccion Local',
            'txt_ip' => 'Txt Ip',
            'txt_notas' => 'Txt Notas',
            'txt_estatus' => 'Txt Estatus',
            'txt_transaccion' => 'Txt Transaccion',
            'txt_tipo_transaccion' => 'Txt Tipo Transaccion',
            'txt_cadena_comprador' => 'Txt Cadena Comprador',
            'txt_cadena_pago' => 'Txt Cadena Pago',
            'txt_cadena_producto' => 'Txt Cadena Producto',
            'txt_monto_pago' => 'Txt Monto Pago',
            'verify_sign' => 'Verify Sign',
            'fch_pago' => 'Fch Pago',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntBoletos()
    {
        return $this->hasMany(EntBoletos::className(), ['id_pago_recibido' => 'id_pago_recibido']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTipoPago()
    {
        return $this->hasOne(CatTiposPago::className(), ['id_tipo_pago' => 'id_tipo_pago']);
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
    public function getIdUsuario()
    {
        return $this->hasOne(ModUsuariosEntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }
}
