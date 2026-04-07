<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "h_product_price".
 *
 * @property int $iId
 * @property int|null $iProdukId
 * @property int|null $iKategoriId
 * @property float|null $oldPrice
 * @property float|null $oldEkat
 * @property float|null $newPrice
 * @property float|null $newEkat
 * @property string|null $tUpdated
 * @property int|null $iUpdatedid
 * @property string|null $vFrom
 */
class HPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'h_product_price';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['iProdukId', 'oldPrice', 'newPrice', 'iUpdatedid'], 'default', 'value' => null],
            [['iProdukId', 'iUpdatedid'], 'integer'],
            [['oldPrice', 'newPrice'], 'number'],
            [['tUpdated'], 'safe'],
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
            'oldPrice' => 'Old Price',
            'newPrice' => 'New Price',
            'tUpdated' => 'Time Updated',
            'iUpdatedid' => 'Updated By',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Products::className(), ['iId' => 'iProdukId']);
    }

    public function getUpdated()
    {
        return $this->hasOne(Users::className(), ['id' => 'iUpdatedid']);
    }
}
