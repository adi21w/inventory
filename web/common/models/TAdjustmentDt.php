<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "t_adj_dt".
 *
 * @property int $iId
 * @property string|null $vNoAdj
 * @property int|null $iProdukId
 * @property string|null $vNoBatch
 * @property int|null $iQty
 * @property float|null $dPrice
 * @property float|null $dTotal
 * @property string|null $eDeleted
 * @property int|null $iCreatedid
 * @property int|null $iUpdatedid
 * @property string|null $tCreated
 * @property string|null $tUpdated
 */
class TAdjustmentDt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $stock;
    public static function tableName()
    {
        return 't_adjustment_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iQty2', 'dHarga', 'dHargasatuan', 'dTotal'], 'default', 'value' => 0],
            [['iProdukId', 'iQty', 'iKemasanId'], 'integer'],
            [['tCreated', 'tUpdated', 'stock', 'dExpired', 'iId', 'vAdjNo', 'iCreatedId', 'iUpdatedId'], 'safe'],
            [['vBatch'], 'string', 'max' => 50],
            [['iProdukId', 'iKemasanId', 'vBatch', 'iQty'], 'required'],
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
            'iProdukId' => 'Product',
            'iKemasanId' => 'Packaging',
            'vBatch' => 'No Batch',
            'dExpired' => 'Expired Date',
            'iQty' => 'Qty',
            'iQty2' => 'Qty Kecil',
            'dHarga' => 'Harga',
            'dHargasatuan' => 'Harga Satuan',
            'dTotal' => 'Total',
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

    public function getHeader()
    {
        return $this->hasOne(TAdjustmentHd::className(), ['vAdjNo' => 'vAdjNo']);
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['iId' => 'iProdukId']);
    }

    public function getPack()
    {
        return $this->hasOne(Packs::className(), ['iId' => 'iKemasanId']);
    }
}
