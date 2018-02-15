<?php

namespace app\models;

use Yii;
use app\modules\ModUsuarios\models\EntUsuarios;

/**
 * This is the model class for table "ent_tarjetas".
 *
 * @property int $id_usuario
 * @property string $txt_tarjeta
 *
 * @property ModUsuariosEntUsuarios $usuario
 */
class EntTarjetas extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_tarjetas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuario', 'txt_tarjeta'], 'required'],
            [['id_usuario'], 'integer'],
            [['txt_tarjeta'], 'string', 'max' => 200],
            [['id_usuario', 'txt_tarjeta'], 'unique', 'targetAttribute' => ['id_usuario', 'txt_tarjeta']],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => EntUsuarios::className(), 'targetAttribute' => ['id_usuario' => 'id_usuario']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'txt_tarjeta' => 'Txt Tarjeta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(EntUsuarios::className(), ['id_usuario' => 'id_usuario']);
    }
}
