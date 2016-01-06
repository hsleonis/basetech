<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
    
);

require(__DIR__ . '/default_values_for_widgets.php');

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log','app\base\settings','GlobalClass'],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ]
    ],
    'homeUrl' => '/projects/web/base/portal/administrator',
    'components' => [
        'request' => [
            'baseUrl' => '/projects/web/base/portal/administrator',
            'enableCsrfValidation' => false,
        ],
        'GlobalClass'=>[
            'class'=>'backend\components\GlobalClass'
         ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.shantaholdings.com',
                'username' => 'webmail@shantaholdings.com',
                'password' => 'Shanta123$',
                'port' => '26',
                'encryption' => 'tls',
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'urlManager' => [
        
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['en', 'bn'],
            'enableDefaultLanguageUrlCode' => false,
            'enableLanguagePersistence' => false,

            'enablePrettyUrl' => true,
            'showScriptName'  => false,
        ],
        'urlManagerFrontEnd' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/projects/web/base/portal/frontend/web',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
            ],
        ],
        /*'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/basic'],
                'baseUrl' => '@web/themes/basic',
            ],
        ],*/
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'admin/*','site/logout','setup/*','site/lock_screen?*' // add or remove allowed actions to this list
        ]
    ],
    'params' => $params,
    'vendorPath' => dirname(__DIR__).'/../vendor',
    'language' => 'en',
];
