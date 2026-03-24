<?php

use Hoo\ProductFeeds\Domain;
use Hoo\ProductFeeds\Infrastructure;
use Hoo\WordPressPluginFramework\Database\DatabaseInterface;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/../../../wp-load.php';

define('WOOCOMMERCE_PRODUCT_FEEDS', true);
define('WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once __DIR__ . '/container.php';

$productRepository = $container->get(Domain\Repository\Product\RepositoryInterface::class);

print_r($productRepository->all());