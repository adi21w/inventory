<?php
define('SYS_DB', 'mysql:host=127.0.0.1;port=3307;dbname=db_clone');
define('SYS_USERNAME', 'root');
define('SYS_PASSWORD', '123456');
return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => SYS_DB,
            'username' => SYS_USERNAME,
            'password' => SYS_PASSWORD,
            'charset' => 'utf8mb4',
            'attributes' => [PDO::ATTR_PERSISTENT => true],
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 86400,
            'schemaCache' => 'cache',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            // You have to set
            //
            // 'useFileTransport' => false,
            //
            // and configure a transport for the mailer to send real emails.
            //
            // SMTP server example:
            //    'transport' => [
            //        'scheme' => 'smtps',
            //        'host' => '',
            //        'username' => '',
            //        'password' => '',
            //        'port' => 465,
            //        'dsn' => 'native://default',
            //    ],
            //
            // DSN example:
            //    'transport' => [
            //        'dsn' => 'smtp://user:pass@smtp.example.com:25',
            //    ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
