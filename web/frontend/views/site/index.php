<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use common\helper\Helper;

$this->title = "Dashboard";
$dateNow = date('Y-m-d');
$this->registerCssFile('@web/libs/chartjs/Chart.min.css');
$this->registerJsFile("@web/libs/full-calendar/index.global.min.js", ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerJsFile("@web/libs/full-calendar/bs5.global.min.js", ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerJsFile("@web/libs/chartjs/Chart.min.js", ['depends' => [\yii\web\YiiAsset::class]]);
$this->registerJsFile("@web/js/apps/component/calendar.js?v" . date('Ymd'), ['depends' => [\yii\web\YiiAsset::class]]);

$user = Yii::$app->user->identity;
?>
<div class="row">
    <div class="col-7 col-md-7">
        <div class="row">
            <div class="col-6 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon green">
                                    <i class="fas fa-arrow-circle-down text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Products In Goods</h6>
                                <h6 class="font-extrabold mb-0"><?= $stock['qtyin'] ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon red">
                                    <i class="fas fa-arrow-circle-up text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">Products Out Goods</h6>
                                <h6 class="font-extrabold mb-0"><?= $stock['qtyout'] ?></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-12">
                <!-- =====================AKL/AKD========================== -->
                <div class="card">
                    <div class="card-header">
                        <h4>Products In/Out</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="stockChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5 col-md-5">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Kalender</h4>
                    </div>
                    <div class="card-body">
                        <div class="disabled-load"></div>
                        <div id='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const ctx = document.getElementById('stockChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'], // Sumbu X
                datasets: [{
                    label: 'Total Per Produk',
                    data: [12, 19, 3, 5, 2], // Data nilainya
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    yAxes: [{ // Catatan: Chart.js versi lama (sesuai folder lu) pakai syntax yAxes
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

    })
</script>