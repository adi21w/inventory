<?php

use yii\helpers\Html;

?>

<table class="table table-striped table-expand">
    <thead>
        <tr class="bg-sky">
            <th scope="col">#</th>
            <th scope="col">Batch</th>
            <th scope="col">Expired</th>
            <th scope="col">Qty</th>
            <th scope="col">Qty Out</th>
            <th scope="col">Qty End</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $key => $detail) : ?>
            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $detail['vBatch'] ?></td>
                <td><?= $detail['dExpired'] ?></td>
                <td><?= $detail['iQty'] ?></td>
                <td><?= $detail['iQty2'] ?></td>
                <td><?= $detail['iQtysum'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>