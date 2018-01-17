<?php

$configPath = sprintf('config/client/%s/%s.php', getenv('HOSTNAME'), getenv('APPLICATION_ENV'));
if (! is_dir($configPath)) {
    $configPath = sprintf('config/client/%s/environment.global.php', getenv('HOSTNAME'));
}
$realConfig = include $configPath;

$config = $realConfig['saml']['metadata'];

foreach($config as $url => $data) {
    $metadata[$url] = $data;
}