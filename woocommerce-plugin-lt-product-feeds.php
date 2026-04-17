<?php

/**
 * Plugin Name: LT Product Feeds for WooCommerce
 * Plugin URI: https://github.com/hoo-lt/woocommerce-plugin-lt-product-feeds
 * Description:
 * Version: 1.0.0
 * Requires at least: 6.9
 * Requires PHP: 8.2
 * Author: Baltic digital agency, UAB
 * Author URI: https://github.com/hoo-lt
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lt-product-feeds
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 */

if (!defined('ABSPATH')) {
	die();
}

define('WOOCOMMERCE_PRODUCT_FEEDS', true);
define('WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_PATH', plugin_dir_path(__FILE__));

require __DIR__ . '/vendor/autoload.php';

$container = require __DIR__ . '/container.php';

use Hoo\WordPressPluginFramework\Hook\Action\Hook as ActionHook;
use Hoo\WordPressPluginFramework\Hook\Filter\Hook as FilterHook;
use Hoo\WordPressPluginFramework\Hook\Activation\Hook as ActivationHook;
use Hoo\WordPressPluginFramework\Hook\Deactivation\Hook as DeactivationHook;
use Hoo\WordPressPluginFramework\Hooker\Hooker;
use Hoo\WordPressPluginFramework\Pipeline\PipelineInterface;
use Hoo\WordPressPluginFramework\Middlewares\VerifyNonce\Middleware as VerifyNonce;
use Hoo\WordPressPluginFramework\Router\Router;
use Hoo\WordPressPluginFramework\Database\Migrator\MigratorInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;
use Hoo\WooCommercePlugin\LtProductFeeds\Presentation;

$hooker = $container->get(Hooker::class);
$router = $container->get(Router::class);
$pipeline = $container->get(PipelineInterface::class);
$verifyNonce = $container->get(VerifyNonce::class);
$termPresenter = $container->get(Presentation\Presenters\Term\Presenter::class);

$hooks = [
	new ActionHook($pipeline, 'admin_enqueue_scripts', fn() =>
		wp_enqueue_style('product-feeds-admin', WOOCOMMERCE_PRODUCT_FEEDS_PLUGIN_URL . 'assets/css/admin.css')
	),
];

foreach (Domain\Taxonomy::cases() as $taxonomy) {
	$hooks = [
		...$hooks,

		new FilterHook($pipeline, "manage_edit-{$taxonomy->value}_columns", fn(array $columns) =>
			$columns += ['product_feeds' => esc_html__('Product feeds', 'product-feeds')]
		),

		new FilterHook($pipeline, "manage_{$taxonomy->value}_custom_column", fn(string $string, string $column_name, int $term_id) =>
			match ($column_name) {
				'product_feeds' => $termPresenter->view($term_id),
				default => $string,
			}
		),

		new ActionHook($pipeline, "{$taxonomy->value}_add_form_fields", fn() =>
			print $termPresenter->addView()
		),

		new ActionHook($pipeline, "{$taxonomy->value}_edit_form_fields", fn(WP_Term $tag) =>
			print $termPresenter->editView($tag->term_id)
		),

		(new ActionHook($pipeline, "created_{$taxonomy->value}", fn(int $term_id) =>
			$termPresenter->save($term_id)
		))->withMiddlewares($verifyNonce),

		(new ActionHook($pipeline, "edited_{$taxonomy->value}", fn(int $term_id) =>
			$termPresenter->save($term_id)
		))->withMiddlewares($verifyNonce),
	];
}

$hooker = $hooker->withHooks(
	...$hooks,

	new ActivationHook($pipeline, __FILE__, function () use ($container, $router) {
		$container->get(MigratorInterface::class)->up();
		$router->up();
	}),

	new DeactivationHook($pipeline, __FILE__, function () use ($container, $router) {
		$container->get(MigratorInterface::class)->down();
		$router->down();
	}),
);

$hooker();
$router();