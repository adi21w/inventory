<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name
 * @property int|null $parent
 * @property string|null $route
 * @property int|null $order
 * @property int|null $level
 * @property resource|null $data
 * @property string|null $icon
 * @property string|null $eparent
 * @property string|null $cabang
 */
class Menu extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const EPARENT_YA = 'Ya';
    const EPARENT_TIDAK = 'Tidak';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent', 'route', 'order', 'level', 'data', 'icon'], 'default', 'value' => null],
            [['eparent'], 'default', 'value' => 'Tidak'],
            [['name'], 'required'],
            [['parent', 'order', 'level'], 'integer'],
            [['data', 'eparent'], 'string'],
            [['name'], 'string', 'max' => 128],
            [['route', 'cabang'], 'string', 'max' => 255],
            [['icon'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent' => 'Parent',
            'route' => 'Route',
            'order' => 'Order',
            'level' => 'Level',
            'data' => 'Data',
            'icon' => 'Icon',
            'eparent' => 'Eparent'
        ];
    }
}
