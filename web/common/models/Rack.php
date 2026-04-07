<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "m_rack".
 *
 * @property int $iId
 * @property string|null $vNama
 * @property string|null $tKeterangan
 * @property string $eDeleted
 * @property string $tCreated
 * @property string $tUpdated
 * @property int|null $iCreatedId
 * @property int|null $iUpdatedId
 */
class Rack extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_rack';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iCreatedId', 'iUpdatedId'], 'default', 'value' => null],
            [['eDeleted'], 'default', 'value' => 'Tidak'],
            [['eDeleted'], 'string'],
            [['tCreated', 'tUpdated'], 'safe'],
            [['iCreatedId', 'iUpdatedId'], 'integer'],
            [['vNama'], 'string', 'max' => 100],
            [['vNama'], 'required'],
            [['vNama'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iId' => 'ID',
            'vNama' => 'Packs',
            'eDeleted' => 'Deleted?',
            'tCreated' => 'Time Created',
            'tUpdated' => 'Time Updated',
            'iCreatedId' => 'Created By',
            'iUpdatedId' => 'Updated By',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'iCreatedId',
                'updatedByAttribute' => 'iUpdatedId',
                'value' => function () {
                    return Yii::$app->user->id;
                },
                'defaultValue' => 3,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'tCreated',
                'updatedAtAttribute' => 'tUpdated',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    public function fields()
    {
        if (Yii::$app->id === 'app-api') {
            return [
                'id' => 'iId',
                'rack' => 'vNama',
                'time_created' => 'tCreated',
                'time_updated' => 'tUpdated',
                'last_updated_by' => function ($model) {
                    return $model->updated ? $model->updated->name : null;
                },
            ];
        }

        return parent::fields();
    }

    public function getCreated()
    {
        return $this->hasOne(Users::className(), ['id' => 'iCreatedId']);
    }

    public function getUpdated()
    {
        return $this->hasOne(Users::className(), ['id' => 'iUpdatedId']);
    }
}
