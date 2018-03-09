<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "borrar_tabla".
 *
 * @property integer $id_borrar_tabla
 * @property string $txt_nombre
 * @property string $txt_descripcion
 * @property integer $b_habilitado
 */
class BorrarTabla extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'borrar_tabla';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'id_borrar_tabla' => 'Id Borrar Tabla',
            'txt_nombre' => 'Txt Nombre',
            'txt_descripcion' => 'Txt Descripcion',
            'b_habilitado' => 'B Habilitado',
        ];
    }
}
