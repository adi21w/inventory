<?php

namespace common\services;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\TrHist;
use common\models\TrHistSearch;
use common\helper\Helper;

class TrHistService
{
    private $_modul = "Transaction History";

    // protected function getAttributeMap()
    // {
    //     return [
    //         'pack' => 'vNama'
    //     ];
    // }

    public function createModel()
    {
        return new TrHist();
    }

    public function createSearch()
    {
        return new TrHistSearch();
    }

    // public function detailData($id = false)
    // {
    //     if (!$id) {
    //         return [
    //             'status' => false,
    //             'error' => 'Paramater id tidak ada!'
    //         ];
    //     }

    //     $model = $this->findModel($id);

    //     if (!$model) {
    //         return [
    //             'status' => false,
    //             'error' => "Data {$this->_modul} tidak ditemukan!"
    //         ];
    //     } else {
    //         return [
    //             'status' => true,
    //             'data' => $model
    //         ];
    //     }
    // }

    public static function saveData($req = [], $adj = 1)
    {
        $requiredKeys = ['produk', 'gudang', 'batch', 'qty', 'number', 'tipe', 'kemasan', 'date', 'satuan', 'total'];

        if (!array_diff($requiredKeys, array_keys($req))) {
            $modelHist = new TrHist();
            $modelHist->vTr_Number = $req['number'];
            $modelHist->eTr_Type = $req['tipe'];
            $modelHist->iTr_ProdukId = $req['produk'];
            $modelHist->iTr_GudangId = $req['gudang'];
            $modelHist->iTr_Kemasan = $req['kemasan'];
            $modelHist->vTr_Batch = $req['batch'];
            $modelHist->iTr_Qty = ($adj == 1) ? $req['qty'] : $req['qty'] * -1;
            $modelHist->dTr_Date = $req['date'];
            $modelHist->dHarga = ($adj == 1) ? $req['satuan'] : $req['satuan'] * -1;
            $modelHist->dTotal = ($adj == 1) ? $req['total'] : $req['total'] * -1;

            if (!$modelHist->save(false)) {
                return [
                    'status' => false,
                    'error' => implode('<br>', $modelHist->getFirstErrors())
                ];
            } else {
                return ['status' => true];
            }
        } else {
            return [
                'status' => false,
                'error' => 'Lengkapi parameter!'
            ];
        }
    }



    public function findModel($iId)
    {
        if (($model = TrHist::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    private function _getNameClass()
    {
        return \yii\helpers\StringHelper::basename(get_class($this->createModel()));
    }

    // private function _getSearchClass()
    // {
    //     return \yii\helpers\StringHelper::basename(get_class($this->createSearch()));
    // }

    // public function mapQueryParams($params, $search = false)
    // {
    //     $map = $this->getAttributeMap();
    //     $baseName = ($search) ? $this->_getSearchClass() : $this->_getNameClass();
    //     $result = [$baseName => []];

    //     foreach ($params as $key => $value) {
    //         // Jika ada di map, pakai nama asli. Jika tidak, pakai key aslinya.
    //         $realKey = $map[$key] ?? $key;
    //         $result[$baseName][$realKey] = $value;
    //     }

    //     return $result;
    // }
}
