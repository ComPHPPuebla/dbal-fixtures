<?php
return [
    'url' => 'mysql://' . $_ENV['user'] . ':' . $_ENV['password'] . '@localhost/test_fixture',
    'driver' => 'pdo_mysql',
];
