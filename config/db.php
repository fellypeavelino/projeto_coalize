<?php

return [
    'class' => 'yii\db\Connection',
    #'dsn' => 'mysql:host=127.0.0.1:48002;dbname=coalize',
    'dsn' => 'mysql:host=172.18.0.4:3306;dbname=coalize',
    'username' => 'coalize',
    'password' => '123',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
