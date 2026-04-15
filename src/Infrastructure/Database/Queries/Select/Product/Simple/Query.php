<?php

namespace Hoo\WooCommercePlugin\LtProductFeeds\Infrastructure\Database\Queries\Select\Product\Simple;

use Hoo\WordPressPluginFramework\Database\Queries\Select\QueryInterface;
use Hoo\WordPressPluginFramework\Database\Queries\QueryException;
use Hoo\WordPressPluginFramework\Database\Table\TableInterface;
use Hoo\WooCommercePlugin\LtProductFeeds\Domain;

readonly class Query implements QueryInterface
{
	protected string $query;

	public function __construct(
		protected TableInterface $table,
		protected array $ids = [],
		protected array $statuses = [],
	) {
		$this->query = $this->query(
			$this->path(),
		);
	}

	public function withIds(int ...$ids): self
	{
		return new self(
			$this->table,
			$ids,
			$this->statuses,
		);
	}

	public function withStatuses(Domain\Products\Product\Status ...$statuses): self
	{
		return new self(
			$this->table,
			$this->ids,
			$statuses,
		);
	}

	public function __toString(): string
	{
		return strtr($this->query, [
			':AND posts.ID' => $this->ids ? 'AND posts.ID IN (' . implode(',', $this->ids) . ')' : '',
			':AND posts.post_status' => $this->statuses ? "AND posts.post_status IN ('" . implode("','", array_map(fn($status) => $status->value, $this->statuses)) . "')" : '',
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
			':term_relationships' => ($this->table)('term_relationships'),
			':posts' => ($this->table)('posts'),
			':term_taxonomy' => ($this->table)('term_taxonomy'),
			':terms' => ($this->table)('terms'),
			':postmeta' => ($this->table)('postmeta'),
		]);
	}
}