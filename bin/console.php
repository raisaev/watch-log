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

/** @var \Isaev\WatchLog\Console\Application $app */
$app = $containerBuilder->get(\Isaev\WatchLog\Console\Application::class);
$app->run();
