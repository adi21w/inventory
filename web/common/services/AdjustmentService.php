<?php

namespace common\services;

use Yii;
use yii\web\NotFoundHttpException;
use common\models\TAdjustmentHd;
use common\models\TAdjustmentDt;
use common\models\TAdjustmentHdSearch;
use common\models\TAdjustmentDtSearch;
use common\services\StockService;
use common\services\TrHistService;
use common\models\Model;
use common\helper\Helper;
use yii\helpers\ArrayHelper;

class AdjustmentService
{
    private $_modul = "Adjustment";

    protected function getAttributeMap()
    {
        return [
            'pack' => 'vNama'
        ];
    }

    public function createModel()
    {
        return new TAdjustmentHd();
    }

    public function createSearch()
    {
        return new TAdjustmentHdSearch();
    }

    public function createModelDetail()
    {
        return new TAdjustmentDt();
    }

    public function createSearchDetail()
    {
        return new TAdjustmentDtSearch();
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

    public function createData($req, $q = 1)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $result = ['status' => false, 'error' => 'Data tidak valid!'];
            $model = $this->createModel();
            $isValid = false;

            if ($model->load($req) && $model->validate()) {
                $onLoad = $this->loadDetails($req, $this->_getNameClassDetail());
                $modelDetails = $onLoad[0];
                $valid = Model::validateMultiple($modelDetails);

                if ($valid) {
                    $model->eType = ($q == 1) ? 'PLUS' : 'MINUS';
                    $model->dTotal = $onLoad[1];

                    if ($isValid = $model->save()) {
                        foreach ($modelDetails as $modelDetail) {
                            $modelDetail->vAdjNo = $model->vAdjNo;

                            if (!$isValid = $modelDetail->save()) {
                                $result['error'] = implode('<br>', $modelDetail->getFirstErrors());
                                break;
                            }
                        }
                    } else {
                        $result['error'] = implode('<br>', $model->getFirstErrors());
                    }

                    if ($isValid) {
                        $transaction->commit();
                        $result['status'] = true;
                        $result['message'] = 'Berhasil menyimpan data ' . $model->vAdjNo;
                    } else {
                        $transaction->rollBack();
                    }
                }
            } else {
                $result['error'] = Helper::errorHandling($model->errors);
            }

            return $result;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateData($req, $id = null)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $result = ['status' => false, 'error' => 'Data tidak valid!'];
            $model = $this->findModel($id);
            $modelDetails = $model->details;
            $isValid = false;
            if ($model->load($req)) {
                $oldIDs = ArrayHelper::map($modelDetails, 'iId', 'iId');

                $onLoad = $this->loadDetails($req, $this->_getNameClassDetail());
                $modelDetails = $onLoad[0];
                $valid = Model::validateMultiple($modelDetails);

                $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'iId', 'iId')));

                if ($valid) {
                    $model->dTotal = $onLoad[1];

                    if ($isValid = $model->save()) {
                        if (!empty($deletedIDs)) {
                            TAdjustmentDt::deleteAll(['iId' => $deletedIDs]);
                        }

                        foreach ($modelDetails as $modelDetail) {
                            $modelDetail->vAdjNo = $model->vAdjNo;
                            if (!$isValid = $modelDetail->save()) {
                                $result['error'] = implode('<br>', $modelDetail->getFirstErrors());
                                break;
                            }
                        }
                    } else {
                        $result['error'] = implode('<br>', $model->getFirstErrors());
                    }

                    if ($isValid) {
                        $transaction->commit();
                        $result['status'] = true;
                        $result['message'] = 'Berhasil menyimpan data ' . $model->vAdjNo;
                    } else {
                        $transaction->rollBack();
                    }
                }
            } else {
                $result['error'] = Helper::errorHandling($model->errors);
            }

            return $result;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function confirmData($id, $q)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $result = ['status' => false, 'error' => 'Data tidak valid!'];
            $model = $this->findModel($id);
            $isValid = false;

            $model->dConfirm = date('Y-m-d');
            $model->eConfirm = 'Ya';

            if ($isValid = $model->save()) {
                foreach ($model->details as $key => $detail) {
                    $checkStock = StockService::saveData([
                        'produk' => $detail->iProdukId,
                        'gudang' => $model->iGudangId,
                        'batch' => $detail->vBatch,
                        'expired' => $detail->dExpired,
                        'qty' => $detail->iQty2
                    ], $q);

                    if ($isValid = $checkStock['status']) {
                        $checkHist = TrHistService::saveData([
                            'produk' => $detail->iProdukId,
                            'gudang' => $model->iGudangId,
                            'batch' => $detail->vBatch,
                            'qty' => $detail->iQty2,
                            'number' => $model->vAdjNo,
                            'tipe' => ($q == 1) ? 'ADJ-IN' : 'ADJ-OUT',
                            'kemasan' => $detail->product->kemasankecil->iId,
                            'date' => $model->dConfirm,
                            'satuan' => $detail->dHargasatuan,
                            'total' => $detail->dTotal
                        ], $q);

                        if (!$isValid = $checkHist['status']) {
                            $result['error'] = $checkHist['error'];
                        }
                    } else {
                        $result['error'] = $checkStock['error'];
                    }
                }
            } else {
                $result['error'] = implode('<br>', $model->getFirstErrors());
            }

            if ($isValid) {
                $transaction->commit();
                $result['status'] = true;
                $result['message'] = 'Berhasil konfirmasi data ' . $model->vAdjNo;
            } else {
                $transaction->rollBack();
            }

            return $result;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteData($id)
    {
        $model = $this->findModel($id);

        if (!$model) {
            return [
                'status' => false,
                'error' => "Data {$this->_modul} tidak ditemukan!"
            ];
        }

        $model->eDeleted = 'Ya';

        if ($model->update()) {
            return [
                'status' => true,
                'message' => 'Data berhasil dihapus dari list'
            ];
        } else {
            return [
                'status' => false,
                'error' => Helper::errorHandling($model->errors)
            ];
        }
    }

    public function findModel($iId)
    {
        if (($model = TAdjustmentHd::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelDetail($iId)
    {
        if (($model = TAdjustmentDt::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function parseDataView($data)
    {
        $result = [];
        $result[] = ['label' => 'No Adjustment', 'value' => $data->vAdjNo];
        $result[] = ['label' => 'Warehouse', 'value' => $data->warehouse->vNama];
        $result[] = ['label' => 'Category', 'value' => $data->eKategori];
        $result[] = ['label' => 'Type', 'value' => $data->eType];
        $result[] = ['label' => 'Confrim?', 'value' => $data->eConfirm];
        $result[] = ['label' => 'Confrim Date', 'value' => ($data->dConfirm) ? Helper::formatTanggalIndo($data->dConfirm) : '-'];
        $result[] = ['label' => 'Total', 'value' => $data->dTotal, 'format' => 'currency'];
        $result[] = ['label' => 'Created By', 'value' => $data->created ? $data->created->name : '-'];
        $result[] = ['label' => 'Updated By', 'value' => $data->updated ? $data->updated->name : '-'];
        $result[] = ['label' => 'Time Created', 'value' => $data->tCreated];
        $result[] = ['label' => 'Time Updated', 'value' => $data->tUpdated];
        $result[] = ['label' => 'Details', 'value' => $data->expands, 'format' => 'table'];

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

    protected function loadDetails($req, $form)
    {
        try {
            $models = [];
            $total = 0;
            foreach ($req[$form] as $index => $detail) {
                $data = [$form => $detail];

                $modelDetail = (empty($detail['iId']) || !isset($detail['iId'])) ? new TAdjustmentDt() : $this->findModelDetail($detail['iId']);
                $modelDetail->load($data);

                $produk = $modelDetail->product;

                if ($modelDetail->iKemasanId == $produk->iKemasanBesarId) {
                    $modelDetail->dHarga = $produk->dPrice;
                    $modelDetail->dHargasatuan = Helper::priceSmall($produk->dPrice, $produk->iIsiKemasanKecil);
                    $modelDetail->iQty2 = $modelDetail->iQty * $produk->iIsiKemasanKecil;
                } else {
                    $modelDetail->dHarga = Helper::priceSmall($produk->dPrice, $produk->iIsiKemasanKecil);
                    $modelDetail->dHargasatuan = $modelDetail->dHarga;
                    $modelDetail->iQty2 = $modelDetail->iQty;
                }
                $modelDetail->dTotal = $modelDetail->dHarga * $modelDetail->iQty;

                $total += $modelDetail->dTotal;
                $models[] = $modelDetail;
            }
            return [$models, round($total, 2)];
        } catch (\Throwable $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }
}
