<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Database\Queries\Select\Attribute;

use Hoo\WordPressPluginFramework\Database\Queries\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Queries\QueryException;
use Hoo\WordPressPluginFramework\Database\Table\TableInterface;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected TableInterface $table,
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function __toString(): string
	{
		return $this->query;
	}

	protected function path(): string
	{
		$path = __DIR__ . '/Query.sql';
		if (!file_exists($path)) {
			throw new QueryException('.sql file not found');
		}

		return $path;
	}

	protected function query(string $path): string
	{
		return strtr(file_get_contents($path), [
			':woocommerce_attribute_taxonomies' => ($this->table)('woocommerce_attribute_taxonomies'),
			':term_taxonomy' => ($this->table)('term_taxonomy'),
			':terms' => ($this->table)('terms'),
		]);
	}
}