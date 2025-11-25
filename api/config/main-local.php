<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=127.0.0.1:8889;dbname=tw_go_solution',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'signer' => \sizeg\jwt\JwtSigner::HS256,
            'signerKey' => \sizeg\jwt\JwtKey::PLAIN_TEXT,
            'signerKeyContents' => '404679d33d542c0db81609f3a0a4bf6592dcebd6871565c6a7f81e1348840418',
        ],
    ],
];
