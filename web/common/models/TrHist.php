<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class TrHist extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tr_hist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iTr_Qtybef', 'iTr_Qty', 'iTr_Qtyend'], 'default', 'value' => 0],
            [['eTr_Type', 'vTr_Batch'], 'string'],
            [['iTr_ProdukId', 'iTr_Qtybef', 'iTr_Qty', 'iTr_Qtyend', 'iTr_GudangId', 'iTr_Kemasan'], 'integer'],
            [['dTr_Date', 'tCreated', 'tUpdated', 'iCreatedId', 'iUpdatedId', 'vTr_Number'], 'safe'],
            [['dHarga', 'dTotal'], 'number'],
            [['vTr_Number'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iId' => 'ID',
            'vTr_Number' => 'No Transaction',
            'eTr_Type' => 'Type',
            'iTr_ProdukId' => 'Product',
            'vTr_Batch' => 'Batch',
            'iTr_GudangId' => 'Warehouse',
            'iTr_Kemasan' => 'Pack',
            'iTr_Qtybef' => 'Qty Bef',
            'iTr_Qty' => 'Qty',
            'iTr_Qtyend' => 'Qty End',
            'dTr_Date' => 'Tanggal',
            'dHarga' => 'Price',
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

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['iId' => 'iTr_ProdukId']);
    }

    public function getWarehouse()
    {
        return $this->hasOne(Warehouses::className(), ['iId' => 'iTr_GudangId']);
    }

    public function getKemasan()
    {
        return $this->hasOne(Packs::className(), ['iId' => 'iTr_Kemasan']);
    }
}
