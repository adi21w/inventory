<?php

namespace common\services;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\Stock;
use common\models\Stockdetail;
use common\models\StockSearch;
use yii\helpers\ArrayHelper;
// use common\helper\Helper;

class StockService
{
    private $_modul = "Stock";

    protected function getAttributeMap()
    {
        return [
            'product' => 'iProdukId',
            'warehouse' => 'iGudangId',
        ];
    }

    public function createModel()
    {
        return new Stock();
    }

    public function createSearch()
    {
        return new StockSearch();
    }

    public function createModelDetail()
    {
        return new Stockdetail();
    }

    public function detailData($id = false)
    {
        if (!$id) {
            return [
                'status' => false,
                'error' => 'Paramater id tidak ada!'
            ];
        }

        $model = $this->findModel($id);

        if (!$model) {
            return [
                'status' => false,
                'error' => "Data {$this->_modul} tidak ditemukan!"
            ];
        } else {
            return [
                'status' => true,
                'data' => $model
            ];
        }
    }

    public static function saveData($req = [], $adj = 1)
    {
        $isValid = true;
        $requiredKeys = ['produk', 'gudang', 'batch', 'qty', 'expired'];

        if (!array_diff($requiredKeys, array_keys($req))) {
            $modelStock = Stock::findOne(['iProdukId' => $req['produk'], 'iGudangId' => $req['gudang']]);
            if (!$modelStock) {
                $modelStock = new Stock();
                $modelStock->iProdukId = $req['produk'];
                $modelStock->iGudangId = $req['gudang'];
                $isValid = $modelStock->save(false);
            }

            if ($isValid) {
                $modelStockDet = Stockdetail::findOne(['iProdukId' => $req['produk'], 'vBatch' => $req['batch'], 'iGudangId' => $req['gudang']]);
                if (!$modelStockDet) {
                    $modelStockDet = new Stockdetail();
                    $modelStockDet->iProdukId = $req['produk'];
                    $modelStockDet->iGudangId = $req['gudang'];
                    $modelStockDet->vBatch = $req['batch'];
                    $modelStockDet->dExpired = $req['expired'];
                    $modelStockDet->iQty = $req['qty'];
                    $modelStockDet->iQty2 = 0;
                } else {
                    if ($adj == 1) {
                        $modelStockDet->iQty += $req['qty'];
                    } else {
                        $modelStockDet->iQty2 += $req['qty'];
                    }
                }

                if ($isValid = $modelStockDet->save(false)) {
                    return ['status' => true];
                } else {
                    return [
                        'status' => false,
                        'error' => implode('<br>', $modelStockDet->getFirstErrors())
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'error' => implode('<br>', $modelStock->getFirstErrors())
                ];
            }
        } else {
            return [
                'status' => false,
                'error' => 'Tidak ada paramater produk / gudang!'
            ];
        }
    }



    public function findModel($iId)
    {
        if (($model = Stock::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelDetail($iId)
    {
        if (($model = Stockdetail::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function checkStock()
    {
        $query = Stockdetail::find()->select(['SUM(iQty) as qtyin', 'SUM(iQty2) as qtyout'])->where(['iGudangId' => 1])->groupBy('iGudangId')->asArray()->one();
        return $query;
    }

    public function parseDataView($data)
    {
        $result = [];
        $result[] = ['label' => 'Nama', 'value' => $data->vNama];
        $result[] = ['label' => 'Created By', 'value' => $data->created ? $data->created->name : '-'];
        $result[] = ['label' => 'Updated By', 'value' => $data->updated ? $data->updated->name : '-'];
        $result[] = ['label' => 'Time Created', 'value' => $data->tCreated];
        $result[] = ['label' => 'Time Updated', 'value' => $data->tUpdated];

        return $result;
    }

    public function templateDataView($id, $gudang, $def = 0)
    {
        $result = [];
        $query = Stockdetail::find()->where(['iProdukId' => $id, 'iGudangId' => $gudang])->andWhere(['>', 'iQtysum', 0])->asArray()->all();
        if ($def == 0) {
            $result = array_map(fn($n) => ['batch' => $n['vBatch'], 'expired' => $n['dExpired'], 'qty' => $n['iQtysum']], $query);
        } else if ($def == 1) {
            $grouped = ArrayHelper::index($query, null, 'iProdukId');
            $result = array_values($grouped);
        }

        return $result;
    }

    private function _getNameClass()
    {
        return \yii\helpers\StringHelper::basename(get_class($this->createModel()));
    }

    private function _getNameClassDetail()
    {
        return \yii\helpers\StringHelper::basename(get_class($this->createModelDetail()));
    }

    private function _getSearchClass()
    {
        return \yii\helpers\StringHelper::basename(get_class($this->createSearch()));
    }

    public function mapQueryParams($params, $search = false)
    {
        $map = $this->getAttributeMap();
        $baseName = ($search) ? $this->_getSearchClass() : $this->_getNameClass();
        $result = [$baseName => []];

        foreach ($params as $key => $value) {
            // Jika ada di map, pakai nama asli. Jika tidak, pakai key aslinya.
            $realKey = $map[$key] ?? $key;
            $result[$baseName][$realKey] = $value;
        }

        return $result;
    }
}
