<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "stockdet".
 *
 * @property int $iId
 * @property int|null $iProdukId
 * @property string|null $vBatch
 * @property int|null $iQty
 * @property int|null $iCreatedid
 * @property int|null $iUpdatedid
 * @property string|null $tCreated
 * @property string|null $tUpdated
 */
class Stockdetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dExpired'], 'default', 'value' => null],
            [['iQty', 'iQty2', 'iQtysum'], 'default', 'value' => 0],
            [['iProdukId', 'iGudangId'], 'integer'],
            [['iProdukId', 'iGudangId', 'vBatch'], 'required'],
            [['tCreated', 'tUpdated', 'iCreatedId', 'iUpdatedId'], 'safe'],
            [['vBatch'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iId' => 'ID',
            'iProdukId' => 'Produk',
            'iGudangId' => 'Gudang',
            'vBatch' => 'Batch',
            'dExpired' => 'Expired',
            'iQty' => 'Qty In',
            'iQty2' => 'Qty Out',
            'iQtysum' => 'Qty End',
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
            ]
        ];
    }

    public function fields()
    {
        if (Yii::$app->id === 'app-api') {
            return [
                'qty_in' => 'iQty',
                'qty_out' => 'iQty2',
                'qty_total' => 'iQtysum',
                'batch' => 'vBatch',
                'expired' => 'dExpired'
            ];
        }

        return parent::fields();
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['iId' => 'iProdukId']);
    }

    public function getWarehouse()
    {
        return $this->hasOne(Warehouses::className(), ['iId' => 'iGudangId']);
    }
}
