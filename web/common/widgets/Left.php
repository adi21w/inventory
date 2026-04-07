<?php

namespace common\widgets;

use Yii;
use common\models\AuthAssignment;
use common\models\AuthItemChild;
use frontend\models\Menu;
use yii\base\Widget;

class Left extends Widget
{
    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $id = Yii::$app->user->identity->id;
        $AuthAssignment = AuthAssignment::find()->select(['item_name'])->where(['user_id' => $id])->One();

        if ($AuthAssignment) {
            $role = $AuthAssignment->item_name;
            $AuthItemChild = AuthItemChild::find()->select(['child'])->where(['parent' => $role])->All();
            $accessmenu = array();
            $checkmenu = array();
            $menuchild = array();
            $idparent = array();
            $idparent2 = array();
            $urutantampil =  array();
            $menuparent = array();

            foreach ($AuthItemChild as $menuaccess) {
                $isi = explode('/', $menuaccess->child);
                if (strpos($menuaccess->child, '*') !== false || strpos($menuaccess->child, 'index') !== false) {
                    if (!in_array($menuaccess->child, $accessmenu)) {
                        array_push($accessmenu, $menuaccess->child);
                    }
                } else if (
                    strpos($menuaccess->child, 'create') == false && strpos($menuaccess->child, 'view') == false
                    && strpos($menuaccess->child, 'delete') == false && strpos($menuaccess->child, 'update') == false
                ) {
                    if (!in_array($menuaccess->child, $accessmenu)) {
                        array_push($accessmenu, $menuaccess->child);
                    }
                }
            }

            foreach ($accessmenu as $item) {
                if (strpos($item, '*') !== false || strpos($item, 'index') !== false) {
                    $isi = explode('/', $item);
                    if (in_array($isi[1], $checkmenu)) {
                        $Menu = '';
                    } else {
                        array_push($checkmenu, $isi[1]);
                        $Menu = Menu::find()->select(['name', 'eparent', 'parent', 'route', 'icon', 'id', 'level', 'order'])->where(['like', 'route', '%/' . $isi[1] . '/%', false])->one();
                    }
                } else {
                    $Menu = Menu::find()->select(['name', 'eparent', 'parent', 'route', 'icon', 'id', 'level', 'order'])->where(['route' => $item])->one();
                }
                if ($Menu != '') {
                    $data = [
                        "id" => $Menu->id,
                        "name" => $Menu->name,
                        "eparent" => $Menu->eparent,
                        "parentid" => $Menu->parent,
                        "route" => $Menu->route,
                        "icon" => $Menu->icon,
                        "order" => $Menu->order,
                        "level" => $Menu->level
                    ];
                    array_push($menuchild, $data);
                    sort($menuchild);
                    if ($Menu->level == '1' && $Menu->eparent == 'Tidak') {
                        if (!in_array($Menu->id, $idparent)) {
                            array_push($idparent, $Menu->id);
                            array_push($urutantampil, $Menu->id);
                        }
                    } else {
                        $Menu2 = Menu::find()->select(['id'])->where(['id' => $Menu->parent])->andWhere(['parent' => null])->andWhere(['level' => 1])->one();
                        if ($Menu2 != '') {
                            if (!in_array($Menu2->id, $idparent)) {
                                array_push($idparent, $Menu2->id);
                                array_push($urutantampil, $Menu2->id);
                            }
                        }
                    }
                }
            }

            sort($idparent2);
            foreach ($idparent2 as $parentid2) {
                $aktif = "";
                $Menu = Menu::find()->select(['name', 'eparent', 'route', 'icon', 'parent'])->where(['id' => $parentid2])
                    ->andWhere(['eparent' => 'Ya'])->andWhere(['route' => '/#'])->andWhere(['level' => 2])->one();
                if ($Menu != '') {
                    if (!in_array($Menu->parent, $idparent)) {
                        array_push($idparent, $Menu->parent);
                        array_push($urutantampil, $Menu->parent);
                    }
                }
            }
            sort($idparent);

            foreach ($idparent as $parentid) {
                $aktif = "";
                $Menu = Menu::find()->select(['name', 'eparent', 'route', 'icon', 'level'])->where(['id' => $parentid])->andWhere(['parent' => null])->andWhere(['level' => 1])->one();
                if ($Menu != '') {
                    $urlcheck2 = '/' . Yii::$app->requestedRoute;
                    $Menuchild2 = Menu::find()->select(['id', 'parent', 'route', 'level'])->where(['route' => $urlcheck2])->andWhere(['id' => $parentid])->one();
                    if ($Menuchild2 != '') {
                        if ($parentid == $Menuchild2->parent) {
                            $aktif = "active";
                        } else if ($Menuchild2->level == '1' && $Menuchild2->parent == '') {
                            $aktif = "active";
                        }
                    }
                    $data = [
                        "name" => $Menu->name,
                        "eparent" => $Menu->eparent,
                        "route" => $Menu->route,
                        "icon" => $Menu->icon,
                        "id" => $parentid,
                        "aktif" => $aktif,
                        "level" => $Menu->level
                    ];
                    array_push($menuparent, $data);
                }
            }

            $a = array_column($menuchild, 'parentid');
            $b = array_column($menuchild, 'order');
            array_multisort($a, SORT_ASC, $b, SORT_ASC, $menuchild);
        } else {
            $menuchild = '';
            $menuparent = '';
        }

        return [
            'daftarchild' => $menuchild,
            'daftarparent' => $menuparent,
        ];
    }
}
