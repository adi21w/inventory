<?php

namespace common\services;

use yii\web\NotFoundHttpException;
use common\models\Users;
use common\models\UsersSearch;
use common\helper\Helper;

class UsersService
{
    private $_modul = "User";

    public function createModel()
    {
        return new Users();
    }

    public function createSearch()
    {
        return new UsersSearch();
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
                $model->status = 10;
                $model->password_hash = password_hash('123456', PASSWORD_DEFAULT);

                if ($model->save()) {
                    $result['status'] = true;
                    $result['message'] = 'Berhasil membuat data user ' . $model->name;
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

    public function updateData($req, $id = false)
    {
        if (!$id) {
            return [
                'status' => false,
                'error' => 'Paramater id tidak ada!'
            ];
        }

        try {
            $result = ['status' => false];
            $model = $this->createModel();
            if ($model->load($req)) {
                $modelOld = $this->findModel($id);
                if (!$modelOld) {
                    return [
                        'status' => false,
                        'error' => "Data {$this->_modul} tidak ditemukan!"
                    ];
                }

                $modelOld->name = $model->name;
                $modelOld->email = $model->email;
                $modelOld->number_phone = $model->number_phone;

                if ($modelOld->save()) {
                    $result['status'] = true;
                    $result['message'] = 'Berhasil merubah data ' . $model->name;
                } else {
                    $result['error'] = Helper::errorHandling($modelOld->errors);
                }
            } else {
                $result['error'] = Helper::errorHandling($model->errors);
            }

            return $result;
        } catch (\Throwable $e) {
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function statusData($id, $status = 1)
    {
        $model = $this->findModel($id);

        if (!$model) {
            return [
                'status' => false,
                'error' => "Data {$this->_modul} tidak ditemukan!"
            ];
        }

        $model->status = $status;
        $message = ($status == 10) ? "Mengaktifkan {$this->_modul}" : "Menonaktifkan {$this->_modul}";

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

    public function changePassword($id, $password)
    {
        $model = $this->findModel($id);
        $model->password_hash = password_hash($password, PASSWORD_DEFAULT);

        if ($model->update()) {
            return [
                'status' => true,
                'message' => 'Berhasil mengganti password'
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
        if (($model = Users::findOne(['id' => $iId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
