<?php

namespace common\helper;

use yii\rbac\DbManager;

class MyAuthManager extends DbManager
{
    public function checkAccess($userId, $permissionName, $params = [])
    {
        // Cek permission exact
        if (parent::checkAccess($userId, $permissionName, $params)) {
            return true;
        }
        // $permissionName = 'site/index';
        // Cek wildcard: 'site/index' → cari 'site/*'
        if (str_contains($permissionName, '/')) {
            $controller = explode('/', $permissionName);
            $wildcardPermission = '/' . $controller[1] . '/*';

            if (parent::checkAccess($userId, $wildcardPermission, $params)) {
                return true;
            }
        }

        return false;
    }
}
