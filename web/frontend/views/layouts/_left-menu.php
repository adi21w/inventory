<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\Menu;
?>
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="/"><?= Html::img('@web/images/logo.webp', ['alt' => 'TPS', 'style' => 'height:5rem;']); ?></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                <?php
                $listParent = Menu::find()->where(['level' => 1])->orderBy(['order' =>  SORT_ASC])->asArray()->all();
                foreach ($listParent as $menu1) {
                    if (Yii::$app->user->can($menu1['route'])) {
                ?>
                        <?php if ($menu1 && $menu1['eparent'] == 'Ya') {
                            $listChild = Menu::find()->where(['level' => 2, 'parent' => $menu1['id']])->orderBy(['order' =>  SORT_ASC])->asArray()->all();
                            $visibleChild = array_filter($listChild, fn($child) => Yii::$app->user->can($child['route']));
                        ?>
                            <?php if (count($visibleChild) > 0) { ?>
                                <li class="sidebar-item  has-sub">
                                    <a href="<?= Url::to([$menu1['route']]); ?>" class='sidebar-link'>
                                        <i class="<?= $menu1['icon']; ?>"></i>
                                        <span><?= $menu1['name']; ?></span>
                                    </a>
                                    <ul class="submenu ">
                                        <?php foreach ($listChild as $menu2) {
                                            if (Yii::$app->user->can($menu2['route'])) {
                                        ?>
                                                <li class="submenu-item" data-route>
                                                    <a href="<?= Url::to([$menu2['route']]); ?>" style="padding:.7rem 1rem;">
                                                        <i class="<?= $menu2['icon']; ?>"></i>
                                                        <span><?= $menu2['name']; ?></span>
                                                    </a>
                                                </li>
                                        <?php }
                                        } ?>
                                    </ul>
                                </li>
                            <?php } ?>
                        <?php } else { ?>
                            <li class="sidebar-item" data-route>
                                <a href="<?= Url::to([$menu1['route']]); ?>" class='sidebar-link'>
                                    <i class="<?= $menu1['icon']; ?>"></i>
                                    <span><?= $menu1['name']; ?></span>
                                </a>
                            </li>
                        <?php } ?>
                <?php }
                } ?>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>