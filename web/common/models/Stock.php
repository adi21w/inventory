<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "stock".
 *
 * @property int $iId id stock
 * @property int|null $iProdukId id barang
 * @property int|null $iCreatedid id yang membuat
 * @property string|null $tCreated timestamp dibuat
 * @property int|null $iUpdatedid id uang mengupdate
 * @property string|null $tUpdated timestamp diupdate
 * @property int|null $iQty jumlah stock
 */
class Stock extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iQty'], 'default', 'value' => 0],
            [['iProdukId', 'iGudangId'], 'integer'],
            [['iProdukId', 'iGudangId'], 'required'],
            [['iCreatedId', 'tCreated', 'iUpdatedId', 'tUpdated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iId' => 'ID',
            'iProdukId' => 'Product',
            'iGudangId' => 'Warehouse',
            'iQty' => 'Qty',
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
                'product' => function ($model) {
                    return $model->product ? $model->product->vNama : null;
                },
                'warehouse' => function ($model) {
                    return $model->warehouse ? $model->warehouse->vNama : null;
                },
                'qty' => 'iQty',
                'detail' => function ($model) {
                    return $model->details ?? [];
                },
                'time_created' => 'tCreated',
                'time_updated' => 'tUpdated',
                'last_updated_by' => function ($model) {
                    return $model->updated ? $model->updated->name : null;
                },
            ];
        }

        return parent::fields();
    }

    public function getDetails()
    {
        return $this->hasMany(Stockdetail::className(), ['iProdukId' => 'iProdukId'])->onCondition(['iGudangId' => $this->iGudangId]);
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['iId' => 'iProdukId']);
    }

    public function getWarehouse()
    {
        return $this->hasOne(Warehouses::className(), ['iId' => 'iGudangId']);
    }

    public function getUpdated()
    {
        return $this->hasOne(Users::className(), ['id' => 'iUpdatedId']);
    }

    public function getExpands()
    {
        $model = Stockdetail::find()
            ->alias('sd')
            ->select(['sd.vBatch', 'sd.iQty', 'sd.iQty2', 'sd.iQtysum', 'sd.dExpired'])
            ->where(['sd.iProdukId' => $this->iProdukId, 'iGudangId' => $this->iGudangId])
            ->asArray()
            ->all();

        return $model;
    }
}
