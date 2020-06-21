<?php

use eluhr\shop\models\ShopSettings;

$logo = ShopSettings::shopMailLogo();
if (!empty($logo)):
    ?>
    <img src="<?= $logo ?>" alt="LOGO">
<?php endif ?>