<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "m_products".
 */
class Products extends \yii\db\ActiveRecord
{
    public $excel;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'm_products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vImage', 'iKemasanKecilId', 'iIsiKemasanKecil', 'vDeskripsi'], 'default', 'value' => null],
            [['iStatus'], 'default', 'value' => 1],
            [['eDeleted'], 'default', 'value' => 'Tidak'],
            [['vNama', 'iKemasanBesarId', 'iIsiKemasanBesar', 'dPrice', 'dMargin', 'vSlug'], 'required'],
            [['iKemasanBesarId', 'iIsiKemasanBesar', 'iRak'], 'integer'],
            [['tCreated', 'tUpdated', 'iCreatedid', 'iUpdatedid', 'eDeleted', 'iId'], 'safe'],
            [['vNama'], 'string', 'max' => 100],
            [['vSlug'], 'string', 'max' => 150],
            [['vSlug'], 'unique'],
            [['vImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, avif, webp', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => 'Ukuran file terlalu besar. Maksimal 2MB.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'iId' => 'ID',
            'vNama' => 'Nama Produk',
            'eDeleted' => 'Deleted?',
            'iStatus' => 'Status',
            'iRak' => 'Rak',
            'tCreated' => 'Time Created',
            'tUpdated' => 'Time Updated',
            'iCreatedId' => 'Created By',
            'iUpdatedId' => 'Updated By',
            'iKemasanBesarId' => 'Kemasan Besar',
            'iKemasanKecilId' => 'Kemasan Kecil',
            'iIsiKemasanBesar' => 'Isi Kemasan Besar',
            'iIsiKemasanKecil' => 'Isi Kemasan Kecil',
            'dPrice' => 'Harga Regular',
            'dMargin' => 'Margin',
            'vImage' => 'Image',
            'vSlug' => 'Slug',
            'vDeskripsi' => 'Deskripsi',
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
                'id' => 'iId',
                'product' => 'vNama',
                'slug' => 'vSlug',
                'status' => 'iStatus',
                'rak' => function ($model) {
                    return [
                        'id' => $model->rak ? $model->rak->iId : null,
                        'nama' => $model->rak ? $model->rak->vNama : null,
                    ];
                },
                'kemasan_besar' => function ($model) {
                    return [
                        'id' => $model->kemasan ? $model->kemasan->iId : null,
                        'nama' => $model->kemasan ? $model->kemasan->vNama : null,
                    ];
                },
                'kemasan_kecil' => function ($model) {
                    return [
                        'id' => $model->kemasankecil ? $model->kemasankecil->iId : null,
                        'nama' => $model->kemasankecil ? $model->kemasankecil->vNama : null,
                    ];
                },
                'isi_besar' => 'iIsiKemasanBesar',
                'isi_kecil' => 'iIsiKemasanKecil',
                'price' => 'dPrice',
                'margin' => 'dMargin',
                'image' => function ($model) {
                    return $model->vImage ? Yii::$app->params['WebUrl'] . 'uploads/products/' . $model->vImage : null;
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

    public function getKemasan()
    {
        return $this->hasOne(Packs::className(), ['iId' => 'iKemasanBesarId']);
    }

    public function getKemasankecil()
    {
        return $this->hasOne(Packs::className(), ['iId' => 'iKemasanKecilId']);
    }

    public function getRak()
    {
        return $this->hasOne(Rack::className(), ['iId' => 'iRak']);
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
