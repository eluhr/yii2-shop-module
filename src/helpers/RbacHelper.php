<?php

namespace eluhr\shop\helpers;

use eluhr\shop\Module;

/**
 * --- PROPERTIES ---
 *
 * @author Elias Luhr
 */
class RbacHelper
{
    public static function userIsShopEditor(): bool
    {
        return \Yii::$app->getUser()->can(Module::SHOP_EDITOR_ROLE);
    }
}
