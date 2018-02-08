<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ent_subscripciones".
 *
 * @property string $id_usuario
 * @property string $txt_subscipcion_open_pay
 */
class EntSubscripciones extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ent_subscripciones';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_usuario', 'txt_subscipcion_open_pay'], 'required'],
            [['id_usuario'], 'integer'],
            [['txt_subscipcion_open_pay'], 'string', 'max' => 200],
            [['id_usuario', 'txt_subscipcion_open_pay'], 'unique', 'targetAttribute' => ['id_usuario', 'txt_subscipcion_open_pay']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'txt_subscipcion_open_pay' => 'Txt Subscipcion Open Pay',
        ];
    }
}
