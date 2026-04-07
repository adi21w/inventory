<?php

use yii\helpers\Html;
use common\helper\Helper;
// print_r($detail);
// die;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\StockOpnameSearch $searchModel
 */

$this->title = "Adjustment Plus - " . $model->vAdjNo;
$this->params['breadcrumbs'][] = $this->title;

?>
<div id="printableArea">
    <div class="area-header">
        <table class="table">
            <tr>
                <td width="20%" class="text-center">
                    <img src="<?= Yii::getAlias('@webroot/images/logo.webp') ?>" width="100">
                </td>
                <td width="75%">
                    <div class="fs-14 fw-bold">
                        PT. Name
                    </div>
                    <div class="fs-11">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. A natus corporis eveniet magnam iusto saepe.
                    </div>
                    <div style="height: 10px;">&nbsp;</div>
                    <div class="fs-11">
                        Telp: 021-555-666-333, HP: 0873-8596-213, Fax: 8888-888
                    </div>
                    <div class="fs-11">
                        Email: company@rocket.com
                    </div>
                </td>
            </tr>
        </table>
        <hr class="line-1">
    </div>
    <div class="row header">
        <h3 class="text-center" style="text-decoration: underline;">Keterangan Adjustment Plus</h3>
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <td style="width: 15%;" class="fw-bold">No</td>
                        <td style="width: 1%;">:</td>
                        <td style="width: 30%;"><?= $model->vAdjNo ?></td>
                        <td class="fw-bold">Time Created</td>
                        <td style="width: 1%;">:</td>
                        <td><?= date('Y-m-d', strtotime($model->tCreated)) ?></td>
                    </tr>
                    <tr>
                        <td style="width: 15%;" class="fw-bold">Warehouse</td>
                        <td style="width: 1%;">:</td>
                        <td style="width: 30%;"><?= $model->warehouse->vNama ?></td>
                        <td class="fw-bold">Confirm Date</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $model->dConfirm ?></td>
                    </tr>
                    <tr>
                        <td style="width: 15%;" class="fw-bold">Category</td>
                        <td style="width: 1%;">:</td>
                        <td style="width: 30%;"><?= $model->eKategori ?></td>
                        <td class="fw-bold">Type</td>
                        <td style="width: 1%;">:</td>
                        <td><?= $model->eType ?></td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="container">
            <table class="table-bordered table">
                <thead>
                    <tr>
                        <th class="text-left">No</th>
                        <th class="text-left">Produk</th>
                        <th class="text-left">Batch</th>
                        <th class="text-left">Expired</th>
                        <th class="text-center">iQty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modelDetails as $key => $detail) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= $detail->product->vNama ?></td>
                            <td><?= $detail->vBatch ?></td>
                            <td><?= $detail->dExpired ?></td>
                            <td class="text-center"><?= $detail->iQty ?></td>
                            <td class="text-end">Rp. <?= number_format($detail->dHarga, 0, ',', '.') ?></td>
                            <td class="text-end">Rp. <?= number_format($detail->dTotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <td colspan="6" class="text-center"><b>GRANDTOTAL</b></td>
                        <td class="text-end"><b>Rp. <?= number_format($model->dTotal, 0, ',', '.') ?></b></td>
                    </tr>
                </tbody>
            </table>
            <p style="margin-top:30px;"><strong>Keterangan</strong>: <?= $model->tKeterangan ?></p>
        </div>
    </div>
</div>