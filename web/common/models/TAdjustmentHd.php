<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "t_adj_hd".
 */
class TAdjustmentHd extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 't_adjustment_header';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dConfirm'], 'default', 'value' => null],
            [['dTotal'], 'default', 'value' => 0],
            [['eDeleted', 'eConfirm'], 'default', 'value' => 'Tidak'],
            [['iGudangId', 'iCreatedId', 'iUpdatedId'], 'integer'],
            [['eDeleted', 'tKeterangan', 'eKategori', 'eType', 'eConfirm'], 'string'],
            [['dTotal'], 'number'],
            [['tCreated', 'tUpdated'], 'safe'],
            [['vAdjNo'], 'string', 'max' => 20],
            [['iGudangId'], 'required'],
            [['vAdjNo'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iId' => 'ID',
            'vAdjNo' => 'No Adjusment',
            'iGudangId' => 'Warehouse',
            'eKategori' => 'Category',
            'eType' => 'Type',
            'dConfirm' => 'Tanggal',
            'eConfirm' => 'Confirmed?',
            'dTotal' => 'Total',
            'tKeterangan' => 'Description',
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
            [
                'class' => 'mdm\autonumber\Behavior',
                'attribute' => 'vAdjNo',
                'value' => function ($model) {
                    $prefix = ($this->eType == 'PLUS') ? 'P' : 'M';
                    return 'AJX-' . $prefix . '-' . date('Ymd') . '?';
                },
                'digit' => 3
            ]
        ];
    }

    public function getWarehouse()
    {
        return $this->hasOne(Warehouses::className(), ['iId' => 'iGudangId']);
    }

    public function getDetails()
    {
        return $this->hasMany(TAdjustmentDt::className(), ['vAdjNo' => 'vAdjNo']);
    }

    public function getCreated()
    {
        return $this->hasOne(Users::className(), ['id' => 'iCreatedId']);
    }

    public function getUpdated()
    {
        return $this->hasOne(Users::className(), ['id' => 'iUpdatedId']);
    }

    public function getExpands()
    {
        $model = TAdjustmentDt::find()
            ->alias('tap')
            ->select(['p.vNama as produk', 'tap.vBatch', 'tap.iQty', 'tap.dExpired', 'tap.dHarga', 'tap.dTotal'])
            ->joinWith(['product p'])
            ->where(['tap.vAdjNo' => $this->vAdjNo])
            ->asArray()
            ->all();

        $except = ['product'];

        $cleanData = array_map(function ($item) use ($except) {
            return array_filter(array_diff_key($item, array_flip($except)), fn($v) => !is_null($v));
        }, $model);

        return $cleanData;
    }
}
