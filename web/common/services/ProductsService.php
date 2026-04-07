<?php

namespace common\services;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use common\models\Products;
use common\models\ProductsSearch;
use common\models\HPriceSearch;
use common\helper\Helper;

class ProductsService
{
    private $_modul = "Produk";

    public function createModel()
    {
        return new Products();
    }

    public function createSearch()
    {
        return new ProductsSearch();
    }

    public function historySearch()
    {
        return new HPriceSearch();
    }

    public function detailData($id)
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
                $onUpload = $this->uploadFile($model, 'vImage');
                if (is_null($onUpload['error'])) {
                    $model->vImage = $onUpload['status'] ? $onUpload['name'] : null;
                    if ($model->save()) {
                        $result['status'] = true;
                        $result['message'] = 'Berhasil menyimpan data ' . $model->vNama;
                    } else {
                        $result['error'] = Helper::errorHandling($model->errors);
                    }
                } else {
                    $result['error'] = 'Gagal mengupload gambar produk. Pastikan file yang diupload sesuai dengan ketentuan.';
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
        try {
            $result = ['status' => false];
            $model = $this->findModel($id);

            if (!$model) {
                return [
                    'status' => false,
                    'error' => "Data {$this->_modul} tidak ditemukan!"
                ];
            }

            if ($model->load($req)) {
                $onUpload = $this->uploadFile($model, 'vImage');
                if (is_null($onUpload['error'])) {
                    if ($onUpload['status']) {
                        $model->vImage = $onUpload['name'];
                    } else {
                        $model->vImage = $model->getOldAttribute('vImage');
                    }

                    if ($model->save()) {
                        $result['status'] = true;
                        $result['message'] = 'Berhasil menyimpan data ' . $model->vNama;
                    } else {
                        $result['error'] = Helper::errorHandling($model->errors);
                    }
                } else {
                    $result['error'] = 'Gagal mengupload gambar produk. Pastikan file yang diupload sesuai dengan ketentuan.';
                }
            } else {
                $result['error'] = Helper::errorHandling($model->errors);
            }

            return $result;
        } catch (\Throwable $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    protected function uploadFile($model, $attribute)
    {
        $result = ['status' => false, 'error' => null, 'name' => null];
        $file = UploadedFile::getInstance($model, $attribute);
        if ($file) {
            $fileName = uniqid() . '_' . $file->baseName . '.' . $file->extension;
            $filePath = 'uploads/products/' . $fileName;
            if ($file->saveAs($filePath)) {
                $result['status'] = true;
                $result['name'] = $fileName;
            } else {
                $result['error'] = 'Gagal mengupload gambar produk. Pastikan file yang diupload sesuai dengan ketentuan.';
            }
        }
        return $result;
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

    public function findModel($iId)
    {
        if (($model = Products::findOne(['iId' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function parseDataView($data)
    {
        $result = [];
        $result[] = ['label' => 'Image', 'value' => $data->vImage ?? null, 'format' => 'image', 'path' => Yii::$app->params['WebUrl'] . 'uploads/products/'];
        $result[] = ['label' => 'Nama', 'value' => $data->vNama];
        $result[] = ['label' => 'Status', 'value' => $data->iStatus == 1 ? 'Active' : 'Disabled'];
        $result[] = ['label' => 'Slug', 'value' => $data->vSlug];
        $result[] = ['label' => 'Rak', 'value' => $data->rak ? $data->rak->vNama : '-'];
        $result[] = ['label' => 'Kemasan Besar', 'value' => $data->kemasan ? $data->kemasan->vNama : '-'];
        $result[] = ['label' => 'Isi Kemasan Besar', 'value' => $data->iIsiKemasanBesar];
        $result[] = ['label' => 'Kemasan Kecil', 'value' => $data->kemasankecil ? $data->kemasankecil->vNama : '-'];
        $result[] = ['label' => 'Isi Kemasan Kecil', 'value' => $data->iIsiKemasanKecil];
        $result[] = ['label' => 'Harga', 'value' => $data->dPrice, 'format' => 'currency'];
        $result[] = ['label' => 'Margin', 'value' => $data->dMargin];
        $result[] = ['label' => 'Created By', 'value' => $data->created ? $data->created->name : '-'];
        $result[] = ['label' => 'Updated By', 'value' => $data->updated ? $data->updated->name : '-'];
        $result[] = ['label' => 'Time Created', 'value' => $data->tCreated];
        $result[] = ['label' => 'Time Updated', 'value' => $data->tUpdated];

        return $result;
    }

    public function packDataView($data)
    {
        $result = [];
        $result['big'] = ['label' => $data->kemasan ? $data->kemasan->vNama : '-', 'value' => $data->iKemasanBesarId];
        $result['small'] = ['label' => $data->kemasankecil ? $data->kemasankecil->vNama : '-', 'value' => $data->iKemasanKecilId];

        return $result;
    }

    public function searchList($q)
    {
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $model = Products::find()
                ->alias('b')
                ->select(['b.iId as id', 'b.vNama as text', 'r.vNama as description'])
                ->joinWith(['rak r'])
                ->where(['b.eDeleted' => 'Tidak', 'b.iStatus' => 1])
                ->andFilterWhere(['OR', ['LIKE', 'b.vNama', $q], ['LIKE', 'r.vNama', $q]])
                ->limit(20)
                ->asArray()
                ->all();
            $out['results'] = $model;
        }
        return $out;
    }
}
