<?php

use yii\helpers\Html;

?>

<table class="table table-striped">
    <thead>
        <tr class="bg-sky">
            <th scope="col" class="text-white">#</th>
            <th scope="col" class="text-white">Produk</th>
            <th scope="col" class="text-white">Batch</th>
            <th scope="col" class="text-white">Expired</th>
            <th scope="col" class="text-white">Qty</th>
            <th scope="col" class="text-white">Harga</th>
            <th scope="col" class="text-white">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $detail) : ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $detail['produk'] ?></td>
                <td><?= $detail['vBatch'] ?></td>
                <td><?= $detail['dExpired'] ?></td>
                <td><?= $detail['iQty'] ?></td>
                <td><?= number_format($detail['dHarga'], 0, '.', ',') ?></td>
                <td><?= number_format($detail['dTotal'], 0, '.', ',') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>