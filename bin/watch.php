#!/usr/bin/env php
<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require dirname(__DIR__) . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(dirname(__DIR__) . '/config'));
$loader->load('services.yaml');

$envFiles = [
    __DIR__ . '/../.env',
    __DIR__ . '/../.env.local'
];

$dotEnv = new \Symfony\Component\Dotenv\Dotenv();
foreach ($envFiles as $file) {
    is_file($file) && $dotEnv->load($file);
}

$containerBuilder->compile(true);

/** @var \Isaev\WatchLog\WatchLog $watch */
$watch = $containerBuilder->get(\Isaev\WatchLog\WatchLog::class);
if (in_array('--debug', $argv, true)) {
    $watch->setIsDebugMode(true);
}
$watch->start();
