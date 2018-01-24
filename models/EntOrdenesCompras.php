<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;
/**
 * This is the model class for table "ent_ordenes_compras".
 *
 * @property string $id_orden_compra
 * @property string $txt_order_number
 * @property string $txt_order_open_pay
 * @property string $txt_description
 * @property string $id_usuario
 * @property string $id_tipo_pago
 * @property string $fch_creacion
 * @property string $b_pagado
 * @property double $num_total
 * @property string $b_habilitado
 *
 * @property EntBoletos[] $entBoletos
 * @property CatTiposPago $idTipoPago
 * @property ModUsuariosEntUsuarios $idUsuario
 * @property EntPagosRecibidos[] $entPagosRecibidos
 */
class EntOrdenesCompras extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_ordenes_compras';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['txt_order_number', 'txt_description', 'id_usuario', 'num_total'], 'required'],
            [['id_usuario', 'id_tipo_pago', 'b_pagado', 'b_habilitado'], 'integer'],
            [['fch_creacion'], 'safe'],
            [['num_total'], 'number', 'message'=>'Debe ingresar solo números'],
            ['num_total', 'compare', 'compareValue' => 0, 'operator' => '>', 'type' => 'number', 'message'=>'Debe ingresar una cantidad mayor a 0'],
            [['txt_order_number', 'txt_order_open_pay'], 'string', 'max' => 50],
            [['txt_description'], 'string', 'max' => 500],
            [['id_tipo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => CatTiposPago::className(), 'targetAttribute' => ['id_tipo_pago' => 'id_tipo_pago']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_orden_compra' => 'Id Orden Compra',
            'txt_order_number' => 'Txt Order Number',
            'txt_order_open_pay' => 'Txt Order Open Pay',
            'txt_description' => 'Txt Description',
            'id_usuario' => 'Id Usuario',
            'id_tipo_pago' => 'Id Tipo Pago',
            'fch_creacion' => 'Fch Creacion',
            'b_pagado' => 'B Pagado',
            'num_total' => 'Cantidad a donar',
            'b_habilitado' => 'B Habilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntBoletos()
    {
        return $this->hasMany(EntBoletos::className(), ['id_orden_compra' => 'id_orden_compra']);
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
    public function getIdUsuario()
    {
        return $this->hasOne(ModUsuariosEntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntPagosRecibidos()
    {
        return $this->hasMany(EntPagosRecibidos::className(), ['id_orden_compra' => 'id_orden_compra']);
    }
}
