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

$dotEnv = new \Symfony\Component\Dotenv\Dotenv();
$dotEnv->loadEnv(__DIR__ . '/../.env');

$containerBuilder->compile(true);

/** @var \Isaev\WatchLog\WatchLog $watch */
$watch = $containerBuilder->get(\Isaev\WatchLog\WatchLog::class);
if (in_array('--debug', $argv, true)) {
    $watch->setIsDebugMode(true);
}
$watch->start();
