<?php

namespace common\services;

use yii\web\NotFoundHttpException;
use common\models\Warehouses;
use common\models\WarehousesSearch;
use common\helper\Helper;

class WarehousesService
{
    private $_modul = "Kemasan";

    protected function getAttributeMap()
    {
        return [
            'warehouse' => 'vNama',
            'status' => 'iStatus',
            'stock_status' => 'eStock'
        ];
    }

    public function createModel()
    {
        return new Warehouses();
    }

    public function createSearch()
    {
        return new WarehousesSearch();
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

    public function createData($req)
    {
        try {
            $result = ['status' => false];
            $model = $this->createModel();
            if ($model->load($req) && $model->validate()) {
                $model->vNama = strtoupper($model->vNama);
                if ($model->save()) {
                    $result['status'] = true;
                    $result['message'] = 'Berhasil menyimpan data ' . $model->vNama;
                } else {
                    $result['error'] = Helper::errorHandling($model->errors);
                }
            } else {
                $result['error'] = Helper::errorHandling($model->errors);
            }

            return $result;
        } catch (\Throwable $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateData($req, $id = null)
    {
        if (!$id) {
            return [
                'status' => false,
                'error' => 'Paramater id tidak ada!'
            ];
        }

        try {
            $result = ['status' => false];
            $model = $this->findModel($id);
            if ($model->load($req)) {
                $model->vNama = strtoupper($model->vNama);
                if ($model->save()) {
                    $result['status'] = true;
                    $result['message'] = 'Berhasil merubah data ' . $model->vNama;
                } else {
                    $result['error'] = Helper::errorHandling($model->errors);
                }
            } else {
                $result['error'] = Helper::errorHandling($model->errors);
            }

            return $result;
        } catch (\Throwable $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteData($id)
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

    public function statusData($id, $status = 1)
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
        }

        $model->iStatus = $status;
        $message = ($status == 1) ? "Mengaktifkan {$this->_modul}" : "Menonaktifkan {$this->_modul}";

        if ($model->update()) {
            return [
                'status' => true,
                'message' => 'Berhasil ' . $message
            ];
        } else {
            return [
                'status' => false,
                'error' => Helper::errorHandling($model->errors)
            ];
        }
    }

    protected function findModel($iId)
    {
        if (($model = Warehouses::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function parseDataView($data)
    {
        $result = [];
        $result[] = ['label' => 'Nama', 'value' => $data->vNama];
        $result[] = ['label' => 'Status', 'value' => $data->iStatus == 1 ? 'Active' : 'Disabled'];
        $result[] = ['label' => 'Stock', 'value' => $data->eStock];
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
