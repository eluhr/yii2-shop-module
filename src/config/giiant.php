<?php


use schmunk42\giiant\commands\BatchController;
use schmunk42\giiant\generators\crud\callbacks\base\Callback;
use schmunk42\giiant\generators\crud\providers\core\CallbackProvider;
use schmunk42\giiant\generators\crud\providers\core\OptsProvider;
use schmunk42\giiant\generators\crud\providers\core\RelationProvider;
use eluhr\shop\controllers\crud\Controller;
use eluhr\shop\models\ActiveRecord;

$config = require dirname(__DIR__, 5) . '/config/main.php';

\Yii::$container->set(
    CallbackProvider::class,
    [
        'activeFields' => [
            'created_at|updated_at' => Callback::false(),
            'is_online|show_in_frontend' => function ($attribute) {
                return "\$form->field(\$model,'{$attribute}')->checkbox([], false);";
            },
            'thumbnail_image' => function ($attribute) {
                return "\$form->field(\$model, '{$attribute}')->widget(hrzg\\filemanager\widgets\FileManagerInputWidget::class,['handlerUrl' => '/filefly/api']);";
            }
        ]
    ]
);

$config['controllerMap']['shop:crud'] = [
    'class' => BatchController::class,
    'overwrite' => true,
    'interactive' => false,
    'crudBaseControllerClass' => Controller::class,
    'modelBaseClass' => ActiveRecord::class,
    'modelNamespace' =>  'eluhr\\shop\\models',
    'modelQueryNamespace' => 'eluhr\\shop\\models\\query',
    'crudControllerNamespace' => 'eluhr\\shop\\controllers\\crud',
    'crudSearchModelNamespace' => 'eluhr\\shop\\models\\search',
    'crudViewPath' => '@eluhr/shop/views/crud',
    'crudPathPrefix' => '/shop/crud/',
    'crudTidyOutput' => false,
    'crudAccessFilter' => false,
    'useTimestampBehavior' => false,
    'tablePrefix' => 'sp_',
    'crudMessageCategory' => 'shop',
    'modelMessageCategory' => 'shop',
    'crudProviders' => [
        CallbackProvider::class,
        OptsProvider::class,
        RelationProvider::class,
    ],
    // SELECT GROUP_CONCAT("'",TABLE_NAME,"'") FROM information_schema.TABLES WHERE TABLE_NAME LIKE "sp_%";
    'tables' => [
        'sp_filter',
        'sp_order',
        'sp_order_item',
        'sp_product',
        'sp_tag',
        'sp_tag_x_filter',
        'sp_tag_x_product',
        'sp_variant',
        'sp_discount_code',
        'sp_settings'
    ]
];

return $config;
