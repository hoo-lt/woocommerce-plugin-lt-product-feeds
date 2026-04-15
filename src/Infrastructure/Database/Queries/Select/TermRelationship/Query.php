<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Database\Queries\Select\TermRelationship;

use Hoo\WordPressPluginFramework\Database\Queries\QueryException;
use Hoo\WordPressPluginFramework\Database\Queries\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Table\TableInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected TableInterface $table,
		protected Domain\TermMeta $termMeta,
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function __toString(): string
	{
		return strtr($this->query, [
			':termmeta_meta_key' => "'" . $this->termMeta::KEY . "'",
			':termmeta_meta_value' => "'" . $this->termMeta->value . "'",
		]);
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
			':term_taxonomy' => ($this->table)('term_taxonomy'),
			':termmeta' => ($this->table)('termmeta'),
			':term_relationships' => ($this->table)('term_relationships'),
		]);
	}
}