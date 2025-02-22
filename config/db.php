<?php
$YII_ENV_DEV = YII_ENV === 'dev';
if ($YII_ENV_DEV) {
    $host = '127.0.0.1';
    $db = 'stock_management';
    $user = 'root';
    $pwd = '';
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $host . ';dbname=' . $db,
    'username' => $user,
    'password' => $pwd,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => $YII_ENV_DEV,
    'schemaCacheDuration' => $YII_ENV_DEV ? 60 : null,
    'schemaCache' => $YII_ENV_DEV ? 'cache' : null,
];
