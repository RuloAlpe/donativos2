<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_tarjetas".
 *
 * @property string $id_usuario
 * @property string $txt_tarjeta
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
}
