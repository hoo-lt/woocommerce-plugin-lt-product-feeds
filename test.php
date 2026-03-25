<?php

use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure;
use Hoo\WooCommercePlugin\LtProductFeeds\Presentation;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/../../../wp-load.php';

define('WOOCOMMERCE_PRODUCT_FEEDS', true);
define('WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_PATH', plugin_dir_path(__FILE__));

$definitions = require __DIR__ . '/definitions.php';

$containerBuilder = new DI\ContainerBuilder();
$containerBuilder->addDefinitions($definitions);

$container = $containerBuilder->build();

$repository = $container->get(Infrastructure\Repository\Attribute\Repository::class);

print_r($repository->all());