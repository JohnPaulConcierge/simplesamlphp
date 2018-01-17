<?php
// I can't begin to express how sorry I am for this.
$configPath = sprintf(__DIR__ . '../../../../../config/client/%s/%s.php', getenv('HOSTNAME'), getenv('APPLICATION_ENV'));
if (! is_dir($configPath)) {
    $configPath = sprintf(__DIR__ . '../../../../../config/client/%s/environment.global.php', getenv('HOSTNAME'));
}
$realConfig = include $configPath;

$config = $realConfig['saml']['service_provider'];