<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\services\AdjustmentService;
use common\helper\Helper;
use kartik\mpdf\Pdf;

/**
 * AdjustmentMinus controller
 */
class AdjustmentMinusController extends Controller
{
    protected $modelService;

    public function init()
    {
        parent::init();

        $this->modelService = new AdjustmentService;
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\helper\ErrorAction',
                // 'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = $this->modelService->createSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'MINUS');

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        } else {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        }
    }

    public function actionDetail()
    {
        $searchModel = $this->modelService->createSearchDetail();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'MINUS');

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('detail', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        } else {
            return $this->render('detail', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        }
    }

    public function actionCreate()
    {
        $model = $this->modelService->createModel();
        $modelDetails = [$this->modelService->createModelDetail()];

        if (Yii::$app->request->post()) {
            $out = $this->modelService->createData(Yii::$app->request->post(), 0);
            Yii::$app->getSession()->setFlash('alert', [
                'icon' => $out['status'] ? 'success' : 'error',
                'title' => $out['status'] ? 'Success' : 'Failed',
                'text' => $out['message'] ?? $out['error'],
            ]);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'modelDetails' => $modelDetails,
            'flag' => 'default'
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->modelService->findModel($id);
        $modelDetails = $model->details;

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post();

            if ($post['buttonExec'] == 'confirm') {
                $out = $this->modelService->confirmData($id, 0);
            } else {
                $out = $this->modelService->updateData($post, $id);
            }

            Yii::$app->getSession()->setFlash('alert', [
                'icon' => $out['status'] ? 'success' : 'error',
                'title' => $out['status'] ? 'Success' : 'Failed',
                'text' => $out['message'] ?? $out['error'],
            ]);

            return $this->refresh();
        }

        return $this->render('update', [
            'model' => $model,
            'modelDetails' => $modelDetails,
        ]);
    }

    public function actionGetView($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->detailData($id);
            if ($out['status']) {
                $out['data'] = $this->modelService->parseDataView($out['data']);
            }
        }

        return $out;
    }

    public function actionDelete($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->deleteData($id);
        }

        return $out;
    }

    public function actionStatus($id, $status)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = $out = Yii::$app->params['baseRes'];
        if (Yii::$app->request->isAjax) {
            $out = $this->modelService->statusData($id, $status);
        }

        return $out;
    }

    public function actionPrint($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        // Paksa response format ke RAW agar tidak ada manipulasi header otomatis dari Yii
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        // Pastikan header Content-Disposition adalah inline
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        $headers->add('Content-Disposition', 'inline; filename="Form_Adjustment_' . $id . '.pdf"');
        try {
            $model = $this->modelService->findModel($id);
            $modelDetails = $model->details;

            if ($modelDetails) {
                $content = $this->renderPartial('print', ['model' => $model, 'modelDetails' => $modelDetails]);
                $mpdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    // 'format' => Pdf::FORMAT_A4,
                    'format' => (count($modelDetails) > 5) ? Pdf::FORMAT_LETTER : Pdf::FORMAT_A4, // here define custom [width, height] in mm
                    // 'format' => [219, 140], // here define custom [width, height] in mm
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'marginRight' => 7,
                    'marginLeft' => 7,
                    'filename' => 'Form_Adjustment_Minus_' . $model->vAdjNo . '.pdf',
                    // your html content input
                    'content' => $content,
                    'cssFile' => '@webroot/css/print.css',
                    'methods' => [
                        'setTitle' => "Adjustment Minus - " . $model->vAdjNo,
                        'SetFooter' => [Helper::formatTanggalIndo(date('Y-m-d')) . ' ||{PAGENO}'],
                    ]
                ]);

                if (ob_get_length() > 0) {
                    ob_clean();
                }

                // return the pdf output as per the destination setting
                return $mpdf->render();
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'icon' => 'error',
                    'title' => 'Failed',
                    'text' => 'Tidak ada data untuk ditampilkan',
                ]);
            }
        } catch (\Exception $e) {
            Yii::$app->getSession()->setFlash('alert', [
                'icon' => 'error',
                'title' => 'Failed',
                'text' => $e->getMessage(),
            ]);
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
